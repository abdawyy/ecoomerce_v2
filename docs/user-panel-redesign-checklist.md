# User Panel Redesign — Checklist

> Tracks implementation against [user-panel-redesign.md](./user-panel-redesign.md)  
> **Status: ✅ Fully implemented** (June 2026)  
> Optional / deferred items listed at the bottom.

---

## Part A — Customer “My Account” — ✅ Complete

| Item | Status | Evidence |
|------|--------|----------|
| `/account` dashboard | ✅ | `AccountController@dashboard`, `account/dashboard.blade.php` |
| `/account/orders` list | ✅ | `account/orders/index.blade.php`, status pills, empty state |
| `/account/orders/{id}` detail | ✅ | `account/orders/show.blade.php`, `x-account.order-stepper` |
| `/account/orders/{id}/invoice` PDF | ✅ | `AccountController@invoice`, `PdfService` |
| `/account/addresses` CRUD | ✅ | `account/addresses/index.blade.php`, default address patch route |
| `/account/profile` + password | ✅ | `account/profile.blade.php`, Jetstream redirect to `/account/profile` |
| `/account/reviews` | ✅ | `account/reviews.blade.php` |
| Account layout + sidebar | ✅ | `components/account/layout.blade.php`, `components/account/sidebar.blade.php` |
| Navbar account entry (guest + auth) | ✅ | `components/web/navbar.blade.php` |
| Mobile account links in offcanvas | ✅ | Same navbar offcanvas |
| Guest → account order merge | ✅ | `GuestAccountMergeService`, `MergeGuestOrdersOnLogin` |
| Receipt “View in account” CTA | ✅ | `checkout/receipt.blade.php` |
| Guest checkout → register CTA | ✅ | Receipt + `create_account` checkbox on checkout |
| Phone on profile | ✅ | Migration `2026_06_04_000010`, `AccountController@updateProfile` |
| Default address (`is_default`) | ✅ | Same migration, `setDefaultAddress` |
| Post-login HOME → `/account` | ✅ | `RouteServiceProvider::HOME` |
| Bilingual EN/AR + RTL | ✅ | Lang files, `x-web.layout` RTL bootstrap |
| Storefront styling (Cairo / El Messiri) | ✅ | `account.css`, shared `x-web.layout` |
| Dark mode (inherits storefront theme) | ✅ | `storefront-theme.css` + theme toggle in navbar |

---

## Part B — Admin CRM — ✅ Complete

| Item | Status | Evidence |
|------|--------|----------|
| User list filters (search, status, has orders, new) | ✅ | `admin/user/list.blade.php`, `userController` |
| User list columns (orders, last order, joined, KPIs) | ✅ | Same list view |
| Guest list mirror (filters + columns) | ✅ | `admin/guest/list.blade.php` |
| Shared CRM profile component | ✅ | `components/admin/customer-profile.blade.php` |
| `AdminCustomerProfileService` | ✅ | Used by `user.show` + `admin.guest.show` |
| KPI strip on profile | ✅ | Customer profile component |
| Orders table on profile | ✅ | Customer profile component |
| Address cards + default label | ✅ | Customer profile component |
| Quick actions (toggle active, email, latest order) | ✅ | Profile header actions |
| Consistent View links | ✅ | `x-admin.view-link` |
| Admin nav grouping (Customers) | ✅ | `app/Support/AdminNav.php` |
| Mobile admin table action layout | ✅ | `style-Dashboard.css`, `admin-theme.css` |

---

## Storefront shell — ✅ Complete

See [storefront-redesign.md](./storefront-redesign.md) for full storefront checklist.

| Item | Status | Notes |
|------|--------|-------|
| `x-web.layout` | ✅ | Single valid HTML document |
| Navbar partial (no duplicate `<html>`) | ✅ | `header.blade.php` deprecated |
| Deprecated `x-web.sidebar` | ✅ | Mobile menu in navbar offcanvas |
| `storefront-shell.css` + `storefront-theme.css` | ✅ | Tokens + dark mode |
| RTL Bootstrap | ✅ | Loaded when locale is `ar` |
| Footer columns | ✅ | Shop, help, legal, social |
| Generic social links (admin branding) | ✅ | `x-web.social-links`, branding settings |
| PLP refactor | ✅ | Shared layout, filters, sort, chips |
| `x-web.product-card` | ✅ | Home, PLP, cart empty, related products |
| PDP sticky CTA + breadcrumbs + related | ✅ | `product/show.blade.php` |
| Checkout stepper | ✅ | `x-web.checkout-stepper` on cart + checkout |
| Cart promo code | ✅ | `CartController@applyPromo` |
| Receipt redesign + invoice download | ✅ | `checkout/receipt.blade.php`, signed URL |
| Pages migrated | ✅ | home, PLP, PDP, cart, checkout, receipt, guides, contact, legal, account |

---

## Optional / deferred (not required for “done”)

| Item | Status | Notes |
|------|--------|-------|
| Admin user list **date range** filter | ⏸️ Deferred | Search + status + new/has-orders implemented; date filter not added |
| FAQ page + footer link | ⏸️ Deferred | Optional in storefront doc |
| Contact page map | ⏸️ Deferred | Location text only |
| PDP review pagination | ⏸️ Deferred | Average + count shown; all reviews still listed |
| Homewear size-guide modal | ⏸️ Deferred | Product guide PDF link when guide exists |
| Newsletter signup | ⏸️ Deferred | Future |
| Wishlist / compare | ⏸️ Deferred | Future |

---

## Verify locally

```bash
php artisan migrate --force
php artisan view:cache
php artisan route:list --name=account
php artisan route:list --name=product
```

**Migration note:** Run `2026_06_04_000010_add_phone_and_address_default.php` and `2026_06_04_000011_add_social_urls_to_site_settings.php` if not already applied.
