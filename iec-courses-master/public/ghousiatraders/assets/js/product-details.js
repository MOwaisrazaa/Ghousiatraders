/* ==========================================================================
   Ghousia Traders - Dynamic Product Detail Page (PDP) Interactions & Loader
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {

    // 1. Get Product ID from URL parameters (e.g. product-details.html?id=jeep-wrangler)
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id') || 'mercedes-amg';
    const product = typeof getProductById === 'function' ? getProductById(productId) : null;

    if (product) {
        renderProductData(product);
    }

    function renderProductData(p) {
        // Update Document Title
        document.title = `${p.name} | Ghousia Traders`;

        // Update Breadcrumb
        const breadcrumbCat = document.querySelector('.pdp-breadcrumb-list li:nth-child(5) a');
        if (breadcrumbCat) {
            breadcrumbCat.textContent = p.category;
            breadcrumbCat.href = p.categoryUrl || 'shop.html';
        }
        const breadcrumbCurrent = document.querySelector('.pdp-breadcrumb-list .current');
        if (breadcrumbCurrent) {
            breadcrumbCurrent.textContent = p.name;
        }

        // Update Badge
        const badgeElem = document.querySelector('.pdp-badge');
        if (badgeElem) {
            if (p.badge) {
                badgeElem.textContent = p.badge;
                badgeElem.style.display = 'inline-block';
            } else {
                badgeElem.style.display = 'none';
            }
        }

        // Update Gallery Images
        galleryData = (p.images && p.images.length > 0) ? p.images.map((imgSrc, idx) => ({
            src: imgSrc,
            alt: `${p.name} - View ${idx + 1}`
        })) : [
            { src: 'assets/ride_on_toys.png', alt: p.name }
        ];

        // Reset main image & thumbnails
        currentImgIndex = 0;
        const mainImg = document.getElementById('pdpMainImage');
        if (mainImg && galleryData.length > 0) {
            mainImg.src = galleryData[0].src;
            mainImg.alt = galleryData[0].alt;
        }

        const thumbGrid = document.querySelector('.pdp-thumbnails-grid');
        if (thumbGrid) {
            thumbGrid.innerHTML = '';
            galleryData.forEach((imgObj, idx) => {
                const thumbBtn = document.createElement('button');
                thumbBtn.type = 'button';
                thumbBtn.className = `pdp-thumb-item ${idx === 0 ? 'active' : ''}`;
                thumbBtn.setAttribute('data-img', imgObj.src);
                thumbBtn.setAttribute('data-alt', imgObj.alt);
                thumbBtn.innerHTML = `<img src="${imgObj.src}" alt="${imgObj.alt}">`;
                thumbBtn.addEventListener('click', () => updateMainImage(idx));
                thumbGrid.appendChild(thumbBtn);
            });
        }

        // Update Title, Rating, Reviews
        const titleElem = document.querySelector('.pdp-title');
        if (titleElem) titleElem.textContent = p.name;

        const ratingScoreElem = document.querySelector('.pdp-rating-score');
        if (ratingScoreElem) ratingScoreElem.textContent = `(${p.rating || 4.8})`;

        const reviewsLink = document.querySelector('.pdp-reviews-link');
        if (reviewsLink) reviewsLink.textContent = `${p.reviewsCount || 128} Reviews`;

        const tabReviewsBtn = document.getElementById('tab-btn-reviews');
        if (tabReviewsBtn) tabReviewsBtn.textContent = `Reviews (${p.reviewsCount || 128})`;

        // Update Price Box
        const priceElem = document.querySelector('.pdp-price');
        if (priceElem) priceElem.textContent = p.price;

        const oldPriceElem = document.querySelector('.pdp-old-price');
        if (oldPriceElem) {
            if (p.oldPrice) {
                oldPriceElem.textContent = p.oldPrice;
                oldPriceElem.style.display = 'inline';
            } else {
                oldPriceElem.style.display = 'none';
            }
        }

        const saveBadgeElem = document.querySelector('.pdp-save-badge');
        if (saveBadgeElem) {
            if (p.saveText) {
                saveBadgeElem.textContent = p.saveText;
                saveBadgeElem.style.display = 'inline-block';
            } else {
                saveBadgeElem.style.display = 'none';
            }
        }

        // Update Short Description
        const shortDescElem = document.querySelector('.pdp-short-desc');
        if (shortDescElem) shortDescElem.textContent = p.shortDesc;

        // Update Stock Status, Category, SKU
        const metaCategoryLink = document.querySelector('.pdp-sku-category .meta-link');
        if (metaCategoryLink) {
            metaCategoryLink.textContent = p.category;
            metaCategoryLink.href = p.categoryUrl || 'shop.html';
        }

        const metaSkuVal = document.querySelector('.pdp-sku-category .meta-value');
        if (metaSkuVal) metaSkuVal.textContent = p.sku || 'BOC-GT-001';

        // Update Variations
        const variationsContainer = document.querySelector('.pdp-variations');
        if (variationsContainer && p.variations) {
            variationsContainer.innerHTML = '';
            Object.keys(p.variations).forEach(groupKey => {
                const groupObj = p.variations[groupKey];
                const varGroupDiv = document.createElement('div');
                varGroupDiv.className = 'variation-group';

                let optionsHTML = '';
                if (Array.isArray(groupObj.options)) {
                    groupObj.options.forEach(opt => {
                        if (typeof opt === 'object') {
                            const isActive = opt.name === groupObj.default ? 'active' : '';
                            optionsHTML += `
                                <button type="button" class="var-btn color-btn ${isActive}" data-group="${groupKey}" data-val="${opt.name}">
                                    <span class="color-swatch ${opt.class}"></span>
                                    <span>${opt.name}</span>
                                </button>
                            `;
                        } else {
                            const isActive = opt === groupObj.default ? 'active' : '';
                            optionsHTML += `<button type="button" class="var-btn ${isActive}" data-group="${groupKey}" data-val="${opt}">${opt}</button>`;
                        }
                    });
                }

                varGroupDiv.innerHTML = `
                    <label class="variation-label">${groupObj.label}: <span class="selected-value" id="${groupKey}SelectedVal">${groupObj.default}</span></label>
                    <div class="variation-options">
                        ${optionsHTML}
                    </div>
                `;

                variationsContainer.appendChild(varGroupDiv);
            });

            // Rebind click listeners to variation buttons
            const newVarBtns = variationsContainer.querySelectorAll('.var-btn');
            newVarBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const group = btn.getAttribute('data-group');
                    const value = btn.getAttribute('data-val');

                    const groupBtns = variationsContainer.querySelectorAll(`.var-btn[data-group="${group}"]`);
                    groupBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    const labelSpan = document.getElementById(`${group}SelectedVal`);
                    if (labelSpan) labelSpan.textContent = value;
                });
            });
        }

        // Update Feature Strip
        const featureGrid = document.querySelector('.feature-strip-grid');
        if (featureGrid && p.features) {
            featureGrid.innerHTML = '';
            p.features.forEach(feat => {
                const featDiv = document.createElement('div');
                featDiv.className = 'feature-strip-item';
                featDiv.innerHTML = `
                    <div class="feature-icon"><i data-lucide="${feat.icon}"></i></div>
                    <div class="feature-info">
                        <h4>${feat.title}</h4>
                        <p>${feat.desc}</p>
                    </div>
                `;
                featureGrid.appendChild(featDiv);
            });
        }

        // Update Description Tab Bullets
        const checklistUl = document.querySelector('.pdp-feature-checklist');
        if (checklistUl && p.descriptionBullets) {
            checklistUl.innerHTML = '';
            p.descriptionBullets.forEach(bullet => {
                const li = document.createElement('li');
                li.innerHTML = `<i data-lucide="check-circle-2" class="check-icon"></i><span>${bullet}</span>`;
                checklistUl.appendChild(li);
            });
        }

        // Update Specifications Table
        const specsTbody = document.querySelector('.pdp-specs-table tbody');
        if (specsTbody && p.specifications) {
            specsTbody.innerHTML = '';
            p.specifications.forEach(spec => {
                const tr = document.createElement('tr');
                tr.innerHTML = `<th>${spec.label}</th><td>${spec.value}</td>`;
                specsTbody.appendChild(tr);
            });
        }

        // Render Related Products Grid
        const relatedGrid = document.querySelector('.related-products-grid');
        if (relatedGrid && p.relatedIds && typeof PRODUCTS_DATABASE !== 'undefined') {
            relatedGrid.innerHTML = '';
            p.relatedIds.forEach(relId => {
                const relProd = PRODUCTS_DATABASE[relId];
                if (relProd) {
                    const cardDiv = document.createElement('div');
                    cardDiv.className = 'catalog-card';
                    cardDiv.innerHTML = `
                        <button class="wishlist-heart" aria-label="Add to Wishlist" data-name="${relProd.name}"><i data-lucide="heart"></i></button>
                        <div class="catalog-card-img">
                            <a href="product-details.html?id=${relProd.id}"><img src="${relProd.images[0]}" alt="${relProd.name}"></a>
                        </div>
                        <div class="catalog-card-info">
                            <h3><a href="product-details.html?id=${relProd.id}" style="color: inherit; text-decoration: none;">${relProd.name}</a></h3>
                            <span class="catalog-price">${relProd.price}</span>
                            <button class="add-to-cart-btn" data-name="${relProd.name}"><i data-lucide="shopping-cart"></i></button>
                        </div>
                    `;
                    relatedGrid.appendChild(cardDiv);
                }
            });

            // Bind click handlers for related products add to cart buttons
            const relatedCartBtns = relatedGrid.querySelectorAll('.add-to-cart-btn');
            relatedCartBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const name = btn.getAttribute('data-name');
                    const cartCountBadge = document.getElementById('cartCount');
                    if (cartCountBadge) {
                        let val = parseInt(cartCountBadge.textContent) || 0;
                        cartCountBadge.textContent = val + 1;
                    }
                    showToast(`🛒 Added "${name}" to your shopping cart!`);
                });
            });
        }

        // Compile Lucide Icons after DOM injection
        refreshLucideIcons();
    }

    // Lucide Icon compile helper
    function refreshLucideIcons() {
        if (typeof lucide !== 'undefined' && typeof lucide.createIcons === 'function') {
            lucide.createIcons();
        }
    }

    // Gallery & Lightbox Logic
    let galleryData = [
        { src: 'assets/mercedes_amg_front.png', alt: 'Mercedes B/O Car (AMG) Front View' }
    ];
    let currentImgIndex = 0;

    const mainImg = document.getElementById('pdpMainImage');
    const prevArrow = document.getElementById('galleryPrevBtn');
    const nextArrow = document.getElementById('galleryNextBtn');

    function updateMainImage(index) {
        if (!galleryData || galleryData.length === 0) return;
        if (index < 0) index = galleryData.length - 1;
        if (index >= galleryData.length) index = 0;
        currentImgIndex = index;

        if (mainImg) {
            mainImg.style.opacity = '0.4';
            setTimeout(() => {
                mainImg.src = galleryData[currentImgIndex].src;
                mainImg.alt = galleryData[currentImgIndex].alt;
                mainImg.style.opacity = '1';
            }, 150);
        }

        const thumbButtons = document.querySelectorAll('.pdp-thumb-item');
        thumbButtons.forEach((btn, idx) => {
            if (idx === currentImgIndex) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    }

    if (prevArrow) {
        prevArrow.addEventListener('click', () => updateMainImage(currentImgIndex - 1));
    }
    if (nextArrow) {
        nextArrow.addEventListener('click', () => updateMainImage(currentImgIndex + 1));
    }

    // Image Lightbox Zoom Modal
    const zoomBtn = document.getElementById('pdpZoomBtn');
    const lightboxModal = document.getElementById('pdpLightboxModal');
    const lightboxImg = document.getElementById('lightboxImg');
    const lightboxCaption = document.getElementById('lightboxCaption');
    const lightboxClose = document.getElementById('lightboxCloseBtn');

    function openLightbox() {
        if (lightboxModal && lightboxImg && galleryData.length > 0) {
            lightboxImg.src = galleryData[currentImgIndex].src;
            if (lightboxCaption) {
                lightboxCaption.textContent = galleryData[currentImgIndex].alt;
            }
            lightboxModal.classList.add('active');
        }
    }

    function closeLightbox() {
        if (lightboxModal) {
            lightboxModal.classList.remove('active');
        }
    }

    if (zoomBtn) zoomBtn.addEventListener('click', openLightbox);
    if (mainImg) {
        mainImg.style.cursor = 'zoom-in';
        mainImg.addEventListener('click', openLightbox);
    }
    if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
    if (lightboxModal) {
        lightboxModal.addEventListener('click', (e) => {
            if (e.target === lightboxModal) closeLightbox();
        });
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeLightbox();
    });

    // Quantity Controls
    const qtyMinusBtn = document.getElementById('qtyMinusBtn');
    const qtyPlusBtn = document.getElementById('qtyPlusBtn');
    const qtyInput = document.getElementById('qtyInput');

    if (qtyMinusBtn && qtyPlusBtn && qtyInput) {
        qtyMinusBtn.addEventListener('click', () => {
            let val = parseInt(qtyInput.value) || 1;
            if (val > 1) qtyInput.value = val - 1;
        });

        qtyPlusBtn.addEventListener('click', () => {
            let val = parseInt(qtyInput.value) || 1;
            if (val < 10) qtyInput.value = val + 1;
        });
    }

    // Toast Notification Helper
    const toast = document.getElementById('pdpToast');
    const toastMsg = document.getElementById('toastMessage');
    let toastTimeout = null;

    function showToast(message) {
        if (toast && toastMsg) {
            toastMsg.textContent = message;
            toast.classList.add('show');

            if (toastTimeout) clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => {
                toast.classList.remove('show');
            }, 3500);
        }
    }

    // Add to Cart Action
    const addToCartBtn = document.getElementById('pdpAddToCartBtn');
    const cartCountBadge = document.getElementById('cartCount');

    if (addToCartBtn && cartCountBadge) {
        addToCartBtn.addEventListener('click', () => {
            const qty = parseInt(qtyInput ? qtyInput.value : 1) || 1;
            const currentTitle = document.querySelector('.pdp-title')?.textContent || 'Product';
            
            let currentCartVal = parseInt(cartCountBadge.textContent) || 0;
            cartCountBadge.textContent = currentCartVal + qty;

            const cartHeaderBtn = document.getElementById('cartBtn');
            if (cartHeaderBtn) {
                cartHeaderBtn.style.transform = 'scale(1.25)';
                setTimeout(() => cartHeaderBtn.style.transform = 'scale(1)', 300);
            }

            showToast(`🛒 Added ${qty} x ${currentTitle} to your shopping cart!`);
        });
    }

    // Buy Now Action
    const buyNowBtn = document.getElementById('pdpBuyNowBtn');
    if (buyNowBtn) {
        buyNowBtn.addEventListener('click', () => {
            const qty = parseInt(qtyInput ? qtyInput.value : 1) || 1;
            if (cartCountBadge) {
                let currentCartVal = parseInt(cartCountBadge.textContent) || 0;
                cartCountBadge.textContent = currentCartVal + qty;
            }
            showToast(`⚡ Proceeding to checkout...`);
            setTimeout(() => {
                window.location.href = 'checkout.html';
            }, 800);
        });
    }

    // Wishlist Toggle Action
    const wishlistToggleBtn = document.getElementById('pdpWishlistToggle');
    const wishlistCountBadge = document.getElementById('wishlistCount');

    if (wishlistToggleBtn && wishlistCountBadge) {
        wishlistToggleBtn.addEventListener('click', () => {
            let currentWishVal = parseInt(wishlistCountBadge.textContent) || 0;
            const isAdd = !wishlistToggleBtn.classList.contains('active');
            const currentTitle = document.querySelector('.pdp-title')?.textContent || 'Product';

            if (isAdd) {
                wishlistToggleBtn.classList.add('active');
                const textSpan = wishlistToggleBtn.querySelector('.wishlist-text');
                if (textSpan) textSpan.textContent = 'In Wishlist';
                wishlistCountBadge.textContent = currentWishVal + 1;
                showToast(`❤️ Added ${currentTitle} to your wishlist!`);
            } else {
                wishlistToggleBtn.classList.remove('active');
                const textSpan = wishlistToggleBtn.querySelector('.wishlist-text');
                if (textSpan) textSpan.textContent = 'Add to Wishlist';
                wishlistCountBadge.textContent = Math.max(0, currentWishVal - 1);
                showToast(`Removed item from your wishlist.`);
            }

            const headerWishlistBtn = document.getElementById('wishlistBtn');
            if (headerWishlistBtn) {
                headerWishlistBtn.style.transform = 'scale(1.25)';
                setTimeout(() => headerWishlistBtn.style.transform = 'scale(1)', 300);
            }
        });
    }

    // Tabs Navigation
    const tabBtns = document.querySelectorAll('.pdp-tab-btn');
    const tabPanels = document.querySelectorAll('.pdp-tab-panel');

    function activateTab(tabId) {
        tabBtns.forEach(btn => {
            const controls = btn.getAttribute('aria-controls');
            if (controls === tabId) {
                btn.classList.add('active');
                btn.setAttribute('aria-selected', 'true');
            } else {
                btn.classList.remove('active');
                btn.setAttribute('aria-selected', 'false');
            }
        });

        tabPanels.forEach(panel => {
            if (panel.id === tabId) {
                panel.classList.add('active');
            } else {
                panel.classList.remove('active');
            }
        });
    }

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetTab = btn.getAttribute('aria-controls');
            activateTab(targetTab);
        });
    });

    const scrollToReviewsLink = document.getElementById('scrollToReviewsLink');
    const viewAllReviewsBtn = document.getElementById('viewAllReviewsBtn');

    function jumpToReviews(e) {
        e.preventDefault();
        activateTab('tab-reviews');
        const tabsSection = document.getElementById('pdpTabsSection');
        if (tabsSection) {
            tabsSection.scrollIntoView({ behavior: 'smooth' });
        }
    }

    if (scrollToReviewsLink) scrollToReviewsLink.addEventListener('click', jumpToReviews);
    if (viewAllReviewsBtn) viewAllReviewsBtn.addEventListener('click', jumpToReviews);

    // Initial Lucide icon compile
    refreshLucideIcons();
    setTimeout(refreshLucideIcons, 50);
});
