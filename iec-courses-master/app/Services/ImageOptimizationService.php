<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Exception;

/**
 * ImageOptimizationService
 *
 * Handles image optimization for course and lecture thumbnails
 * - Converts images to WebP format with high compression
 * - Generates responsive sizes (200px, 400px, 600px)
 * - Creates PNG fallback for older browsers
 * - Optimizes for PageSpeed Insights
 */
class ImageOptimizationService
{
    private const WEBP_QUALITY = 75; // Reduced from 85 for better compression
    private const PNG_QUALITY = 9;
    private const JPEG_QUALITY = 70;

    /**
     * Responsive image sizes for course/lecture thumbnails
     * Used for srcset and responsive delivery
     */
    private const RESPONSIVE_SIZES = [
        'small' => 200,    // Mobile (45x45 to 200x150)
        'medium' => 400,   // Tablet (100x100 to 400x300)
        'large' => 600,    // Desktop (600x450)
    ];

    /**
     * Target aspect ratios for different image types
     */
    private const ASPECT_RATIO = 4/3; // 1.33

    /**
     * Process and optimize an uploaded image
     *
     * @param UploadedFile $file
     * @param string $directory - Directory to store optimized images (course-images, lecture-images, etc)
     * @param string $type - Image type for naming (course, lecture)
     * @return array ['path' => string, 'filename' => string, 'sizes' => ['small' => path, 'medium' => path, 'large' => path]]
     * @throws Exception
     */
    public function process(UploadedFile $file, string $directory, string $type = 'course'): array
    {
        try {
            // Validate file is actually an image
            if (!$this->isValidImage($file)) {
                throw new Exception('Invalid image file');
            }

            // Generate unique filename with timestamp
            $timestamp = now()->getTimestamp();
            $random = str_random(6);
            $baseFilename = "{$type}-{$timestamp}-{$random}";

            // Load and validate image dimensions
            $image = Image::make($file->getRealPath());

            // Auto-crop to 4:3 aspect ratio (center crop)
            $this->autoCropTo4by3($image);

            // Create responsive versions
            $sizes = [];
            foreach (self::RESPONSIVE_SIZES as $sizeKey => $width) {
                // Save WebP version
                $webpPath = "{$directory}/{$baseFilename}-{$sizeKey}.webp";
                $this->saveWebP($image, $width, $webpPath);
                $sizes[$sizeKey] = $webpPath;

                // Also save PNG fallback for large size only (save space)
                if ($sizeKey === 'large') {
                    $pngPath = "{$directory}/{$baseFilename}-{$sizeKey}.png";
                    $this->savePNG($image, $width, $pngPath);
                    $sizes["{$sizeKey}_png"] = $pngPath;
                }
            }

            // Return the primary (large) path and all responsive sizes
            return [
                'path' => "{$directory}/{$baseFilename}-large.webp",
                'filename' => $baseFilename,
                'sizes' => $sizes,
                'directory' => $directory,
            ];

        } catch (Exception $e) {
            throw new Exception("Image optimization failed: " . $e->getMessage());
        }
    }

    /**
     * Validate if uploaded file is a valid image
     */
    private function isValidImage(UploadedFile $file): bool
    {
        $mimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

        return in_array($file->getMimeType(), $mimeTypes) &&
               getimagesize($file->getRealPath()) !== false;
    }

    /**
     * Auto-crop image to 4:3 aspect ratio (center crop)
     * Maintains image quality by using largest possible area
     */
    private function autoCropTo4by3(&$image): void
    {
        $width = $image->width();
        $height = $image->height();
        $currentRatio = $width / $height;
        $targetRatio = self::ASPECT_RATIO;

        if (abs($currentRatio - $targetRatio) < 0.01) {
            // Already correct aspect ratio
            return;
        }

        if ($currentRatio > $targetRatio) {
            // Image is too wide, crop width
            $newWidth = intval($height * $targetRatio);
            $x = intval(($width - $newWidth) / 2);
            $image->crop($newWidth, $height, $x, 0);
        } else {
            // Image is too tall, crop height
            $newHeight = intval($width / $targetRatio);
            $y = intval(($height - $newHeight) / 2);
            $image->crop($width, $newHeight, 0, $y);
        }
    }

    /**
     * Save image as optimized WebP format
     * WebP provides ~25% better compression than JPEG at same quality
     */
    private function saveWebP($image, int $width, string $path): void
    {
        $height = intval($width / self::ASPECT_RATIO);

        $resized = clone $image;
        $resized->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // Convert to WebP with aggressive compression for mobile
        $webpImage = Image::make($resized->encode('webp', self::WEBP_QUALITY)->stream());
        Storage::disk('public')->put($path, $webpImage->stream());
    }

    /**
     * Save image as PNG fallback (for browsers that don't support WebP)
     * Only creates large size to save space
     */
    private function savePNG($image, int $width, string $path): void
    {
        $height = intval($width / self::ASPECT_RATIO);

        $resized = clone $image;
        $resized->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // Save as PNG with compression
        Storage::disk('public')->put($path, $resized->encode('png', self::PNG_QUALITY)->stream());
    }

    /**
     * Delete all optimized image variations for a file
     */
    public function deleteOptimizedImages(string $baseFilename, string $directory): void
    {
        $patterns = [
            "{$directory}/{$baseFilename}-*.webp",
            "{$directory}/{$baseFilename}-*.png",
        ];

        foreach ($patterns as $pattern) {
            $files = glob(storage_path("app/public/{$pattern}"));
            foreach ($files as $file) {
                if (file_exists($file)) {
                    @unlink($file);
                }
            }
        }
    }

    /**
     * Get responsive image URL for srcset
     * Returns comma-separated list of image URLs with sizes
     */
    public function getSrcset(string $baseFilename, string $directory): string
    {
        $srcset = [];
        foreach (self::RESPONSIVE_SIZES as $sizeKey => $width) {
            $url = asset("storage/{$directory}/{$baseFilename}-{$sizeKey}.webp");
            $srcset[] = "{$url} {$width}w";
        }
        return implode(', ', $srcset);
    }

    /**
     * Get image HTML with responsive srcset and fallback
     */
    public function getImageHtml(
        string $baseFilename,
        string $directory,
        string $alt = 'Image',
        string $class = '',
        array $attributes = []
    ): string
    {
        $srcset = $this->getSrcset($baseFilename, $directory);
        $mainImage = asset("storage/{$directory}/{$baseFilename}-large.webp");
        $fallbackImage = asset("storage/{$directory}/{$baseFilename}-large.png");

        $attributesStr = '';
        foreach ($attributes as $key => $value) {
            $attributesStr .= " {$key}=\"{$value}\"";
        }

        return <<<HTML
<picture>
    <source srcset="{$srcset}" sizes="(max-width: 600px) 200px, (max-width: 1024px) 400px, 600px" type="image/webp">
    <source srcset="{$fallbackImage}" type="image/png">
    <img src="{$mainImage}"
         alt="{$alt}"
         class="{$class}"
         loading="lazy"
         decoding="async"
         {$attributesStr}>
</picture>
HTML;
    }

    /**
     * Get file size comparison (original vs optimized)
     */
    public function getFileSizeInfo(string $baseFilename, string $directory): array
    {
        $totalSize = 0;
        $largeWebP = storage_path("app/public/{$directory}/{$baseFilename}-large.webp");

        if (file_exists($largeWebP)) {
            $totalSize = filesize($largeWebP);
        }

        return [
            'large_webp_size' => $totalSize,
            'estimated_savings' => 'Optimized for mobile delivery',
        ];
    }
}
