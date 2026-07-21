/**
 * PurgeCSS Configuration - Phase 3
 * Purpose: Remove unused CSS from corporate-ui-dashboard.purged.min.css
 *
 * This configuration will:
 * 1. Scan all blade templates and JS files
 * 2. Identify used CSS classes
 * 3. Remove unused rules while preserving critical classes
 * 4. Protect auth pages, responsive utilities, and dynamic classes
 *
 * Expected result: 129KB → 70-85KB (45-50% reduction)
 */

module.exports = {
  content: [
    // Blade templates
    './resources/views/**/*.{blade.php,php}',
    // JavaScript files (for dynamic classes)
    './resources/js/**/*.js',
    // Livewire components
    './app/Http/Livewire/**/*.php',
    // Additional Laravel paths
    './app/View/Components/**/*.php',
  ],

  css: [
    './public/assets/css/corporate-ui-dashboard.purged.min.css',
  ],

  output: './public/assets/css/corporate-ui-dashboard.purged-phase3.min.css',

  safelist: {
    // Standard safe list - always keep these
    standard: [
      // Critical utility classes
      /^m[tblrxy]?-[0-9]/,      // Margins
      /^p[tblrxy]?-[0-9]/,      // Padding
      /^gap-[0-9]/,              // Gaps
      /^w-[0-9]/,                // Widths
      /^h-[0-9]/,                // Heights
      /^max-w-/,                 // Max widths
      /^max-h-/,                 // Max heights
      /^min-h-/,                 // Min heights
      /^text-/,                  // Text utilities
      /^bg-/,                    // Background utilities
      /^flex-/,                  // Flexbox
      /^justify-/,               // Justify utilities
      /^align-/,                 // Align utilities
      /^items-/,                 // Items utilities
      /^content-/,               // Content utilities
      /^grid-/,                  // Grid utilities
      /^col-/,                   // Column utilities
      /^row-/,                   // Row utilities
      /^order-/,                 // Order utilities
      /^basis-/,                 // Basis utilities

      // Display and visibility
      /^d-[a-z]/,                // Display classes
      /^display-/,               // Display utilities
      /^visible/,                // Visibility
      /^invisible/,              // Invisibility

      // Positioning
      /^position-/,              // Position
      /^top-/,                   // Top positioning
      /^bottom-/,                // Bottom positioning
      /^left-/,                  // Left positioning
      /^right-/,                 // Right positioning
      /^z-index/,                // Z-index

      // Borders and shadows
      /^border/,                 // Border classes
      /^rounded/,                // Rounded corners
      /^shadow/,                 // Shadows
      /^outline-/,               // Outline

      // Colors and opacity
      /^text-[a-z]/,             // Text colors
      /^bg-[a-z]/,               // Background colors
      /^opacity-/,               // Opacity
      /^accent-/,                // Accent colors

      // Transforms and transitions
      /^transform/,              // Transforms
      /^transition/,             // Transitions
      /^duration-/,              // Duration
      /^ease-/,                  // Easing
      /^scale-/,                 // Scale
      /^rotate-/,                // Rotate
      /^translate-/,             // Translate

      // Spacing edge cases
      /^[mp][tblrxy]?-auto/,     // Auto margins/padding
      /^space-/,                 // Space utilities

      // Screen reader utilities
      'sr-only',
      'not-sr-only',

      // Bootstrap utilities (kept for compatibility)
      'container',
      'container-fluid',
      'row',
      'col',
      /^col-[0-9]/,
      /^col-md-/,
      /^col-lg-/,
      /^col-xl-/,
      /^col-sm-/,

      // Buttons and forms
      /^btn/,                    // All button classes
      /^form-/,                  // All form classes
      'input-group',
      'input-group-prepend',
      'input-group-append',
      'input-group-text',

      // Cards and containers
      /^card/,                   // All card classes
      'card-header',
      'card-body',
      'card-footer',
      'card-text',
      'card-title',
      'card-subtitle',
      'card-link',
      'card-img',
      'card-img-top',
      'card-img-bottom',
      'card-group',
      'card-deck',
      'card-columns',

      // Modals and overlays
      /^modal/,                  // All modal classes
      /^fade/,                   // Fade effect

      // Carousels
      /^carousel/,               // All carousel classes
      /^slide/,                  // Slide class

      // Dropdowns and navs
      /^dropdown/,               // All dropdown classes
      /^nav/,                    // All nav classes

      // Alerts and badges
      /^alert/,                  // All alert classes
      /^badge/,                  // All badge classes

      // Pagination
      /^pagination/,             // All pagination classes
      /^page-/,                  // Page utilities

      // Breadcrumbs
      /^breadcrumb/,             // Breadcrumb classes

      // Tables
      /^table/,                  // All table classes

      // Lists
      /^list-/,                  // List classes

      // Tooltips and popovers
      /^tooltip/,                // Tooltip classes
      /^popover/,                // Popover classes

      // Auth pages - CRITICAL (DO NOT REMOVE)
      'auth-page',
      'auth-page-class',
      'header-logo-section',
      'logo-container-center',
      'header-logo',
      'back-home-btn',
      'card-header',
      'page-header',
      'page-header-min-vh-100',
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
      'text-left',
      'text-right',
      'mb-0',
      'mb-2',
      'mb-3',
      'mb-4',
      'mb-5',
      'mt-0',
      'mt-2',
      'mt-3',
      'mt-4',
      'mt-5',
      'pb-0',
      'pb-3',
      'pt-3',
      'pt-5',
      'px-3',
      'px-5',
      'py-3',
      'py-4',
      'py-5',
      'bg-transparent',
      'bg-white',
      'bg-light',
      'bg-gray-100',
      'bg-gray-200',
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

      // Hero carousel - Phase 1 optimization
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

      // Responsive utilities
      /^d-sm-/,
      /^d-md-/,
      /^d-lg-/,
      /^d-xl-/,
      /^d-xxl-/,
      /^flex-sm-/,
      /^flex-md-/,
      /^flex-lg-/,
      /^flex-xl-/,
      /^justify-sm-/,
      /^justify-md-/,
      /^justify-lg-/,
      /^justify-xl-/,
      /^align-sm-/,
      /^align-md-/,
      /^align-lg-/,
      /^align-xl-/,
      /^m[tblrxy]?-sm-/,
      /^m[tblrxy]?-md-/,
      /^m[tblrxy]?-lg-/,
      /^m[tblrxy]?-xl-/,
      /^p[tblrxy]?-sm-/,
      /^p[tblrxy]?-md-/,
      /^p[tblrxy]?-lg-/,
      /^p[tblrxy]?-xl-/,

      // Hover states
      /^hover:/,                 // Tailwind-style hover
      /^hover-/,                 // Bootstrap-style hover

      // Focus states
      /^focus:/,                 // Tailwind-style focus
      /^focus-/,                 // Bootstrap-style focus

      // Active states
      /^active/,                 // Active states

      // Disabled states
      /^disabled/,               // Disabled states

      // Dynamic pseudo-classes (often used by JS)
      ':hover',
      ':focus',
      ':active',
      ':disabled',
      ':not',

      // Animation utilities
      /^animate-/,               // Animation classes
      /^transition-/,            // Transition classes
      /^duration-/,              // Duration classes
      /^delay-/,                 // Delay classes

      // Admin-specific utilities
      'admin',
      'admin-panel',
      'admin-header',
      'admin-sidebar',
      'admin-content',

      // Course-specific utilities
      'course-card',
      'course-image-container',
      'course-detail',
      'lecture-detail',
      'purchased-course',
      'purchased-lecture',

      // Livewire-specific utilities
      'livewire',
      'wire',
      /^livewire:/,

      // Utilities often used dynamically
      'hidden',
      'block',
      'inline-block',
      'inline',
      'flex',
      'inline-flex',
      'grid',
      'inline-grid',
      'flow-root',
      'contents',
      'list-item',
      'table',
      'table-row',
      'table-cell',
      'table-column',
      'table-column-group',
      'table-caption',
      'table-header-group',
      'table-row-group',
      'table-footer-group',
    ],

    // Deep safelist for complex selectors
    deep: [
      /\.auth-page/,             // Auth page body class
      /\.card-header/,           // Card header variations
      /\.page-header/,           // Page header variations
      /\.btn-/,                  // Button variations
    ],

    // Greedy safelist - keep patterns
    greedy: [
      /^m[tblrxy]?-/,            // All margin utilities
      /^p[tblrxy]?-/,            // All padding utilities
      /^(d-|display)/,           // All display utilities
      /^(text-|font)/,           // All text/font utilities
      /^(bg-|background)/,       // All background utilities
      /^(flex|justify|align)/,   // All flex utilities
      /^(w-|width)/,             // All width utilities
      /^(h-|height)/,            // All height utilities
      /^(col-|row-)/,            // All grid utilities
      /^(btn|card|modal)/,       // All component classes
      /^alert/,                  // All alert variations
      /^badge/,                  // All badge variations
    ],
  },

  // Dynamic attributes for modern CSS
  dynamicAttributes: true,

  // Extract keywords from HTML and other files
  extractors: [
    {
      extractor: (content) => {
        // Extract words from HTML attributes
        return content.match(/[\w-]+(?=['"])/g) || [];
      },
      extensions: ['blade.php', 'php', 'html'],
    },
  ],

  // Keyframe animations to keep
  keyframes: true,

  // CSS variables
  variables: true,

  // Rejected selectors
  rejected: true,

  // Rejected selector output (for debugging)
  rejectedCss: false,
};
