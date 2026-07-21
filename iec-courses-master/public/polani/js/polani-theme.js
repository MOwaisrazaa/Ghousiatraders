(() => {
  const $ = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

  const setYear = () => {
    $$("[data-year]").forEach((el) => (el.textContent = String(new Date().getFullYear())));
  };

  const mountIcons = () => {
    const icon = (name) => {
      const g = "rgba(198,164,108,.95)";
      const w = "rgba(255,255,255,.82)";
      switch (name) {
        case "search":
          return `<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="${w}" stroke-width="1.6"><circle cx="11" cy="11" r="6"/><path d="M20 20l-3.5-3.5"/></svg>`;
        case "user":
          return `<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="${w}" stroke-width="1.6"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="8" r="4"/></svg>`;
        case "google":
          return `<svg viewBox="0 0 24 24" width="22" height="22" viewBox="0 0 24 24"><path fill="#4285F4" d="M21.35 11.1H12v2.9h5.35c-.23 1.23-1 2.27-2.08 2.97v2.47h3.36c1.97-1.81 3.11-4.48 3.11-7.62 0-.67-.06-1.32-.19-1.92z"/><path fill="#34A853" d="M12 22c2.7 0 4.96-.9 6.62-2.46l-3.36-2.47c-.93.62-2.12.98-3.26.98-2.51 0-4.64-1.7-5.4-4H3.14v2.54A9.99 9.99 0 0 0 12 22z"/><path fill="#FBBC05" d="M6.6 13.05A5.99 5.99 0 0 1 6.27 11c0-.71.12-1.4.33-2.05V6.41H3.14A9.99 9.99 0 0 0 2 11c0 1.61.39 3.13 1.14 4.46l3.46-2.41z"/><path fill="#EA4335" d="M12 5.97c1.47 0 2.8.51 3.84 1.5l2.88-2.88A9.64 9.64 0 0 0 12 2a9.99 9.99 0 0 0-8.86 5.41l3.46 2.41C7.36 7.67 9.49 5.97 12 5.97z"/></svg>`;
        case "cart":
          return `<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="${w}" stroke-width="1.6"><path d="M6 6h15l-1.5 8H8L6 2H3"/><circle cx="9" cy="20" r="1.6"/><circle cx="18" cy="20" r="1.6"/></svg>`;
        case "x":
          return `<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="${w}" stroke-width="1.6"><path d="M6 6l12 12M18 6L6 18"/></svg>`;
        case "logout":
          return `<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="${w}" stroke-width="1.6"><path d="M10 7V5a2 2 0 0 1 2-2h8v18h-8a2 2 0 0 1-2-2v-2"/><path d="M3 12h12"/><path d="M7 8l-4 4 4 4"/></svg>`;
        case "wa":
          return `<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="${g}" stroke-width="1.6"><path d="M20 12a8 8 0 0 1-12.8 6.4L4 20l1.8-3.1A8 8 0 1 1 20 12z"/><path d="M8.5 9.5c1.2 3 3.8 5.5 6.9 6.7"/><path d="M16 15.5l-1.6.6c-.6.2-1.4 0-2-.4l-1.8-1.2c-.6-.4-1-.9-1.2-1.6l-.7-2c-.2-.6 0-1.3.5-1.7l.7-.6"/></svg>`;
        case "mail":
          return `<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="${g}" stroke-width="1.6"><path d="M4 6h16v12H4z"/><path d="M4 7l8 6 8-6"/></svg>`;
        case "phone":
          return `<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="${g}" stroke-width="1.6"><path d="M6 3l4 3-2 3a14 14 0 0 0 7 7l3-2 3 4-2 2c-1.2 1.2-3.1 1.2-5.4.4-5.5-2-9.7-6.2-11.7-11.7C1.8 6.1 1.8 4.2 3 3l3 0z"/></svg>`;
        case "pin":
          return `<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="${g}" stroke-width="1.6"><path d="M12 22s7-6.1 7-12a7 7 0 0 0-14 0c0 5.9 7 12 7 12z"/><circle cx="12" cy="10" r="2"/></svg>`;
        case "clock":
          return `<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="${g}" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="M12 7v6l4 2"/></svg>`;
        case "lock":
          return `<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="${g}" stroke-width="1.6"><path d="M7 11V8a5 5 0 0 1 10 0v3"/><path d="M6 11h12v10H6z"/></svg>`;
        case "lock-dark":
          return `<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="rgba(23,23,23,.9)" stroke-width="1.6"><path d="M7 11V8a5 5 0 0 1 10 0v3"/><path d="M6 11h12v10H6z"/></svg>`;
        case "money":
          return `<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="${g}" stroke-width="1.6"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="3"/><path d="M5 8h0M19 16h0"/></svg>`;
        case "box":
          return `<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="${g}" stroke-width="1.6"><path d="M4 7l8-4 8 4-8 4-8-4z"/><path d="M4 7v10l8 4 8-4V7"/><path d="M12 11v10"/></svg>`;
        case "badge":
          return `<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="${g}" stroke-width="1.6"><path d="M12 2l3 6 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1 3-6z"/></svg>`;
        case "headset":
          return `<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="${g}" stroke-width="1.6"><path d="M4 12a8 8 0 0 1 16 0v6a2 2 0 0 1-2 2h-2"/><path d="M4 12v6a2 2 0 0 0 2 2h2"/><path d="M8 20v-6"/><path d="M16 20v-6"/></svg>`;
        case "quality":
          return `<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="${g}" stroke-width="1.6"><path d="M12 2l7 4v6c0 5-3 9-7 10-4-1-7-5-7-10V6l7-4z"/><path d="M9 12l2 2 4-5"/></svg>`;
        case "hand":
          return `<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="${g}" stroke-width="1.6"><path d="M8 11V6a2 2 0 0 1 4 0v5"/><path d="M12 11V5a2 2 0 0 1 4 0v7"/><path d="M16 12V7a2 2 0 0 1 4 0v8c0 4-3 7-7 7h-3c-3 0-6-2-7-5l-1-3c-1-2 2-3 3-1l2 3"/></svg>`;
        case "gift":
          return `<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="${g}" stroke-width="1.6"><path d="M20 12v10H4V12"/><path d="M2 7h20v5H2z"/><path d="M12 7v15"/><path d="M12 7h-3a3 3 0 1 1 3-3v3z"/><path d="M12 7h3a3 3 0 1 0-3-3v3z"/></svg>`;
        case "truck":
          return `<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="${g}" stroke-width="1.6"><path d="M3 6h12v10H3z"/><path d="M15 10h4l2 3v3h-6z"/><circle cx="7" cy="18" r="1.6"/><circle cx="18" cy="18" r="1.6"/></svg>`;
        case "men":
          return `<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="${g}" stroke-width="1.6"><path d="M14 3h7v7"/><path d="M21 3l-7 7"/><circle cx="10" cy="14" r="7"/></svg>`;
        case "women":
          return `<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="${g}" stroke-width="1.6"><circle cx="12" cy="9" r="6"/><path d="M12 15v7"/><path d="M9 19h6"/></svg>`;
        case "attar":
          return `<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="${g}" stroke-width="1.6"><path d="M10 2h4v4h-4z"/><path d="M8 6h8l-1 16H9L8 6z"/><path d="M10 10h4"/></svg>`;
        case "oud":
          return `<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="${g}" stroke-width="1.6"><path d="M7 22c4-7 6-9 5-20"/><path d="M12 22c4-7 6-9 5-20"/><path d="M4 18c6-2 10-2 16 0"/></svg>`;
        case "candle":
          return `<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="${g}" stroke-width="1.6"><path d="M9 22h6"/><path d="M8 10h8v12H8z"/><path d="M12 2c2 2 2 4 0 6-2-2-2-4 0-6z"/></svg>`;
        case "star":
          return `<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="${g}" stroke-width="1.6"><path d="M12 2l3 7 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1 3-7z"/></svg>`;
        case "ig":
          return `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="${g}" stroke-width="1.6"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1.2" fill="${g}" stroke="none"/></svg>`;
        case "fb":
          return `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="${g}" stroke-width="1.6"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>`;
        case "tt":
          return `<svg viewBox="0 0 24 24" width="18" height="18" fill="${g}" stroke="none"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.76a4.85 4.85 0 0 1-1.01-.07z"/></svg>`;
        case "yt":
          return `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="${g}" stroke-width="1.6"><rect x="2" y="5" width="20" height="14" rx="4"/><polygon points="10,9 16,12 10,15" fill="${g}" stroke="none"/></svg>`;
        case "li":
          return `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="${g}" stroke-width="1.6"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>`;
        case "visa":
          return `<svg viewBox="0 0 84 40" width="64" height="30" role="img" aria-label="Visa"><rect x="1" y="1" width="82" height="38" rx="10" fill="#fff" stroke="rgba(0,0,0,.12)"/><text x="42" y="26" text-anchor="middle" font-family="Montserrat, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif" font-size="16" font-weight="800" fill="#1A1F71" letter-spacing="2">VISA</text></svg>`;
        case "mc":
          return `<svg viewBox="0 0 84 40" width="64" height="30" role="img" aria-label="Mastercard"><rect x="1" y="1" width="82" height="38" rx="10" fill="#fff" stroke="rgba(0,0,0,.12)"/><circle cx="40" cy="20" r="10" fill="#EB001B"/><circle cx="48" cy="20" r="10" fill="#F79E1B"/><circle cx="44" cy="20" r="10" fill="#FF5F00" opacity=".92"/></svg>`;
        case "amex":
          return `<svg viewBox="0 0 84 40" width="64" height="30" role="img" aria-label="American Express"><rect x="1" y="1" width="82" height="38" rx="10" fill="#2E77BC" stroke="rgba(0,0,0,.12)"/><rect x="8" y="9" width="68" height="22" rx="6" fill="rgba(255,255,255,.14)"/><text x="42" y="26" text-anchor="middle" font-family="Montserrat, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif" font-size="14" font-weight="900" fill="#FFFFFF" letter-spacing="1">AMEX</text></svg>`;
        case "paypal":
          return `<svg viewBox="0 0 84 40" width="64" height="30" role="img" aria-label="PayPal"><rect x="1" y="1" width="82" height="38" rx="10" fill="#fff" stroke="rgba(0,0,0,.12)"/><text x="42" y="26" text-anchor="middle" font-family="Montserrat, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif" font-size="12" font-weight="900" fill="#003087">Pay</text><text x="55" y="26" text-anchor="start" font-family="Montserrat, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif" font-size="12" font-weight="900" fill="#009CDE">Pal</text></svg>`;
        case "applepay":
          return `<svg viewBox="0 0 84 40" width="64" height="30" role="img" aria-label="Apple Pay"><rect x="1" y="1" width="82" height="38" rx="10" fill="#fff" stroke="rgba(0,0,0,.12)"/><path d="M26 18c1-.1 2-.7 2.7-1.5.8-.9 1.3-2.2 1.2-3.5-1.2.1-2.5.8-3.3 1.7-.7.8-1.3 2.1-1.1 3.3.2 0 .3 0 .5 0z" fill="#111"/><path d="M29.8 20.2c-1.6-.1-3 .9-3.8.9-.8 0-2-.9-3.3-.9-1.7 0-3.2 1-4 2.5-1.7 2.9-.4 7.2 1.2 9.6.8 1.2 1.8 2.5 3.1 2.4 1.2 0 1.7-.8 3.2-.8 1.5 0 1.9.8 3.2.8 1.3 0 2.2-1.2 3-2.4.9-1.3 1.3-2.6 1.3-2.7-.0 0-2.6-1-2.6-3.9 0-2.4 2-3.5 2.1-3.6-1.2-1.7-3-1.9-3.4-1.9z" fill="#111"/><text x="56" y="26" text-anchor="middle" font-family="Montserrat, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif" font-size="12" font-weight="900" fill="#111">Pay</text></svg>`;
        default:
          return "";
      }
    };

    $$("[data-icon]").forEach((el) => {
      const name = el.getAttribute("data-icon");
      el.innerHTML = icon(name);
    });
  };

  const mountNav = () => {
    const toggle = $("[data-nav-toggle]");
    const nav = $("[data-nav]");
    if (!toggle || !nav) return;
    toggle.addEventListener("click", () => nav.classList.toggle("is-open"));
    document.addEventListener("click", (e) => {
      const t = e.target;
      if (!(t instanceof Element)) return;
      if (nav.classList.contains("is-open") && !nav.contains(t) && !toggle.contains(t)) nav.classList.remove("is-open");
    });
  };

  const mountSearchDrawer = () => {
    const drawer = $("[data-search]");
    if (!drawer) return;
    const openBtn = $("[data-search-open]");
    const closeEls = $$("[data-search-close]");
    const form = $("[data-search-form]");
    const input = drawer.querySelector("input[type='search']");

    let closeTimer = 0;
    const open = () => {
      drawer.hidden = false;
      window.clearTimeout(closeTimer);
      requestAnimationFrame(() => {
        drawer.classList.add("is-open");
        input?.focus();
      });
    };
    const close = () => {
      drawer.classList.remove("is-open");
      window.clearTimeout(closeTimer);
      closeTimer = window.setTimeout(() => {
        drawer.hidden = true;
      }, 340);
    };

    openBtn?.addEventListener("click", open);
    closeEls.forEach((c) => c.addEventListener("click", close));
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape") close();
    });
    form?.addEventListener("submit", (e) => {
      e.preventDefault();
      const q = input?.value?.trim() || "";
      if (!q) return;
      window.location.href = `/shop?q=${encodeURIComponent(q)}`;
    });
  };

  const mountCollectionFilters = () => {
    const grid = $("[data-product-grid='collection']");
    if (!grid) return;

    // Mobile toggle handling
    const filterToggle = $("[data-filter-toggle]");
    const filtersSidebar = $(".filters");
    if (filterToggle && filtersSidebar) {
      filterToggle.addEventListener("click", () => {
        filtersSidebar.classList.toggle("is-open");
        const isOpen = filtersSidebar.classList.contains("is-open");
        filterToggle.innerHTML = isOpen 
          ? `Filters <span style="font-size: 9px; margin-left: 4px;">▲</span>` 
          : `Filters <span style="font-size: 9px; margin-left: 4px;">▼</span>`;
      });
    }

    const cards = () => $$("[data-card-link]", grid);
    const ui = {
      sort: $("[data-sort]"),
      results: $("[data-results-count]"),
      price: $("[data-filter-price]"),
      priceLabel: $("[data-price-label]"),
      cat: $$("[data-filter-cat]"),
      family: $$("[data-filter-family]"),
      occasion: $$("[data-filter-occasion]"),
      reset: $("[data-reset-filters]"),
    };

    const money = (value) => `Rs ${Number(value || 0).toLocaleString("en-PK")}`;

    const state = () => {
      const cat = ui.cat.find((r) => r.checked)?.value || "all";
      const priceMax = Number(ui.price?.value || 50000);
      const family = ui.family.filter((c) => c.checked).map((c) => c.value);
      const occasion = ui.occasion.filter((c) => c.checked).map((c) => c.value);
      return { cat, priceMax, family, occasion };
    };

    const apply = () => {
      const { cat, priceMax, family, occasion } = state();
      if (ui.priceLabel) ui.priceLabel.textContent = money(priceMax);

      const list = cards();
      list.forEach((card) => {
        const price = Number(card.getAttribute("data-product-price") || 0);
        const category = String(card.getAttribute("data-product-category") || "").toLowerCase();
        const familyText = String(card.getAttribute("data-product-family") || "");
        const occasionText = String(card.getAttribute("data-product-occasion") || "");

        let visible = price <= priceMax;
        if (cat !== "all") visible = visible && category.includes(cat);
        if (family.length) visible = visible && family.some((item) => familyText.includes(item));
        if (occasion.length) visible = visible && occasion.some((item) => occasionText.includes(item));

        card.hidden = !visible;
      });

      const visibleCards = list.filter((card) => !card.hidden);
      const sort = ui.sort?.value || "best";
      if (sort === "price-asc" || sort === "price-desc" || sort === "rating-desc") {
        const sorted = visibleCards.slice().sort((a, b) => {
          const priceA = Number(a.getAttribute("data-product-price") || 0);
          const priceB = Number(b.getAttribute("data-product-price") || 0);
          const ratingA = Number(a.getAttribute("data-product-rating") || 0);
          const ratingB = Number(b.getAttribute("data-product-rating") || 0);
          if (sort === "price-asc") return priceA - priceB;
          if (sort === "price-desc") return priceB - priceA;
          return ratingB - ratingA;
        });
        sorted.forEach((card) => grid.appendChild(card));
      }

      if (ui.results) ui.results.textContent = `Showing 1–${Math.min(12, visibleCards.length)} of ${visibleCards.length} products`;
    };

    ui.sort?.addEventListener("change", apply);
    ui.price?.addEventListener("input", apply);
    ui.cat.forEach((r) => r.addEventListener("change", apply));
    ui.family.forEach((c) => c.addEventListener("change", apply));
    ui.occasion.forEach((c) => c.addEventListener("change", apply));
    ui.reset?.addEventListener("click", () => {
      ui.cat.forEach((r) => (r.checked = r.value === "all"));
      if (ui.price) ui.price.value = "50000";
      ui.family.forEach((c) => (c.checked = false));
      ui.occasion.forEach((c) => (c.checked = false));
      apply();
    });

    apply();
  };

  const mountAddToCartButtons = () => {
    $$("[data-add-to-cart]").forEach((button) => {
      if (button.dataset.mountedAddToCart === "1") return;
      button.dataset.mountedAddToCart = "1";
      button.addEventListener("click", async (event) => {
        event.preventDefault();
        event.stopPropagation();
        const url = button.getAttribute("data-add-url");
        if (!url) return;

        const originalText = button.textContent;
        button.disabled = true;
        button.textContent = "Adding...";

        try {
          const response = await fetch(url, {
            method: "POST",
            headers: {
              "X-Requested-With": "XMLHttpRequest",
              Accept: "application/json",
              "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") || "",
            },
          });

          const data = await response.json().catch(() => ({}));
          if (!response.ok) throw new Error(data.message || "Unable to add item.");

          if (typeof data.cartCount === "number") {
            $$("[data-cart-badge]").forEach((el) => (el.textContent = String(data.cartCount)));
          }

          button.textContent = "Added";
          window.setTimeout(() => {
            button.textContent = originalText;
            button.disabled = false;
          }, 900);
        } catch (error) {
          button.textContent = originalText;
          button.disabled = false;
          alert(error.message || "Unable to add item.");
        }
      });
    });
  };

  const mountCardLinks = () => {
    $$("[data-card-link]").forEach((card) => {
      if (card.dataset.mountedCardLink === "1") return;
      card.dataset.mountedCardLink = "1";
      card.addEventListener("click", (event) => {
        const target = event.target;
        if (!(target instanceof Element)) return;
        if (target.closest("button, a, input, select, textarea, label")) return;
        const url = card.getAttribute("data-card-link");
        if (url) window.location.href = url;
      });
    });
  };

  setYear();
  mountIcons();
  mountNav();
  mountSearchDrawer();
  mountCollectionFilters();
  mountAddToCartButtons();
  mountCardLinks();
})();
