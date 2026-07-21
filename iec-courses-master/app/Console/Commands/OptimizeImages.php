<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeImages extends Command
{
    protected $signature = 'images:optimize 
                            {--directory=lectures : Directory to optimize (lectures, courses, etc.)}
                            {--dry-run : Show what would be done without making changes}';

    protected $description = 'Optimize images by converting to WebP and creating responsive sizes';

    public function handle(): int
    {
        $directory = $this->option('directory');
        $dryRun = $this->option('dry-run');
        
        $this->info("Scanning directory: {$directory}");
        
        if (!extension_loaded('gd')) {
            $this->error('GD extension is required for image optimization. Please install it.');
            return Command::FAILURE;
        }
        
        $files = Storage::disk('public')->allFiles($directory);
        $imageFiles = array_filter($files, function ($file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            return in_array($ext, ['jpg', 'jpeg', 'png', 'gif']);
        });
        
        $this->info("Found " . count($imageFiles) . " images to optimize");
        
        if ($dryRun) {
            $this->warn("DRY RUN - No changes will be made");
        }
        
        $bar = $this->output->createProgressBar(count($imageFiles));
        $bar->start();
        
        $optimized = 0;
        $failed = 0;
        $savedBytes = 0;
        
        foreach ($imageFiles as $file) {
            try {
                $result = $this->optimizeImage($file, $dryRun);
                if ($result['success']) {
                    $optimized++;
                    $savedBytes += $result['saved'];
                }
            } catch (\Exception $e) {
                $failed++;
                $this->newLine();
                $this->error("Failed to optimize {$file}: " . $e->getMessage());
            }
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        $this->info("Optimization complete!");
        $this->info("Optimized: {$optimized}");
        $this->info("Failed: {$failed}");
        $this->info("Estimated space saved: " . $this->formatBytes($savedBytes));
        
        return Command::SUCCESS;
    }
    
    protected function optimizeImage(string $path, bool $dryRun): array
    {
        $content = Storage::disk('public')->get($path);
        $originalSize = strlen($content);
        
        // Create image from string
        $image = @imagecreatefromstring($content);
        if (!$image) {
            return ['success' => false, 'saved' => 0];
        }
        
        $width = imagesx($image);
        $height = imagesy($image);
        
        $pathInfo = pathinfo($path);
        $webpPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.webp';
        
        // Skip if WebP already exists
        if (Storage::disk('public')->exists($webpPath)) {
            imagedestroy($image);
            return ['success' => true, 'saved' => 0];
        }
        
        if ($dryRun) {
            imagedestroy($image);
            return ['success' => true, 'saved' => (int)($originalSize * 0.4)]; // Estimate 40% savings
        }
        
        // Create WebP version
        ob_start();
        imagewebp($image, null, 85);
        $webpContent = ob_get_clean();
        
        Storage::disk('public')->put($webpPath, $webpContent);
        
        // Create responsive sizes
        $sizes = [300, 600];
        foreach ($sizes as $targetWidth) {
            if ($width > $targetWidth) {
                $ratio = $height / $width;
                $targetHeight = (int)($targetWidth * $ratio);
                
                $resized = imagecreatetruecolor($targetWidth, $targetHeight);
                
                // Preserve transparency for PNG
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
                
                imagecopyresampled($resized, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);
                
                ob_start();
                imagewebp($resized, null, 80);
                $resizedContent = ob_get_clean();
                
                $resizedPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '-' . $targetWidth . '.webp';
                Storage::disk('public')->put($resizedPath, $resizedContent);
                
                imagedestroy($resized);
            }
        }
        
        imagedestroy($image);
        
        $newSize = strlen($webpContent);
        return ['success' => true, 'saved' => max(0, $originalSize - $newSize)];
    }
    
    protected function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' bytes';
    }
}

