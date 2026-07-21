<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class CarouselImageProcessor
{
    /**
     * Responsive image sizes to generate (in pixels).
     */
    protected array $sizes = [
        '400w' => 400,
        '800w' => 800,
        '1200w' => 1200,
    ];

    /**
     * WebP quality setting.
     * Reduced to 75 for better mobile performance while maintaining visual quality
     * Saves ~30-40% more bandwidth compared to 85
     */
    protected int $webpQuality = 75;

    /**
     * PNG quality setting.
     */
    protected int $pngQuality = 9; // 0-9 compression level (9 = maximum compression)

    /**
     * Valid aspect ratio range (4:3 = 1.333, with tolerance 1.2-1.4).
     */
    protected float $minAspectRatio = 1.2;
    protected float $maxAspectRatio = 1.4;

    /**
     * Process uploaded image: validate, optimize, generate responsive versions.
     *
     * @param UploadedFile $file Uploaded image file
     * @param string $baseName Base name for image files (without extension)
     * @return bool True on success
     * @throws \Exception
     */
    public function process(UploadedFile $file, string $baseName): bool
    {
        try {
            // 1. Validate image
            $this->validateImage($file);

            // Check if GD extension is loaded. If not, use fallback (copy file directly).
            if (!extension_loaded('gd') || !function_exists('imagecreatefromjpeg')) {
                $carouselPath = $this->getCarouselPath();
                if (!file_exists($carouselPath)) {
                    mkdir($carouselPath, 0755, true);
                }

                $originalPath = $file->getRealPath();
                copy($originalPath, "{$carouselPath}/{$baseName}.webp");
                copy($originalPath, "{$carouselPath}/{$baseName}-400w.webp");
                copy($originalPath, "{$carouselPath}/{$baseName}-800w.webp");
                copy($originalPath, "{$carouselPath}/{$baseName}-1200w.webp");
                copy($originalPath, "{$carouselPath}/{$baseName}.png");

                return true;
            }

            // 2. Create carousel directory if needed
            $carouselPath = $this->getCarouselPath();
            if (!file_exists($carouselPath)) {
                mkdir($carouselPath, 0755, true);
            }

            // 3. Load original image
            $image = $this->loadImage($file->getRealPath());
            if (!$image) {
                throw new \Exception('Failed to load image');
            }

            // 4. Get dimensions
            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);

            // 5. Auto-crop image to 4:3 aspect ratio
            $cropResult = $this->autoCropTo4by3($image, $originalWidth, $originalHeight);
            $croppedImage = $cropResult['resource'];
            $croppedWidth = $cropResult['width'];
            $croppedHeight = $cropResult['height'];

            // 6. Save original upload format as WebP (format conversion)
            // This ensures PNG/JPEG uploads are converted to WebP immediately
            $originalWebpPath = "{$carouselPath}/{$baseName}.webp";
            $this->saveWebP($croppedImage, $originalWebpPath);

            // 7. Generate responsive WebP versions from cropped image
            foreach ($this->sizes as $suffix => $targetWidth) {
                if ($croppedWidth >= $targetWidth) {
                    $resized = $this->resizeImage($croppedImage, $targetWidth, $croppedWidth, $croppedHeight);
                    $webpPath = "{$carouselPath}/{$baseName}-{$suffix}.webp";
                    $this->saveWebP($resized, $webpPath);
                    imagedestroy($resized);
                } else {
                    // If target width is larger than cropped width, use cropped image as-is
                    $webpPath = "{$carouselPath}/{$baseName}-{$suffix}.webp";
                    $this->saveWebP($croppedImage, $webpPath);
                }
            }

            // 8. Save PNG fallback at 1200w size (or max available size)
            // PNG is kept for browser compatibility and fallback
            $pngResized = $this->resizeImage($croppedImage, 1200, $croppedWidth, $croppedHeight);
            $pngPath = "{$carouselPath}/{$baseName}.png";
            $this->savePNG($pngResized, $pngPath);
            imagedestroy($pngResized);

            // 9. Clean up cropped and original
            imagedestroy($croppedImage);
            imagedestroy($image);

            return true;
        } catch (\Exception $e) {
            // Clean up any partially created files
            $this->cleanup($baseName);
            throw $e;
        }
    }

    /**
     * Validate uploaded image file exists and is readable.
     *
     * @param UploadedFile $file
     * @throws \Exception
     */
    protected function validateImage(UploadedFile $file): void
    {
        // Just validate file is readable
        $dimensions = getimagesize($file->getRealPath());
        if (!$dimensions) {
            throw new \Exception('Invalid image file or file format not supported');
        }
    }

    /**
     * Load image from file path, detecting format.
     *
     * @param string $path Path to image file
     * @return resource|false Image resource or false on failure
     */
    protected function loadImage(string $path)
    {
        $mimeType = mime_content_type($path);

        return match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($path),
            'image/png' => imagecreatefrompng($path),
            'image/webp' => imagecreatefromwebp($path),
            default => false,
        };
    }

    /**
     * Auto-crop image to 4:3 aspect ratio (center crop).
     * Intelligently crops the image to exact 4:3 ratio by cropping from edges.
     *
     * @param resource $sourceImage Source image resource
     * @param int $originalWidth Original image width
     * @param int $originalHeight Original image height
     * @return array Array with keys: resource, width, height
     */
    protected function autoCropTo4by3($sourceImage, int $originalWidth, int $originalHeight)
    {
        // Target aspect ratio: 4:3 = 1.333...
        $targetRatio = 4 / 3;
        $currentRatio = $originalWidth / $originalHeight;

        $cropX = 0;
        $cropY = 0;
        $cropWidth = $originalWidth;
        $cropHeight = $originalHeight;

        if ($currentRatio > $targetRatio) {
            // Image is too wide - crop width
            $cropWidth = (int) ($originalHeight * $targetRatio);
            $cropX = (int) (($originalWidth - $cropWidth) / 2); // Center horizontally
        } else {
            // Image is too tall - crop height
            $cropHeight = (int) ($originalWidth / $targetRatio);
            $cropY = (int) (($originalHeight - $cropHeight) / 2); // Center vertically
        }

        // Create cropped image
        $cropped = imagecreatetruecolor($cropWidth, $cropHeight);

        // Preserve transparency
        imagealphablending($cropped, false);
        imagesavealpha($cropped, true);

        // Copy the cropped region
        imagecopyresampled(
            $cropped,
            $sourceImage,
            0,
            0,
            $cropX,
            $cropY,
            $cropWidth,
            $cropHeight,
            $cropWidth,
            $cropHeight
        );

        return [
            'resource' => $cropped,
            'width' => $cropWidth,
            'height' => $cropHeight,
        ];
    }

    /**
     * Resize image to target width maintaining aspect ratio.
     *
     * @param resource $sourceImage Source image resource
     * @param int $targetWidth Target width in pixels
     * @param int $originalWidth Original image width
     * @param int $originalHeight Original image height
     * @return resource Resized image resource
     */
    protected function resizeImage($sourceImage, int $targetWidth, int $originalWidth, int $originalHeight)
    {
        // Calculate target height maintaining aspect ratio
        $ratio = $originalHeight / $originalWidth;
        $targetHeight = (int) ($targetWidth * $ratio);

        // Create new image
        $resized = imagecreatetruecolor($targetWidth, $targetHeight);

        // Preserve transparency
        imagealphablending($resized, false);
        imagesavealpha($resized, true);

        // Resample
        imagecopyresampled(
            $resized,
            $sourceImage,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $originalWidth,
            $originalHeight
        );

        return $resized;
    }

    /**
     * Save image as WebP format.
     *
     * @param resource $image Image resource
     * @param string $path File path to save
     * @return bool True on success
     */
    protected function saveWebP($image, string $path): bool
    {
        return imagewebp($image, $path, $this->webpQuality);
    }

    /**
     * Save image as PNG format.
     *
     * @param resource $image Image resource
     * @param string $path File path to save
     * @return bool True on success
     */
    protected function savePNG($image, string $path): bool
    {
        return imagepng($image, $path, $this->pngQuality);
    }

    /**
     * Get carousel directory path.
     *
     * @return string Carousel directory path
     */
    protected function getCarouselPath(): string
    {
        return public_path('assets/img/carousel');
    }

    /**
     * Cleanup partially created files on error.
     *
     * @param string $baseName Base name of files to delete
     * @return void
     */
    protected function cleanup(string $baseName): void
    {
        $carouselPath = $this->getCarouselPath();
        $patterns = [
            "{$carouselPath}/{$baseName}*.webp",
            "{$carouselPath}/{$baseName}*.png",
        ];

        foreach ($patterns as $pattern) {
            foreach (glob($pattern) as $file) {
                @unlink($file);
            }
        }
    }

    /**
     * Delete all image files for a slide.
     *
     * @param string $imageName Image name (base name without extension)
     * @return bool True if any files were deleted
     */
    public function deleteImages(string $imageName): bool
    {
        $carouselPath = $this->getCarouselPath();
        $patterns = [
            "{$carouselPath}/{$imageName}*.webp",
            "{$carouselPath}/{$imageName}*.png",
        ];

        $deleted = false;
        foreach ($patterns as $pattern) {
            foreach (glob($pattern) as $file) {
                if (@unlink($file)) {
                    $deleted = true;
                }
            }
        }

        return $deleted;
    }
}
