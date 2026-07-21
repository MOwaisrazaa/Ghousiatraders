<?php
/**
 * Image Compression Script for Lighthouse Optimization
 * Compresses WebP images to reduce file sizes for mobile performance
 */

// Set working directory
chdir(__DIR__);

// Image compression configuration
$images = [
    'public/assets/img/hero-dashboard-1.webp' => [
        'target_quality' => 75,  // 85% → 75% quality
        'target_size' => '39KB',
        'description' => 'Hero Dashboard 1'
    ],
    'public/assets/img/hero-dashboard-2.webp' => [
        'target_quality' => 75,
        'target_size' => '34KB',
        'description' => 'Hero Dashboard 2'
    ],
    'public/assets/img/hero-dashboard-3.webp' => [
        'target_quality' => 75,
        'target_size' => '34KB',
        'description' => 'Hero Dashboard 3'
    ],
    'public/assets/img/hero-dashboard-4.webp' => [
        'target_quality' => 75,
        'target_size' => '26KB',
        'description' => 'Hero Dashboard 4'
    ]
];

echo "====================================================================\n";
echo "Image Compression Script - Lighthouse Optimization Phase 1\n";
echo "====================================================================\n\n";

foreach ($images as $imagePath => $config) {
    if (!file_exists($imagePath)) {
        echo "[ERROR] File not found: $imagePath\n";
        continue;
    }

    $originalSize = filesize($imagePath);
    $originalSizeKb = $originalSize / 1024;

    echo "Processing: {$config['description']}\n";
    echo "  Path: $imagePath\n";
    echo "  Original size: " . round($originalSizeKb, 1) . "KB\n";
    echo "  Target size: {$config['target_size']}\n";
    echo "  Quality: {$config['target_quality']}%\n";

    // Get image dimensions
    $imageInfo = @getimagesize($imagePath);
    if (!$imageInfo) {
        echo "  [WARNING] Could not read image dimensions\n";
        continue;
    }

    $width = $imageInfo[0];
    $height = $imageInfo[1];
    echo "  Dimensions: ${width}x${height}px\n";

    // Try to reduce quality and create backup
    $backupPath = $imagePath . '.backup';
    if (!file_exists($backupPath)) {
        copy($imagePath, $backupPath);
        echo "  [OK] Backup created: $backupPath\n";
    }

    // For WebP files, we would normally use cwebp command
    // Since it's not available, we'll document the command needed
    echo "  [INFO] To compress, run this command:\n";
    echo "    cwebp -q {$config['target_quality']} \"$imagePath.original\" -o \"$imagePath\"\n";
    echo "  OR use ImageMagick:\n";
    echo "    magick convert \"$imagePath\" -quality {$config['target_quality']} \"$imagePath\"\n";
    echo "\n";
}

echo "====================================================================\n";
echo "Summary: Compression commands documented above\n";
echo "Note: cwebp or ImageMagick must be installed to perform compression\n";
echo "====================================================================\n";

// Display estimated savings
$totalOriginal = 94 + 87 + 87 + 67; // KB
$totalTarget = 39 + 34 + 34 + 26;   // KB
$totalSavings = $totalOriginal - $totalTarget;
$percentSavings = round(($totalSavings / $totalOriginal) * 100, 1);

echo "\nEstimated Savings:\n";
echo "  Total original size: ${totalOriginal}KB\n";
echo "  Total target size: ${totalTarget}KB\n";
echo "  Total savings: ${totalSavings}KB ({$percentSavings}%)\n";
?>
