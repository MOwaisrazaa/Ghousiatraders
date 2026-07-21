/**
 * Event Handler CSP Fixer
 * Converts inline event handlers to proper event listeners for CSP compliance
 * 
 * Usage: Include this script in your layout and it will automatically fix all inline event handlers
 */

(function() {
    'use strict';
    
    let isProcessing = false;
    let fixedCount = 0;
    
    // Get CSP nonce if available
    const nonce = document.querySelector('meta[name="csp-nonce"]')?.getAttribute('content') || 
                  document.querySelector('script[nonce]')?.getAttribute('nonce') || '';
    
    /**
     * Fix all inline event handlers on the page
     */
    function fixAllEventHandlers() {
        if (isProcessing) return;
        isProcessing = true;
        
        console.log('🔧 Starting event handler CSP fixes...');
        
        try {
            // Common event types to fix
            const eventTypes = [
                'click', 'change', 'submit', 'load', 'error', 
                'mouseover', 'mouseout', 'focus', 'blur', 
                'keydown', 'keyup', 'resize', 'scroll'
            ];
            
            let totalFixed = 0;
            
            eventTypes.forEach(eventType => {
                const handlerAttr = 'on' + eventType;
                const elements = document.querySelectorAll('[' + handlerAttr + ']');
                
                elements.forEach(element => {
                    const handlerCode = element.getAttribute(handlerAttr);
                    if (handlerCode && handlerCode.trim()) {
                        try {
                            // Remove the inline handler
                            element.removeAttribute(handlerAttr);
                            
                            // Add proper event listener
                            element.addEventListener(eventType, function(event) {
                                try {
                                    // Create function with proper context
                                    const func = new Function('event', 'this', handlerCode);
                                    func.call(this, event, this);
                                } catch (error) {
                                    console.error(`Error executing ${eventType} handler:`, error);
                                    console.error('Handler code:', handlerCode);
                                }
                            });
                            
                            totalFixed++;
                        } catch (error) {
                            console.error(`Error fixing ${eventType} handler:`, error);
                        }
                    }
                });
            });
            
            fixedCount += totalFixed;
            
            if (totalFixed > 0) {
                console.log(`✅ Fixed ${totalFixed} event handlers for CSP compliance`);
            }
            
        } catch (error) {
            console.error('Error in event handler fixer:', error);
        } finally {
            isProcessing = false;
        }
    }
    
    /**
     * Fix specific common patterns
     */
    function fixCommonPatterns() {
        // Fix form submissions
        document.querySelectorAll('form[onsubmit]').forEach(form => {
            const onsubmitCode = form.getAttribute('onsubmit');
            if (onsubmitCode) {
                form.removeAttribute('onsubmit');
                form.addEventListener('submit', function(event) {
                    try {
                        const func = new Function('event', onsubmitCode);
                        const result = func.call(this, event);
                        if (result === false) {
                            event.preventDefault();
                        }
                    } catch (error) {
                        console.error('Error in form submit handler:', error);
                    }
                });
            }
        });
        
        // Fix image error handlers
        document.querySelectorAll('img[onerror]').forEach(img => {
            const onerrorCode = img.getAttribute('onerror');
            if (onerrorCode) {
                img.removeAttribute('onerror');
                img.addEventListener('error', function(event) {
                    try {
                        const func = new Function('event', onerrorCode);
                        func.call(this, event);
                    } catch (error) {
                        console.error('Error in image error handler:', error);
                    }
                });
            }
        });
        
        // Fix select change handlers
        document.querySelectorAll('select[onchange]').forEach(select => {
            const onchangeCode = select.getAttribute('onchange');
            if (onchangeCode) {
                select.removeAttribute('onchange');
                select.addEventListener('change', function(event) {
                    try {
                        const func = new Function('event', onchangeCode);
                        func.call(this, event);
                    } catch (error) {
                        console.error('Error in select change handler:', error);
                    }
                });
            }
        });
    }
    
    /**
     * Monitor for dynamically added elements
     */
    function setupMutationObserver() {
        if (typeof MutationObserver === 'undefined') return;
        
        const observer = new MutationObserver(function(mutations) {
            let needsUpdate = false;
            
            mutations.forEach(mutation => {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach(node => {
                        if (node.nodeType === 1) { // Element node
                            // Check if the node has inline event handlers
                            const eventAttrs = Array.from(node.attributes || [])
                                .filter(attr => attr.name.startsWith('on'));
                            
                            if (eventAttrs.length > 0) {
                                needsUpdate = true;
                            }
                            
                            // Check for event handlers in child elements
                            if (node.querySelectorAll) {
                                const elementsWithHandlers = node.querySelectorAll('[onclick], [onchange], [onsubmit], [onerror], [onload]');
                                if (elementsWithHandlers.length > 0) {
                                    needsUpdate = true;
                                }
                            }
                        }
                    });
                } else if (mutation.type === 'attributes' && mutation.attributeName.startsWith('on')) {
                    needsUpdate = true;
                }
            });
            
            if (needsUpdate) {
                setTimeout(fixAllEventHandlers, 100);
            }
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true,
            attributes: true,
            attributeFilter: ['onclick', 'onchange', 'onsubmit', 'onerror', 'onload', 'onmouseover', 'onmouseout']
        });
    }
    
    /**
     * Initialize the fixer
     */
    function initialize() {
        // Fix existing handlers
        fixAllEventHandlers();
        fixCommonPatterns();
        
        // Set up monitoring for dynamic content
        setupMutationObserver();
        
        // Expose global function for manual fixing
        window.fixEventHandlers = fixAllEventHandlers;
        
        console.log('🎯 Event Handler CSP Fixer initialized');
    }
    
    // Run when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize);
    } else {
        initialize();
    }
    
    // Also run on window load to catch any late-loading content
    window.addEventListener('load', function() {
        setTimeout(fixAllEventHandlers, 500);
    });
    
    // Expose stats
    window.getEventHandlerFixStats = function() {
        return {
            totalFixed: fixedCount,
            isProcessing: isProcessing
        };
    };
    
})();
