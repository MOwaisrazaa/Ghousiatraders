#!/usr/bin/env node

/**
 * Image Optimization Script - Lighthouse Phase 1
 * Creates responsive image sizes from PNG originals
 * Generates 400w, 800w, 1200w variants for mobile/tablet/desktop
 */

import sharp from 'sharp';
import path from 'path';
import fs from 'fs';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const imagesDir = path.join(__dirname, 'public', 'assets', 'img');

// Image configuration
const images = [
    {
        source: 'hero-dashboard-1.png',
        baseName: 'hero-dashboard-1',
        sizes: [
            { width: 400, height: 300, quality: 75 },
            { width: 800, height: 600, quality: 80 },
            { width: 1200, height: 900, quality: 85 }
        ]
    },
    {
        source: 'hero-dashboard-2.png',
        baseName: 'hero-dashboard-2',
        sizes: [
            { width: 400, height: 300, quality: 75 },
            { width: 800, height: 600, quality: 80 },
            { width: 1200, height: 900, quality: 85 }
        ]
    },
    {
        source: 'hero-dashboard-3.png',
        baseName: 'hero-dashboard-3',
        sizes: [
            { width: 400, height: 300, quality: 75 },
            { width: 800, height: 600, quality: 80 },
            { width: 1200, height: 900, quality: 85 }
        ]
    },
    {
        source: 'hero-dashboard-4.png',
        baseName: 'hero-dashboard-4',
        sizes: [
            { width: 400, height: 300, quality: 75 },
            { width: 800, height: 600, quality: 80 },
            { width: 1200, height: 900, quality: 85 }
        ]
    }
];

// Colors for console output
const colors = {
    reset: '\x1b[0m',
    bright: '\x1b[1m',
    dim: '\x1b[2m',
    red: '\x1b[31m',
    green: '\x1b[32m',
    yellow: '\x1b[33m',
    blue: '\x1b[34m',
    cyan: '\x1b[36m'
};

function log(color, ...args) {
    console.log(color, ...args, colors.reset);
}

async function optimizeImage(imageConfig) {
    log(colors.cyan, `\n📦 Processing: ${imageConfig.baseName}`);

    const sourcePath = path.join(imagesDir, imageConfig.source);
    if (!fs.existsSync(sourcePath)) {
        log(colors.red, `  ❌ Source file not found: ${sourcePath}`);
        return false;
    }

    const sourceStats = fs.statSync(sourcePath);
    log(colors.dim, `  Source size: ${(sourceStats.size / 1024).toFixed(1)}KB`);

    try {
        let totalSavings = 0;

        for (const size of imageConfig.sizes) {
            const outputName = `${imageConfig.baseName}-${size.width}w.webp`;
            const outputPath = path.join(imagesDir, outputName);

            // Process image: resize and convert to WebP
            await sharp(sourcePath)
                .resize(size.width, size.height, {
                    fit: 'cover',
                    position: 'center'
                })
                .webp({ quality: size.quality })
                .toFile(outputPath);

            const outputStats = fs.statSync(outputPath);
            const outputSizeKb = outputStats.size / 1024;

            // Calculate savings
            const estimatedOriginalSize = (sourceStats.size / 1024) * (size.width / 1200);
            const savings = estimatedOriginalSize - outputSizeKb;

            log(colors.green, `  ✅ Generated ${outputName}`);
            log(colors.dim, `     Size: ${outputSizeKb.toFixed(1)}KB | Quality: ${size.quality}%`);

            totalSavings += Math.max(0, savings);
        }

        log(colors.blue, `  💾 Total estimated savings: ~${totalSavings.toFixed(1)}KB`);
        return true;

    } catch (error) {
        log(colors.red, `  ❌ Error processing image:`, error.message);
        return false;
    }
}

async function main() {
    log(colors.bright + colors.cyan, '\n╔════════════════════════════════════════════════════════════╗');
    log(colors.bright + colors.cyan, '║  IMAGE OPTIMIZATION SCRIPT - Lighthouse Phase 1             ║');
    log(colors.bright + colors.cyan, '║  Responsive Image Generation for Mobile Performance         ║');
    log(colors.bright + colors.cyan, '╚════════════════════════════════════════════════════════════╝\n');

    let successCount = 0;
    let failCount = 0;

    for (const imageConfig of images) {
        const success = await optimizeImage(imageConfig);
        if (success) successCount++;
        else failCount++;
    }

    // Summary
    log(colors.cyan, '\n╔════════════════════════════════════════════════════════════╗');
    log(colors.cyan, '║                      OPTIMIZATION SUMMARY                  ║');
    log(colors.cyan, '╚════════════════════════════════════════════════════════════╝\n');

    log(colors.green, `✅ Successfully processed: ${successCount} images`);
    if (failCount > 0) {
        log(colors.red, `❌ Failed: ${failCount} images`);
    }

    log(colors.bright + colors.yellow, '\n📊 Responsive Image Breakpoints Created:\n');
    log(colors.dim, '  Mobile (375-600px):    400x300 @75% quality');
    log(colors.dim, '  Tablet (600-1024px):   800x600 @80% quality');
    log(colors.dim, '  Desktop (1024px+):     1200x900 @85% quality\n');

    log(colors.bright + colors.yellow, '📈 Expected Lighthouse Impact:\n');
    log(colors.dim, '  Mobile image delivery savings: ~40-50KB');
    log(colors.dim, '  Image delivery metric:        Likely to drop below 10KB threshold');
    log(colors.dim, '  LCP improvement:              ~15-25% reduction');
    log(colors.dim, '  FCP improvement:              ~10-15% reduction\n');

    log(colors.bright + colors.yellow, '⏭️  Next Steps:\n');
    log(colors.dim, '  1. Update resources/views/dashboard.blade.php with responsive srcsets');
    log(colors.dim, '  2. Run Lighthouse audit on mobile (375px viewport)');
    log(colors.dim, '  3. Verify all 4 auth pages still display correctly');
    log(colors.dim, '  4. Commit optimized images to git\n');

    log(colors.blue, '✨ Image optimization complete!\n');

    process.exit(failCount > 0 ? 1 : 0);
}

main().catch(error => {
    log(colors.red, '\n❌ Fatal error:', error);
    process.exit(1);
});
