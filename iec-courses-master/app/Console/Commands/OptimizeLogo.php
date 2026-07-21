<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OptimizeLogo extends Command
{
    protected $signature = 'logo:optimize';
    protected $description = 'Create optimized versions of the logo';

    public function handle(): int
    {
        $sourcePath = public_path('assets/img/logos/iec-Logo.png');
        
        if (!file_exists($sourcePath)) {
            $this->error("Source logo not found at: {$sourcePath}");
            return Command::FAILURE;
        }
        
        $image = imagecreatefrompng($sourcePath);
        if (!$image) {
            $this->error("Failed to load image");
            return Command::FAILURE;
        }
        
        $width = imagesx($image);
        $height = imagesy($image);
        
        $this->info("Original logo size: {$width}x{$height}");
        
        $sizes = [50, 100];
        
        foreach ($sizes as $size) {
            // Create resized image
            $thumb = imagecreatetruecolor($size, $size);
            
            // Preserve transparency
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
            $transparent = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
            imagefilledrectangle($thumb, 0, 0, $size, $size, $transparent);
            
            // Resize
            imagecopyresampled($thumb, $image, 0, 0, 0, 0, $size, $size, $width, $height);
            
            // Save WebP
            $webpPath = public_path("assets/img/logos/iec-Logo-{$size}.webp");
            imagewebp($thumb, $webpPath, 90);
            $this->info("Created: iec-Logo-{$size}.webp");
            
            // Save PNG for 50px (fallback)
            if ($size === 50) {
                $pngPath = public_path("assets/img/logos/iec-Logo-{$size}.png");
                imagepng($thumb, $pngPath, 9);
                $this->info("Created: iec-Logo-{$size}.png");
            }
            
            imagedestroy($thumb);
        }
        
        imagedestroy($image);
        
        $this->info("Logo optimization complete!");
        return Command::SUCCESS;
    }
}

