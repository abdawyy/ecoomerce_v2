# Hayah — Storefront Redesign

> **Status: ✅ Implemented + UI/UX polish (Sept 2026)**  
> Scope: Public shop (homepage, PLP, PDP, cart, checkout, footer, global shell)  
> Related: [storefront-ui-ux.md](./storefront-ui-ux.md) · [storefront-changelog.md](./storefront-changelog.md) · [user-panel-redesign.md](./user-panel-redesign.md)

---

## Overview

The Hayah storefront is a **boutique homewear** shop: one valid HTML shell, shared product cards, bilingual EN/AR (RTL), and a complete path from browse → bag → checkout → receipt → My Account.

This document is the **implementation map**. Visual rules live in [storefront-ui-ux.md](./storefront-ui-ux.md). Recent storefront and admin branding work is listed in [storefront-changelog.md](./storefront-changelog.md).

---

## Current storefront map

| Page | View | Layout | Status |
|------|------|--------|--------|
| **Home** | `index.blade.php` | `x-web.layout` + `x-web.product-card` | ✅ |
| **PLP** | `product/list.blade.php` | Filters, sort, chips, mobile drawer | ✅ |
| **PDP** | `product/show.blade.php` | Gallery, sticky mobile CTA, related | ✅ |
| **Cart** | `cart/index.blade.php` | Stepper, promo, **sales policy panel** | ✅ |
| **Checkout** | `checkout/address.blade.php` | Stepper, saved addresses | ✅ |
| **Receipt** | `checkout/receipt.blade.php` | Confirmation card + invoice | ✅ |
| **Guides / Contact / Legal** | `guides/*`, `contact_us`, `legal` | Shared shell | ✅ |
| **My Account** | `account/*` | `x-account.layout` in `x-web.layout` | ✅ |

### Key components

| Component | Path | Role |
|-----------|------|------|
| Layout | `components/web/layout.blade.php` | One document: SEO, fonts, skip link, slot, footer |
| Navbar | `components/web/navbar.blade.php` | Promo, branding logo, search, **SVG cart + count**, account, theme |
| Footer | `components/web/footer.blade.php` | Shop / Help / Legal + tagline + social (no logo) |
| Product card | `components/web/product-card.blade.php` | 4:5 image, sale badge, hover bag |
| Checkout stepper | `components/web/checkout-stepper.blade.php` | Large 1 Cart → 2 Details → 3 Confirm |
| Breadcrumb | `components/web/breadcrumb.blade.php` | PLP + PDP |
| Social links | `components/web/social-links.blade.php` | Admin URLs + Bootstrap Icons |
| Branding logo | `components/branding/logo.blade.php` | Same logo on storefront + admin |

---

## Structural issues — resolved

| # | Issue | Status |
|---|--------|--------|
| 1 | Nested HTML documents | ✅ `x-web.layout` only |
| 2 | Parallel CSS systems | ✅ Tokens in `storefront-shell.css` |
| 3 | Dual mobile menus | ✅ One `#mobileMenu` offcanvas |
| 4 | No account in navbar | ✅ Login + auth dropdown |
| 5 | Thin footer | ✅ Four columns + logo + social |
| 6 | RTL Bootstrap unused | ✅ Loaded when `dir="rtl"` |
| 7 | Global `box-shadow: none !important` | ✅ Removed |
| 8 | Hardcoded Hayah social URLs | ✅ Branding settings |
| 9 | Hardcoded logo files | ✅ Branding **Main logo** |

---

## Design direction

Boutique homewear:

- Portrait product ratio `4 / 5`
- Hover bag on cards (always visible on touch)
- Full-bleed category blocks
- Black promo bar, uppercase nav, 44px icon buttons
- Frosted sticky header
- Cairo (body) + El Messiri (headings)

Tokens: see [storefront-ui-ux.md](./storefront-ui-ux.md).

---

## Page-by-page

### Homepage ✅
Admin hero image, carousel, shared cards, **two category tiles** (EN/AR name + category redirect from Branding; empty redirect = all products; whole tile is a link), RTL gradient, mobile swipe hint, tighter mobile hero padding.

### PLP ✅
Shared layout, sticky filters (on sale + sort), chips, 4-col grid, empty state, RTL filter drawer.

### PDP ✅
Gallery, sizes, color, reviews average, breadcrumb, related products, sticky Add to bag. Size-guide modal and review pagination remain optional.

### Cart ✅
Sticky summary, promo, qty +/−, guest hint, featured empty state, large numbered stepper, **admin-editable sales & inspection policy**.

### Checkout ✅
Homepage hero banner, large 1–2–3 stepper, saved addresses, city fees, create-account checkbox, promo from cart session. Confirm redirects immediately; PDF/email send after the page loads.

### Receipt ✅
Same hero banner, stepper on Confirm, order totals, invoice download, email-on-the-way note, shop CTA.

### Footer ✅
Shop / Help / Legal + tagline, generic social icons, © 2026. No footer logo.

---

## Branding-driven storefront (latest)

| Setting (Admin → Branding & Media) | Storefront use |
|------------------------------------|----------------|
| **Main logo** | Navbar, auth cards, admin header (not footer) |
| Site name, support email/phone | Shell / contact |
| Social URLs (10 platforms) | Footer + contact icons |
| Cart policy title/body EN+AR | Cart page dark panel |
| Hero + category images | Homepage |
| **Homepage banners (Top & Long Sleeve)** | EN/AR title + category redirect (or all products) |

**Removed from the admin form** (values still used if already stored, else config defaults): primary color, taglines, logo alt, dark logo, favicon, OG image, footer logo, product placeholder.

**Admin dark mode:** homepage banner cards use `.admin-nested-panel`, not `bg-light`.

---

## Customer journey

```
Home / PLP → PDP → Cart (+ policy) → Checkout → Receipt → My Account
```

---

## Mobile-first checklist

- [x] Single offcanvas (RTL-aware)
- [x] Sticky header + cart badge
- [x] 44px toolbar tap targets
- [x] Sticky Add to bag on PDP
- [x] PLP filter drawer
- [x] Cart qty +/−
- [x] Product overlay visible on touch
- [x] Skip to content
- [x] `bootstrap.rtl.min.css` when Arabic

---

## Optional / deferred

- FAQ, newsletter, contact map
- PDP review pagination, dedicated size-guide modal
- PLP empty-state illustration
- Wishlist / compare

---

## Technical reference

| File | Purpose |
|------|---------|
| `public/assets/css/storefront-shell.css` | Tokens, header, cards, stepper, cart policy |
| `public/assets/css/storefront-theme.css` | Dark mode |
| `public/assets/css/plp.css` | Filter sidebar |
| `app/Services/BrandingService.php` | Logo, social, cart policy, images, homepage tiles |
| `public/admin/assets/css/admin-theme.css` | Admin dark mode, nested branding panels |
| `app/Http/Controllers/SiteSettingsController.php` | Branding save (logo, tiles, images, social, policy) |

---

## Summary

1. Unified shell and product card.
2. Complete purchase journey including policy on cart.
3. Generic branding: one logo, configurable social, editable cart copy, editable homepage banners.
4. UI/UX polish: frosted header, numbered stepper, touch-friendly controls.
5. Admin Branding form trimmed; homepage banner cards work in dark mode.

Visual spec: [storefront-ui-ux.md](./storefront-ui-ux.md)
