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

**Ask:** Change the two homepage names and where they go, from admin.

**Done**
- Branding & Media → **Homepage banners (Top & Long Sleeve)** — English/Arabic name + category redirect for each banner.
- No hardcoded category IDs. Empty redirect goes to all products.
- Names are prefilled (Top / Long Sleeve) so they are clearly editable.
- Images remain the left/right homepage banners.

**Admin:** Branding & Media (`#home-tiles`).

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

Run `php artisan migrate` if any of these have not been applied.

---

## Docs

| Doc | Role |
|-----|------|
| [storefront-redesign.md](./storefront-redesign.md) | What shipped (pages, components, journey) |
| [storefront-ui-ux.md](./storefront-ui-ux.md) | How it should look and behave |
| This file | What changed recently |
