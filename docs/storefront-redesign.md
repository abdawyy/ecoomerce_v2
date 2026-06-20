# Hayah — Storefront Redesign

> **Status: ✅ Implemented** (June 2026)  
> Scope: Public shop (homepage, PLP, PDP, cart, checkout, footer, global shell)  
> Related: [user-panel-redesign.md](./user-panel-redesign.md) · [user-panel-redesign-checklist.md](./user-panel-redesign-checklist.md)

---

## Overview

The Hayah storefront targets a **boutique homewear / fashion** feel. The redesign unified layout, tokens, and customer journey across all shop pages.

---

## Current storefront map (after redesign)

| Page | View | Layout pattern | Status |
|------|------|----------------|--------|
| **Home** | `index.blade.php` | `x-web.layout` + `x-web.product-card` | ✅ |
| **Product list (PLP)** | `product/list.blade.php` | `x-web.layout` + sticky filters + mobile drawer | ✅ |
| **Product detail (PDP)** | `product/show.blade.php` | `x-web.layout` + sticky mobile CTA | ✅ |
| **Cart** | `cart/index.blade.php` | `x-web.layout` + checkout stepper | ✅ |
| **Checkout** | `checkout/address.blade.php` | `x-web.layout` + stepper + saved addresses | ✅ |
| **Receipt** | `checkout/receipt.blade.php` | White confirmation card + invoice link | ✅ |
| **Guides** | `guides/index.blade.php` | `x-web.layout` | ✅ |
| **Contact / Legal** | `contact_us.blade.php`, `legal.blade.php` | `x-web.layout` | ✅ |
| **My Account** | `account/*` | `x-account.layout` inside `x-web.layout` | ✅ |

### Key components

| Component | Path | Role | Status |
|-----------|------|------|--------|
| Layout | `components/web/layout.blade.php` | Single `<html>`, SEO, fonts, scripts | ✅ |
| Navbar | `components/web/navbar.blade.php` | Promo bar, nav, search, account, offcanvas | ✅ |
| Footer | `components/web/footer.blade.php` | 4 columns + social + toastr | ✅ |
| Product card | `components/web/product-card.blade.php` | Shared grid card + hover bag link | ✅ |
| Checkout stepper | `components/web/checkout-stepper.blade.php` | Cart → Details → Confirm | ✅ |
| Breadcrumb | `components/web/breadcrumb.blade.php` | PLP + PDP | ✅ |
| Social links | `components/web/social-links.blade.php` | Admin-configurable URLs | ✅ |
| Header (legacy) | `components/web/header.blade.php` | Deprecated stub | ✅ |
| Sidebar (legacy) | `components/web/sidebar.blade.php` | Deprecated stub | ✅ |

---

## Critical structural issues — ✅ All resolved

| # | Issue | Status |
|---|--------|--------|
| 1 | Invalid HTML (nested documents) | ✅ Fixed — single `x-web.layout` |
| 2 | Three parallel design systems | ✅ Fixed — `storefront-shell.css` + theme; some page inline CSS remains (acceptable) |
| 3 | Duplicate mobile navigation | ✅ Fixed — one `#mobileMenu` offcanvas |
| 4 | No account entry in navbar | ✅ Fixed — login + auth dropdown |
| 5 | Footer underbuilt | ✅ Fixed — shop / help / legal / social columns |
| 6 | RTL inconsistency | ✅ Fixed — RTL bootstrap + RTL filter offcanvas on PLP |
| 7 | Global `box-shadow: none !important` | ✅ Fixed — removed from `style.css` |

---

## Design direction — ✅ Implemented

**“Boutique homewear”** patterns in production:

- ✅ Portrait product ratio (`aspect-ratio: 4/5`)
- ✅ Hover reveal bag button (`x-web.product-card`)
- ✅ Full-bleed category blocks with gradient overlay
- ✅ Black promo bar + uppercase nav links
- ✅ Rounded corners (15–20px), minimal borders
- ✅ Cairo + El Messiri everywhere (no Inter duplicates)

