#!/usr/bin/env node

/**
 * JavaScript Minification Script
 * Minifies all JS files in public/assets/js/extracted/ and public/js/
 */

import fs from 'fs';
import path from 'path';
import { minify } from 'terser';

const directories = [
    'public/assets/js/extracted',
    'public/js'
];

async function minifyFile(filePath) {
    try {
        const code = fs.readFileSync(filePath, 'utf8');
        const result = await minify(code, {
            compress: {
                drop_console: false,
                passes: 2
            },
            mangle: true,
            output: {
                comments: false
            }
        });

        if (result.error) {
            console.error(`❌ Error minifying ${filePath}:`, result.error);
            return false;
        }

        const minPath = filePath.replace(/\.js$/, '.min.js');
        fs.writeFileSync(minPath, result.code);

        const originalSize = code.length;
        const minifiedSize = result.code.length;
        const savings = originalSize - minifiedSize;
        const percent = ((savings / originalSize) * 100).toFixed(1);

        console.log(`✅ ${path.basename(filePath)}`);
        console.log(`   Original: ${(originalSize / 1024).toFixed(1)} KiB`);
        console.log(`   Minified: ${(minifiedSize / 1024).toFixed(1)} KiB`);
        console.log(`   Saved: ${(savings / 1024).toFixed(1)} KiB (${percent}%)\n`);

        return true;
    } catch (error) {
        console.error(`❌ Error processing ${filePath}:`, error.message);
        return false;
    }
}

async function minifyDirectory(dir) {
    if (!fs.existsSync(dir)) {
        console.log(`⚠️  Directory not found: ${dir}`);
        return;
    }

    const files = fs.readdirSync(dir).filter(f => f.endsWith('.js') && !f.endsWith('.min.js'));
    
    console.log(`\n📁 Processing ${dir}...`);
    console.log(`Found ${files.length} files to minify\n`);

    let totalOriginal = 0;
    let totalMinified = 0;

    for (const file of files) {
        const filePath = path.join(dir, file);
        const code = fs.readFileSync(filePath, 'utf8');
        totalOriginal += code.length;

        const result = await minify(code, {
            compress: {
                drop_console: false,
                passes: 2
            },
            mangle: true,
            output: {
                comments: false
            }
        });

        if (!result.error) {
            const minPath = filePath.replace(/\.js$/, '.min.js');
            fs.writeFileSync(minPath, result.code);
            totalMinified += result.code.length;

            const savings = code.length - result.code.length;
            const percent = ((savings / code.length) * 100).toFixed(1);

            console.log(`✅ ${file}`);
            console.log(`   ${(code.length / 1024).toFixed(1)} KiB → ${(result.code.length / 1024).toFixed(1)} KiB (saved ${percent}%)`);
        } else {
            console.error(`❌ ${file}: ${result.error.message}`);
        }
    }

    const totalSavings = totalOriginal - totalMinified;
    const totalPercent = ((totalSavings / totalOriginal) * 100).toFixed(1);
    console.log(`\n📊 Summary for ${dir}:`);
    console.log(`   Total Original: ${(totalOriginal / 1024).toFixed(1)} KiB`);
    console.log(`   Total Minified: ${(totalMinified / 1024).toFixed(1)} KiB`);
    console.log(`   Total Saved: ${(totalSavings / 1024).toFixed(1)} KiB (${totalPercent}%)\n`);
}

async function main() {
    console.log('🚀 Starting JavaScript Minification...\n');

    for (const dir of directories) {
        await minifyDirectory(dir);
    }

    console.log('✨ Minification complete!');
    console.log('\n📝 Next steps:');
    console.log('1. Update your Blade templates to load .min.js files');
    console.log('2. Or use a build tool like Vite to handle minification automatically');
}

main().catch(console.error);

