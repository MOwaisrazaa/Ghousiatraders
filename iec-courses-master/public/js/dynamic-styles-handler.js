/**
 * Dynamic Styles Handler
 * Applies dynamic styles from data attributes for CSP compliance
 */

(function() {
    'use strict';
    
    let isProcessing = false;
    let processedCount = 0;
    
    /**
     * Apply dynamic progress bar widths
     */
    function applyProgressBarWidths() {
        const progressBars = document.querySelectorAll('.dynamic-progress-bar[data-width]');
        
        progressBars.forEach(bar => {
            const width = bar.getAttribute('data-width');
            if (width !== null && width !== '') {
                // Add loading animation briefly
                bar.classList.add('loading');
                
                // Apply width after a short delay for visual effect
                setTimeout(() => {
                    bar.style.width = width + '%';
                    bar.classList.remove('loading');
                    bar.classList.add('animated');
                    processedCount++;
                }, 100);
            }
        });
    }
    
    /**
     * Apply dynamic modal display states
     */
    function applyModalStates() {
        const modals = document.querySelectorAll('.dynamic-modal[data-show]');
        
        modals.forEach(modal => {
            const show = modal.getAttribute('data-show');
            if (show === 'true') {
                modal.style.display = 'block';
            } else {
                modal.style.display = 'none';
            }
            processedCount++;
        });
    }
    
    /**
     * Handle dynamically added elements
     */
    function setupMutationObserver() {
        if (typeof MutationObserver === 'undefined') return;
        
        const observer = new MutationObserver(function(mutations) {
            let needsUpdate = false;
            
            mutations.forEach(mutation => {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeType === 1) { // Element node
                            // Check for dynamic progress bars
                            if (node.classList && node.classList.contains('dynamic-progress-bar')) {
                                needsUpdate = true;
                            }
                            
                            // Check for dynamic modals
                            if (node.classList && node.classList.contains('dynamic-modal')) {
                                needsUpdate = true;
                            }
                            
                            // Check for child elements
                            if (node.querySelectorAll) {
                                const dynamicElements = node.querySelectorAll('.dynamic-progress-bar[data-width], .dynamic-modal[data-show]');
                                if (dynamicElements.length > 0) {
                                    needsUpdate = true;
                                }
                            }
                        }
                    });
                } else if (mutation.type === 'attributes') {
                    const target = mutation.target;
                    if (target.classList && 
                        (target.classList.contains('dynamic-progress-bar') || 
                         target.classList.contains('dynamic-modal'))) {
                        needsUpdate = true;
                    }
                }
            });
            
            if (needsUpdate) {
                setTimeout(applyDynamicStyles, 50);
            }
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['data-width', 'data-show', 'class']
        });
    }
    
    /**
     * Apply all dynamic styles
     */
    function applyDynamicStyles() {
        if (isProcessing) return;
        isProcessing = true;
        
        try {
            applyProgressBarWidths();
            applyModalStates();
            
            if (processedCount > 0) {
                console.log(`✅ Applied ${processedCount} dynamic styles for CSP compliance`);
            }
        } catch (error) {
            console.error('Error applying dynamic styles:', error);
        } finally {
            isProcessing = false;
        }
    }
    
    /**
     * Initialize the handler
     */
    function initialize() {
        // Apply styles to existing elements
        applyDynamicStyles();
        
        // Set up monitoring for dynamic content
        setupMutationObserver();
        
        // Expose global function for manual application
        window.applyDynamicStyles = applyDynamicStyles;
        
        console.log('🎨 Dynamic Styles Handler initialized');
    }
    
    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize);
    } else {
        initialize();
    }
    
    // Also run on window load
    window.addEventListener('load', function() {
        setTimeout(applyDynamicStyles, 200);
    });
    
    // Expose stats
    window.getDynamicStylesStats = function() {
        return {
            totalProcessed: processedCount,
            isProcessing: isProcessing
        };
    };
    
})();
