<?php

function convertToWebp($source, $destination, $quality = 80) {
    $info = getimagesize($source);
    $isPng = $info['mime'] == 'image/png';
    $isJpeg = $info['mime'] == 'image/jpeg';

    if ($isPng) {
        $image = imagecreatefrompng($source);
        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);
    } elseif ($isJpeg) {
        $image = imagecreatefromjpeg($source);
    } else {
        return false;
    }

    // Determine destination if not provided
    if (!$destination) {
        $destination = pathinfo($source, PATHINFO_DIRNAME) . '/' . pathinfo($source, PATHINFO_FILENAME) . '.webp';
    }

    echo "Converting $source to $destination...\n";
    imagewebp($image, $destination, $quality);
    imagedestroy($image);
    return true;
}

$images = [
    'hero-dashboard-1.png',
    'hero-dashboard-2.png',
    'hero-dashboard-3.png',
    'hero-dashboard-4.png',
    'hero-dashboard.png'
];

$dir = __DIR__ . '/public/assets/img/';

foreach ($images as $img) {
    $path = $dir . $img;
    if (file_exists($path)) {
        convertToWebp($path, $dir . pathinfo($img, PATHINFO_FILENAME) . '.webp', 80);
    } else {
        echo "File not found: $path\n";
    }
}

echo "Done.\n";
