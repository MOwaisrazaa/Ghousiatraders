<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageOptimizer
{
    protected ImageManager $manager;
    
    /**
     * Responsive image sizes for srcset
     */
    protected array $sizes = [
        'thumbnail' => 150,  // For small thumbnails
        'small' => 300,      // For card images
        'medium' => 600,     // For medium displays
        'large' => 900,      // For large displays
    ];

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    /**
     * Optimize and store an uploaded image
     * Creates WebP versions in multiple sizes
     * 
     * @param UploadedFile $file
     * @param string $directory
     * @param int|null $maxWidth Maximum width for the original
     * @return array ['path' => string, 'srcset' => array]
     */
    public function optimizeAndStore(UploadedFile $file, string $directory, ?int $maxWidth = 800): array
    {
        $filename = Str::random(20);
        $basePath = $directory . '/' . $filename;
        
        // Load the original image
        $image = $this->manager->read($file->getRealPath());
        $originalWidth = $image->width();
        $originalHeight = $image->height();
        
        // Calculate aspect ratio
        $aspectRatio = $originalHeight / $originalWidth;
        
        $srcset = [];
        
        // Create responsive sizes
        foreach ($this->sizes as $sizeName => $width) {
            if ($width < $originalWidth) {
                $height = (int) round($width * $aspectRatio);
                $resized = $this->manager->read($file->getRealPath())
                    ->resize($width, $height);
                
                // Save as WebP with good compression
                $webpPath = $basePath . '-' . $sizeName . '.webp';
                $webpContent = $resized->toWebp(quality: 80)->toString();
                Storage::disk('public')->put($webpPath, $webpContent);
                
                $srcset[$sizeName] = [
                    'path' => $webpPath,
                    'width' => $width,
                    'url' => Storage::url($webpPath),
                ];
            }
        }
        
        // Create the main optimized image (WebP)
        $mainWidth = min($maxWidth, $originalWidth);
        $mainHeight = (int) round($mainWidth * $aspectRatio);
        
        $mainImage = $this->manager->read($file->getRealPath())
            ->resize($mainWidth, $mainHeight);
        
        $mainPath = $basePath . '.webp';
        $mainContent = $mainImage->toWebp(quality: 85)->toString();
        Storage::disk('public')->put($mainPath, $mainContent);
        
        // Also save original format as fallback (compressed)
        $originalExtension = strtolower($file->getClientOriginalExtension());
        $fallbackPath = $basePath . '.' . $originalExtension;
        
        $fallbackImage = $this->manager->read($file->getRealPath())
            ->resize($mainWidth, $mainHeight);
        
        if ($originalExtension === 'png') {
            $fallbackContent = $fallbackImage->toPng()->toString();
        } else {
            $fallbackContent = $fallbackImage->toJpeg(quality: 85)->toString();
        }
        Storage::disk('public')->put($fallbackPath, $fallbackContent);
        
        return [
            'path' => $mainPath,
            'fallback_path' => $fallbackPath,
            'srcset' => $srcset,
            'width' => $mainWidth,
            'height' => $mainHeight,
        ];
    }

    /**
     * Generate srcset string for responsive images
     */
    public function generateSrcset(array $srcset): string
    {
        $parts = [];
        foreach ($srcset as $size) {
            $parts[] = $size['url'] . ' ' . $size['width'] . 'w';
        }
        return implode(', ', $parts);
    }

    /**
     * Quick optimization for existing image path (converts to WebP)
     */
    public function optimizeExisting(string $storagePath): ?string
    {
        if (!Storage::disk('public')->exists($storagePath)) {
            return null;
        }

        $content = Storage::disk('public')->get($storagePath);
        $image = $this->manager->read($content);
        
        // Create WebP version
        $pathInfo = pathinfo($storagePath);
        $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
        
        $webpContent = $image->toWebp(quality: 85)->toString();
        Storage::disk('public')->put($webpPath, $webpContent);
        
        return $webpPath;
    }
}