### Design tokens — ✅ in `storefront-shell.css`

```css
:root {
  --brand-black: #111111;
  --brand-white: #ffffff;
  --brand-muted: #6b7280;
  --brand-soft: #f9f9f9;
  --brand-sale: #ff4b2b;
  --brand-accent: #2aaee7;
  --radius-lg: 20px;
  --radius-md: 12px;
  --font-display: "El Messiri", serif;
  --font-body: "Cairo", sans-serif;
}
```

Dark mode overrides: `storefront-theme.css`

---

## Global shell — ✅ Done

```
x-web.layout
├── <head> (once): SEO, fonts, storefront CSS, bootstrap [+ RTL]
├── promo-bar
├── navbar (nav + icons + account + theme toggle)
├── mobile offcanvas (single menu)
├── search modal
├── {{ $slot }}
└── footer + scripts (once)
```

| Navbar element | Status |
|----------------|--------|
| Search modal | ✅ |
| Bag + cart count | ✅ |
| Language EN / AR | ✅ |
| Account (guest → login; auth → dropdown) | ✅ |
| Sticky header | ✅ |

---

## Page-by-page status

### Homepage — ✅ Done

| Item | Status |
|------|--------|
| Admin-editable hero | ✅ |
| Product carousel + sale badges | ✅ |
| Hover bag button on cards | ✅ |
| Category images from branding | ✅ |
| Hero tagline from branding / lang | ✅ |
| `x-web.product-card` partial | ✅ |
| Mobile carousel swipe hint | ✅ |
| RTL hero gradient | ✅ |

### PLP — ✅ Done

| Area | Status |
|------|--------|
| Shared layout (not standalone HTML) | ✅ |
| Desktop sticky filter column | ✅ |
| Filters: category, price, type, color, size, stock, **on sale** | ✅ |
| Mobile filter offcanvas (RTL-aware) | ✅ |
| Sort: newest, price ↑↓ | ✅ |
| Shared `product-card` (4 cols desktop, 2 mobile) | ✅ |
| Active filter chips | ✅ |
| Title + product count + breadcrumb | ✅ |
| Empty state + clear filters | ✅ (text; no illustration) |

### PDP — ✅ Mostly done

| Area | Status |
|------|--------|
| Image gallery + thumbnails | ✅ |
| Size selectors + disabled states | ✅ |
| Color display (when set on product) | ✅ |
| Reviews + guest login prompt | ✅ |
| Mobile sticky bottom bar | ✅ |
| Breadcrumb | ✅ |
| Related products (4, same category) | ✅ |
| Review star average + count | ✅ |
| SEO / JSON-LD | ✅ |
| Size guide modal | ⏸️ Product guide PDF link only (optional) |
| Review pagination | ⏸️ Deferred |

### Cart — ✅ Done

| Area | Status |
|------|--------|
| Line items + sticky summary | ✅ |
| Larger images, +/- qty, delete confirm | ✅ |
| Promo code field | ✅ |
| Empty cart + featured products | ✅ |
| Trust line under summary | ✅ |
| Guest vs auth hint + login prompt | ✅ |
| Checkout stepper | ✅ |

### Checkout — ✅ Done

| Area | Status |
|------|--------|
| Stepper (Cart → Details → Confirm) | ✅ |
| Pre-fill name (logged-in) | ✅ |
| Saved addresses dropdown | ✅ |
| City dropdown from admin `cities` | ✅ |
| Sticky order summary | ✅ |
| Guest “Create account after order” checkbox | ✅ |
| Inline validation errors | ✅ |
| Promo code (cart session + checkout field) | ✅ |

### Receipt — ✅ Done

| Area | Status |
|------|--------|
| White confirmation card (no hero overlay) | ✅ |
| Order #, total, delivery fee | ✅ |
| Back to shop | ✅ |
| View order (when logged in) | ✅ |
| Download invoice (signed URL, 48h) | ✅ |
| Email confirmation message on page | ✅ |

