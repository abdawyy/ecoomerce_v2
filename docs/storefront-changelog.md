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
| Footer branding logo + tagline | Identity at the bottom of every page |
| Softer cream `--brand-soft` | Less “admin grey”, more apparel |
| Tighter mobile hero | Less empty black overlay on small screens |
| `:focus-visible` rings | Keyboard users |

Details: [storefront-ui-ux.md](./storefront-ui-ux.md).

---

## Migrations involved

| File | Purpose |
|------|---------|
| `2026_06_04_000011_add_social_urls_to_site_settings.php` | First six social URL columns |
| `2026_06_04_000012_add_more_social_urls_to_site_settings.php` | LinkedIn, Telegram, Pinterest, Snapchat |
| `2026_06_04_000013_add_cart_policy_to_site_settings.php` | Cart policy title/body EN+AR |

Run `php artisan migrate` if any of these have not been applied.

---

## Docs

| Doc | Role |
|-----|------|
| [storefront-redesign.md](./storefront-redesign.md) | What shipped (pages, components, journey) |
| [storefront-ui-ux.md](./storefront-ui-ux.md) | How it should look and behave |
| This file | What changed recently |
