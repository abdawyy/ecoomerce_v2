# Hayah storefront — UI / UX spec

> Boutique homewear. EN + AR (RTL). Last polish: September 2026.

Companion to [storefront-redesign.md](./storefront-redesign.md). Use this when changing layout, type, or interaction — not as a backlog of unfinished pages.

---

## Principles

1. **Calm commerce** — black/white, one accent (sale red). No noisy banners besides the thin promo bar.
2. **One logo, one voice** — Main logo from Branding & Media on storefront and admin.
3. **Thumb-first** — 44px icon buttons, visible bag overlay on touch, sticky PDP CTA.
4. **Honest copy** — cart policy, checkout stepper, and receipts explain the next step.
5. **Bilingual by default** — RTL flips gradients, drawers, and sale badges.

---

## Tokens

Defined in `storefront-shell.css` `:root`:

| Token | Light | Use |
|-------|--------|-----|
| `--brand-black` | `#111111` | Text, buttons, promo |
| `--brand-white` | `#ffffff` | Page background |
| `--brand-muted` | `#6b7280` | Secondary text |
| `--brand-soft` | `#f7f6f4` | Cards, chips, hover wells |
| `--brand-sale` | `#e24b2d` | Sale badge |
| `--brand-border` | `#eceae6` | Hairlines |
| `--radius-lg` | `22px` | Hero, policy panel, receipt |
| `--radius-md` | `14px` | Product images |
| `--font-display` | El Messiri | Headings |
| `--font-body` | Cairo | UI + body |

Dark mode remaps the same tokens in `storefront-theme.css`.

---

## Header

- Promo bar: 8px, uppercase, site name + tagline.
- Sticky navbar with **frosted** background (`backdrop-filter`).
- **Main logo** (`x-branding.logo`, not nested links).
- Toolbar: theme, search, bag + count, account, EN/AR.
- Icon buttons: 44×44px circle, soft hover fill.
- Skip link: “Skip to content” → `#storefront-main`.
- Mobile: hamburger → one offcanvas (`offcanvas-end` in Arabic).

---

## Product card

- Image `aspect-ratio: 4 / 5`, 14px radius, slow zoom on hover.
- Sale pill top-start (RTL: top-end).
- Bag overlay: hover on desktop; **always on** for `hover: none`.
- Title is a link to PDP.
- Reused on home carousel, PLP, related products, empty cart.

---

## Home

- Hero: cover image from branding, gradient that **flips in RTL**.
- Mobile: shorter hero, full-width copy, smaller category tiles.
- CTA: light pill button → PLP.

---

## Cart & checkout

- Numbered stepper (1 Cart → 2 Details → 3 Confirm). Current step is a filled black dot.
- Cart summary sticky on desktop.
- **Sales & inspection policy**: dark rounded panel; title + paragraphs from admin (EN/AR). Empty admin fields fall back to lang defaults.
- Checkout: saved address picker, city fees, promo, guest create-account checkbox.

---

## Footer

- Columns: Shop, Help, Legal.
- Brand column: inverted **main logo**, tagline, social icons (only if URLs exist), copyright.
- Social icons: Bootstrap Icons from `config/branding.php` platforms — never hardcoded Hayah URLs.

---

## Accessibility

| Rule | Implementation |
|------|----------------|
| Skip link | `.skip-to-content` |
| Focus | `:focus-visible` 2px black ring |
| Icon labels | `aria-label` on search, bag, account, theme |
| Language | `<html lang>` + `dir` |
| Contrast | White on `#111` promo/policy; muted grey for secondary |

---

## Do / don’t

**Do**
- Change the logo only in Branding & Media (Main logo).
- Add social platforms in `config/branding.php` + a URL column if needed.
- Keep product cards on a 4:5 ratio.

**Don’t**
- Hardcode `assets/img/logo.png` or Flaticon social images.
- Add a second mobile menu.
- Wrap `x-branding.logo` in another `<a>` without `:link="false"`.

---

## Files to touch for visual work

| Change | Files |
|--------|--------|
| Color / radius / header | `public/assets/css/storefront-shell.css` |
| Dark mode | `public/assets/css/storefront-theme.css` |
| PLP filters | `public/assets/css/plp.css` |
| Account pages | `public/assets/css/account.css` |
| Nav / footer / cards | `resources/views/components/web/*` |
