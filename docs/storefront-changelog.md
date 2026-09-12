# Storefront changelog (recent)

Work done after the original storefront redesign phases, including the September 2026 UI/UX pass.

---

## Branding logo (admin + storefront)

**Ask:** One editable logo for admin and storefront, from Branding & Media.

**Done**
- `x-branding.logo` is the only logo component.
- Storefront navbar + footer, admin navbar + login, Jetstream auth card.
- Nested `<a>` around the logo removed (`:link="false"` when the parent is already a link).
- Admin favicon from branding.
- Branding UI: **Logos & media** section with preview of current main logo.

**Admin:** Branding & Media → Main logo → Save.

---

## Generic social icons & links

**Ask:** No hardcoded Hayah Instagram/Facebook.

**Done**
- Platforms live in `config/branding.php` → `social_platforms`.
- URLs on `site_settings` (Instagram, Facebook, TikTok, YouTube, WhatsApp, X, LinkedIn, Telegram, Pinterest, Snapchat).
- Icons: Bootstrap Icons, matched from URL host.
- WhatsApp accepts a phone number or `wa.me` link.
- Footer and contact use `<x-web.social-links />`.
- Empty URLs hide that icon; empty set hides the Follow heading.

**Admin:** Branding & Media → Social media links.

---

## Cart sales & inspection policy

**Ask:** Cart section with the Arabic policy text; editable in admin.

**Done**
- Dark panel on cart: title + paragraphs (one line = one paragraph).
- Fields: `cart_policy_title_en/ar`, `cart_policy_body_en/ar`.
- Defaults match the provided Arabic copy + English equivalent.
- Sample loaders in branding form.

**Admin:** Branding & Media → Cart policy (sales & inspection).

---

## UI / UX polish (this pass)

Aligned with boutique fashion UX (calm type, large tap targets, clearer journey):

| Change | Why |
|--------|-----|
| Frosted sticky header | Feels like a fashion site; content still readable while scrolling |
| 44px icon buttons | Touch targets |
| Skip to content | Keyboard / a11y |
| Numbered checkout stepper | Progress is obvious |
| Product title is a link; bag visible on touch | Mobile shoppers can tap the card |
| Footer branding logo + tagline | Replaced: tagline + social only, no logo |
| Softer cream `--brand-soft` | Less “admin grey”, more apparel |
| Tighter mobile hero | Less empty black overlay on small screens |
| `:focus-visible` rings | Keyboard users |

Details: [storefront-ui-ux.md](./storefront-ui-ux.md).

---

## Place order (guest + logged-in)

**Bug:** `GuestUser` and `addresses` use `Apptraits::updateOrCreate()`, which hid Eloquent’s static `updateOrCreate()`. Guest checkout crashed with “Non-static method cannot be called statically”. Mail/PDF errors after a successful insert also looked like a failed order.

**Done**
- Checkout creates/updates guest + address with normal Eloquent queries.
- Phone accepts local formats (not digits-only 10–15).
- Confirm redirects immediately; PDF/email are **not** in that request. The receipt page then pings `/checkout/receipt/{id}/notify` (beacon) to send a light HTML email with an invoice link. PDFs are built only when downloaded.
- Checkout form lists validation errors at the top.

---

## Checkout stepper (Cart / Details / Confirm)

Larger 40px numbered dots, labels under each step, soft panel, shown on cart, checkout, and receipt.

---

## Cart SVG + count badge

Navbar uses an inline cart SVG. Count badge sits on the icon. `overflow: hidden` was clipping the badge; cart button is `overflow: visible`.

---

## Homepage tiles (Top / Long Sleeve)

**Ask:** Change the two homepage names and where they go, from admin. Names and redirects were still static.

**Done**
- Branding & Media → **Homepage banners (Top & Long Sleeve)** (`#home-tiles`): English name, Arabic name, and category redirect for each banner.
- Stopped falling back to hardcoded category IDs `1` and `5`. Empty redirect opens **all products**.
- Category dropdown lists every category (inactive ones are marked), not only `is_active = 1`.
- Names are prefilled (Top / Long Sleeve) so they are clearly editable.
- Admin shows a **Live now** name + URL after save.
- Whole homepage tile is a link (not only the button).
- Images remain the left/right homepage banners (`category_image_1/2`).

**Admin:** Branding & Media → Homepage banners. Dashboard “manage home images” jumps to `#home-tiles`.

**Code:** `BrandingService::homeCategoryTiles()`, `SiteSettingsController`, `resources/views/admin/settings/branding.blade.php`, `resources/views/index.blade.php`.

---

## Admin dark mode — homepage banners

**Ask:** Homepage banners block was unreadable in admin dark mode.

**Cause:** Nested cards used Bootstrap `bg-light`, which stays pale while dark-mode text is light.

**Done**
- Cards use `.admin-nested-panel` (theme tokens) instead of `bg-light`.
- Admin dark mode remaps `.bg-light`, form controls inside `#main`, select options, and image previews.
- Cache-bust query on `admin-theme.css`.

**Files:** `public/admin/assets/css/admin-theme.css`, `resources/views/admin/settings/branding.blade.php`, `resources/views/components/admin/header.blade.php`.

---

## Branding form — fields removed from admin

**Ask:** Remove unused branding fields from the admin portal.

**Removed from Branding & Media (no longer editable):**
- Primary color
- Tagline (English / Arabic)
- Logo alt text (English / Arabic)
- Logo (dark background)
- Favicon
- Product placeholder image
- Footer logo
- Default social share image

**Still on the form:** site name, support email/phone, social URLs, **main logo**, homepage banners, homepage images (hero + two category images), invoice PDF copy, cart policy.

Existing stored values and `config/branding.php` defaults still feed the storefront; they just cannot be changed from this screen.

**Files:** `resources/views/admin/settings/branding.blade.php`, `app/Http/Controllers/SiteSettingsController.php`.

---

## Customer analytics (data collection)

**Ask:** Collect more customer behaviour for analytics.

**Done**
- Event stream table `customer_events`: add to cart, checkout start, purchase, search, register.
- Page views now store device (mobile/tablet/desktop) and traffic source (direct / search / social / referral / UTM).
- Bots are skipped for page/product views. Events never block cart or checkout.
- Admin → Analytics → **Customers** tab: new vs returning buyers, guest vs account orders, repeat rate, add-to-cart rate, devices, traffic sources, weekday sales, top customers, search terms, payment methods.
- CSV export on the Customers tab (`type=customers`).

**Collection points:** cart add, checkout page, order complete, product search, new account.

Run `php artisan migrate` for `2026_09_12_000015_create_customer_events_table.php`.

---

## Footer

Logo removed from the footer. Copyright year is **2026**.

---

## Migrations involved

| File | Purpose |
|------|---------|
| `2026_06_04_000011_add_social_urls_to_site_settings.php` | First six social URL columns |
| `2026_06_04_000012_add_more_social_urls_to_site_settings.php` | LinkedIn, Telegram, Pinterest, Snapchat |
| `2026_06_04_000013_add_cart_policy_to_site_settings.php` | Cart policy title/body EN+AR |
| `2026_06_04_000014_add_home_category_tiles_to_site_settings.php` | Two homepage category IDs + titles |
| `2026_09_12_000015_create_customer_events_table.php` | Customer event stream + page-view device/source |

Run `php artisan migrate` if any of these have not been applied.

---

## Docs

| Doc | Role |
|-----|------|
| [storefront-redesign.md](./storefront-redesign.md) | What shipped (pages, components, journey) |
| [storefront-ui-ux.md](./storefront-ui-ux.md) | How it should look and behave |
| This file | What changed recently |
