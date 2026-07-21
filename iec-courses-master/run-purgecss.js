#!/usr/bin/env node

/**
 * PurgeCSS Runner - Phase 3 CSS Optimization
 * Programmatically runs PurgeCSS to remove unused CSS
 */

import { PurgeCSS } from 'purgecss';
import path from 'path';
import { fileURLToPath } from 'url';
import fs from 'fs';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

console.log('\n╔════════════════════════════════════════════════════════════╗');
console.log('║  PURGECSS - Phase 3 CSS Optimization                       ║');
console.log('║  Removing unused CSS from corporate-ui-dashboard           ║');
console.log('╚════════════════════════════════════════════════════════════╝\n');

async function runPurgeCSS() {
  try {
    const inputCss = path.join(__dirname, 'public/assets/css/corporate-ui-dashboard.purged.min.css');
    const outputCss = path.join(__dirname, 'public/assets/css/corporate-ui-dashboard.purged-phase3.min.css');

    // Get input file size
    const inputStats = fs.statSync(inputCss);
    const inputSize = (inputStats.size / 1024).toFixed(1);
    console.log(`📥 Input CSS file: ${inputSize}KB`);
    console.log(`   Path: public/assets/css/corporate-ui-dashboard.purged.min.css\n`);

    console.log('🔍 Scanning content files for used CSS classes...');

    // Run PurgeCSS
    const purgeCSSResult = await new PurgeCSS().purge({
      content: [
        {
          raw: fs.readFileSync(path.join(__dirname, 'resources/views/dashboard.blade.php'), 'utf8'),
          extension: 'blade',
        },
      ],
      css: [inputCss],
      safelist: {
        standard: [
          // Critical utilities
          /^m[tblrxy]?-[0-9]/,
          /^p[tblrxy]?-[0-9]/,
          /^gap-[0-9]/,
          /^w-[0-9]/,
          /^h-[0-9]/,
          /^max-w-/,
          /^max-h-/,
          /^min-h-/,
          /^text-/,
          /^bg-/,
          /^flex-/,
          /^justify-/,
          /^align-/,
          /^items-/,
          /^content-/,
          /^grid-/,
          /^col-/,
          /^row-/,
          /^d-[a-z]/,
          /^position-/,
          /^top-/,
          /^bottom-/,
          /^left-/,
          /^right-/,
          /^z-index/,
          /^border/,
          /^rounded/,
          /^shadow/,
          /^opacity-/,
          /^transform/,
          /^transition/,
          /^duration-/,
          /^ease-/,
          /^btn/,
          /^form-/,
          /^card/,
          /^modal/,
          /^carousel/,
          /^dropdown/,
          /^nav/,
          /^alert/,
          /^badge/,
          /^pagination/,
          /^breadcrumb/,
          /^table/,
          /^tooltip/,
          /^popover/,

          // Auth pages - CRITICAL
          'auth-page',
          'header-logo-section',
          'logo-container-center',
          'header-logo',
          'back-home-btn',
          'card-header',
          'page-header',
          'card-plain',
          'card-body',
          'form-control',
          'btn-outline-primary',
          'btn-gradient-primary',
          'text-muted',
          'font-weight-black',
          'font-weight-bold',
          'font-weight-bolder',
          'text-dark',
          'text-white',
          'text-center',
          'mb-0',
          'mb-2',
          'mb-3',
          'mb-4',
          'mb-5',
          'mt-0',
          'mt-2',
          'mt-3',
          'pb-3',
          'pt-3',
          'px-3',
          'px-5',
          'py-3',
          'py-4',
          'py-5',
          'bg-transparent',
          'bg-white',
          'bg-light',
          'bg-gray-100',
          'captcha-container',
          'mask',
          'gradient-dark',
          'gradient-primary',
          'opacity-5',
          'h-100',
          'position-relative',
          'z-index-1',
          'display-4',
          'h3',

          // Hero carousel
          'hero-slide-wrapper',
          'hero-picture',
          'hero-slide-img',
          'hero-slide-content',
          'hero-cta-btn',
          'hover-scale',
          'carousel-item',
          'carousel-inner',
          'carousel-control-prev',
          'carousel-control-next',
          'carousel-indicators',
          'active',

          // Responsive
          /^d-sm-/,
          /^d-md-/,
          /^d-lg-/,
          /^d-xl-/,
          /^m[tblrxy]?-sm-/,
          /^m[tblrxy]?-md-/,
          /^m[tblrxy]?-lg-/,
          /^m[tblrxy]?-xl-/,
          /^p[tblrxy]?-sm-/,
          /^p[tblrxy]?-md-/,
          /^p[tblrxy]?-lg-/,
          /^p[tblrxy]?-xl-/,

          // Hover, focus, active
          /^hover-/,
          /^focus-/,
          'active',
          'disabled',

          // General
          'hidden',
          'block',
          'inline-block',
          'inline',
          'flex',
          'grid',
          'container',
          'container-fluid',
          /^input-/,
          'livewire',
          /^livewire:/,
        ],
        deep: [
          /\.auth-page/,
          /\.card-header/,
          /\.page-header/,
          /\.btn-/,
        ],
        greedy: [
          /^m[tblrxy]?-/,
          /^p[tblrxy]?-/,
          /^(d-|display)/,
          /^(text-|font)/,
          /^(bg-|background)/,
          /^(flex|justify|align)/,
          /^(col-|row-)/,
          /^(btn|card|modal)/,
          /^alert/,
          /^badge/,
        ],
      },
      dynamicAttributes: ['data-'],
      keyframes: true,
      variables: true,
    });

    // Write output CSS
    if (purgeCSSResult && purgeCSSResult.length > 0) {
      fs.writeFileSync(outputCss, purgeCSSResult[0].output);

      // Get output file size
      const outputStats = fs.statSync(outputCss);
      const outputSize = (outputStats.size / 1024).toFixed(1);
      const savings = ((inputStats.size - outputStats.size) / 1024).toFixed(1);
      const savingsPercent = (((inputStats.size - outputStats.size) / inputStats.size) * 100).toFixed(1);

      console.log('✅ PurgeCSS optimization complete!\n');
      console.log('📊 Results:');
      console.log(`   Input size:  ${inputSize}KB`);
      console.log(`   Output size: ${outputSize}KB`);
      console.log(`   Savings:     ${savings}KB (${savingsPercent}% reduction)`);
      console.log(`\n   Output file: public/assets/css/corporate-ui-dashboard.purged-phase3.min.css\n`);

      // Expected savings breakdown
      console.log('💾 Lighthouse Impact:');
      console.log(`   Before: 129.1KB used, 109.8KB unused (85% unused)`);
      console.log(`   After:  ${outputSize}KB used, ~${(129.1 - parseFloat(outputSize)).toFixed(1)}KB unused`);
      console.log(`   Est. Lighthouse savings: ${savings}KB\n`);

      console.log('✨ Next steps:');
      console.log('   1. Review the output CSS for any missing styles');
      console.log('   2. Replace corporate-ui-dashboard.purged.min.css with the new file');
      console.log('   3. Test all pages for styling issues');
      console.log('   4. Test auth pages thoroughly');
      console.log('   5. Run Lighthouse audit\n');

      return true;
    } else {
      console.error('❌ No output from PurgeCSS');
      return false;
    }

  } catch (error) {
    console.error('❌ Error running PurgeCSS:', error.message);
    console.error(error);
    return false;
  }
}

// Run the function
runPurgeCSS().then(success => {
  process.exit(success ? 0 : 1);
});