### Footer — ✅ Done (optional items deferred)

| Column | Status |
|--------|--------|
| **Shop** — categories, all products | ✅ |
| **Help** — contact, guides, cart, orders/login | ✅ |
| **Legal** — terms, privacy, cookies | ✅ |
| **Social** — admin-configurable links | ✅ |
| FAQ link | ⏸️ Optional — not added |
| Newsletter | ⏸️ Future |

### Secondary pages

| Page | Status |
|------|--------|
| **Guides** — card grid | ✅ |
| **Contact** — form + toastr | ✅ |
| **Contact** — map | ⏸️ Deferred |
| **Legal** — prose width + sticky in-page nav | ✅ |

---

## Customer journey — ✅ Complete

```
Browse (home / PLP)
    → Product (PDP)
    → Cart
    → Checkout
    → Receipt
    → My Account → Order detail + invoice
```

---

## Mobile-first checklist — ✅ All done

- [x] Single offcanvas menu (RTL: `offcanvas-end` for Arabic nav; PLP filter RTL-aware)
- [x] Sticky header with cart count
- [x] Sticky “Add to bag” on PDP
- [x] Filter drawer on PLP
- [x] Touch-friendly qty controls in cart (+/− buttons)
- [x] Category dropdown hover (desktop) / tap (mobile)
- [x] Load `bootstrap.rtl.min.css` when `dir="rtl"`

---

## Rollout plan — ✅ All phases complete

| Phase | Focus | Status |
|-------|--------|--------|
| **1** | `x-web.layout`, theme CSS, fix HTML duplication | ✅ |
| **2** | `product-card` partial; refactor PLP | ✅ |
| **3** | PDP sticky CTA + breadcrumbs + related products | ✅ |
| **4** | Cart/checkout summary + discount code UI | ✅ |
| **5** | Navbar account links + footer columns | ✅ |
| **6** | RTL pass + remove duplicate sidebar | ✅ |

---

## Quick wins vs larger refactors — ✅ Done

| Quick win | Status |
|-----------|--------|
| Footer links wired | ✅ |
| Login/account icon in navbar | ✅ |
| Shared `product-card` partial | ✅ |
| Centralized tokens (`storefront-shell.css`) | ✅ |
| Branding text on hero | ✅ |

| Larger refactor | Status |
|-----------------|--------|
| Full `x-web.layout` extraction | ✅ |
| PLP off standalone HTML | ✅ |
| Customer `/account/*` section | ✅ |
| Related products | ✅ |
| Newsletter, wishlist, compare | ⏸️ Future |

---

## Optional / deferred (not blocking “done”)

- FAQ page and footer link
- Newsletter signup
- Contact page map embed
- PDP review pagination for long lists
- Dedicated homewear size-guide modal (product guide PDF exists)
- PLP empty-state illustration asset

---

## Technical reference

| Asset / file | Purpose |
|--------------|---------|
| `public/assets/css/storefront-shell.css` | Layout tokens, product card, stepper, receipt |
| `public/assets/css/storefront-theme.css` | Dark mode overrides |
| `public/assets/css/style.css` | Legacy storefront components |
| `public/assets/css/plp.css` | PLP filter sidebar |
| `public/assets/css/account.css` | My Account area |
| `resources/views/components/web/*` | Layout components |
| `app/Services/BrandingService.php` | Hero, images, social links |
| `app/Services/SeoService.php` | Meta, sitemap, JSON-LD |
| `resources/lang/en\|ar/web.php` | Storefront copy |

---

## Summary

1. ✅ **Unified shell** — one layout, shared tokens, valid HTML.
2. ✅ **Reused homepage patterns** — product card across PLP, cart empty, related products.
3. ✅ **Complete journey** — account in navbar, footer columns, checkout steps, receipt + invoice.
4. ✅ **RTL and duplication fixed** — one mobile menu, consistent Bootstrap + RTL CSS.

Customer account details: [user-panel-redesign.md](./user-panel-redesign.md)
