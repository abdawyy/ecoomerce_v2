# Hayah — User Panel Redesign Ideas

> **Status: ✅ Implemented** (June 2026)  
> Scope: Customer account area + admin customer management  
> Checklist: [user-panel-redesign-checklist.md](./user-panel-redesign-checklist.md)

---

## Implementation summary

| Area | Status |
|------|--------|
| **Part A — Customer My Account** | ✅ Complete |
| **Part B — Admin CRM** | ✅ Complete |
| **Storefront integration** | ✅ Complete (navbar, receipt, checkout) |

---

## Overview

Hayah has two “user panel” contexts — both are now built:

| Context | Routes / views | Current state |
|---------|----------------|---------------|
| **Customer account** | `/account/*` | ✅ Storefront-styled hub (orders, addresses, profile, reviews) |
| **Admin user management** | `admin/user/*`, `admin/guest/*` | ✅ CRM-style lists + shared customer profile |

---

## Part A — Customer User Panel (“My Account”) — ✅ Done

### Goal

Give logged-in shoppers one place to manage orders, profile, and addresses — styled like the Hayah storefront (black/white, Cairo/El Messiri, bilingual EN/AR).

### Layout — ✅ Implemented

```
[Store navbar with account avatar ▼]
├── Dashboard          /account
├── My orders          /account/orders
├── Order detail       /account/orders/{id}
├── Addresses          /account/addresses
├── Profile & password /account/profile
├── My reviews         /account/reviews
└── Log out
```

**Desktop:** Sidebar (~240px) + content area (`components/account/layout.blade.php`)  
**Mobile:** Account links in navbar offcanvas + responsive account pages

### Pages — ✅ All built

| Page | Route | Status |
|------|-------|--------|
| **Dashboard** | `/account` | ✅ |
| **Orders list** | `/account/orders` | ✅ |
| **Order detail** | `/account/orders/{id}` | ✅ |
| **Addresses** | `/account/addresses` | ✅ |
| **Profile** | `/account/profile` | ✅ |
| **Reviews** | `/account/reviews` | ✅ |

### UX — ✅ Implemented

| Idea | Status |
|------|--------|
| Order status stepper | ✅ `x-account.order-stepper` |
| Empty states + shop CTA | ✅ Orders list + dashboard |
| Guest → account merge | ✅ `GuestAccountMergeService` on login |
| Receipt “View in my orders” | ✅ Auth users on receipt page |
| Invoice download (own orders) | ✅ `account.orders.invoice` |
| Bilingual + RTL | ✅ |
| Phone + default address | ✅ Migration + CRUD |
| Post-login redirect to `/account` | ✅ |

### Visual style — ✅ Implemented

- White cards, soft shadow, `#111` accents — `account.css`
- Status pills aligned with admin orders
- Cairo + El Messiri typography
- Dark mode via storefront theme toggle (inherits `storefront-theme.css`)

### Navbar integration — ✅ Done

- **Guest:** Login icon (desktop) + login/register in mobile menu
- **Logged in:** Dropdown → Dashboard, Orders, Profile, Logout

### Rollout — ✅ All phases complete

| Phase | Deliverable | Status |
|-------|-------------|--------|
| **1** | Navbar account entry + `/account/orders` | ✅ |
| **2** | Order detail + PDF invoice | ✅ |
| **3** | Addresses CRUD + profile | ✅ |
| **4** | Dashboard + reviews + guest merge | ✅ |

---

## Part B — Admin User Panel — ✅ Done

### Goal

Upgrade admin tools for registered users and guests to match the redesigned admin panel.

### User list — ✅ Implemented

- ✅ Unified filter bar (search, status, has orders, new users)
- ✅ Columns: ID, Name, Email, Orders, Last order, Joined, Status, Actions
- ✅ New-user badges
- ✅ View + toggle active actions
- ⏸️ Date range filter — deferred (optional)

### User / guest detail — ✅ CRM profile

- ✅ Shared `x-admin.customer-profile` for `user.show` + `admin.guest.show`
- ✅ KPI strip, orders table, address cards, default label
- ✅ Registered vs Guest badge
- ✅ Quick actions: toggle active, email customer, open latest order

### Admin IA — ✅ Unchanged structure, enhanced views

```
Operations
  ├── Orders
  ├── Messages
  └── Customers
        ├── User list
        └── Guest list
```

---

## Part C — Shared design principles — ✅ Applied

| Principle | Customer account | Admin users |
|-----------|------------------|-------------|
| Status colors | ✅ Same pills | ✅ Same as orders admin |
| Language | ✅ EN/AR + RTL | ✅ |
| Components | ✅ account-card, order-stepper, status-pill | ✅ admin-card, customer-profile, view-link |
| Icons | Font Awesome + Bootstrap Icons (storefront) | Bootstrap Icons (admin) |

---

## Part D — Technical reference

| Item | Location |
|------|----------|
| Account routes | `routes/web.php` → `account.*` |
| Account controller | `app/HttpControllers/AccountController.php` |
| Account layout | `resources/views/components/account/layout.blade.php` |
| Guest merge | `app/Services/GuestAccountMergeService.php` |
| Admin CRM service | `app/Services/AdminCustomerProfileService.php` |
| Customer profile UI | `resources/views/components/admin/customer-profile.blade.php` |
| Invoice PDF | `app/Services/PdfService.php` |
| Jetstream profile redirect | `/user/profile` → `/account/profile` |

---

## Summary

1. ✅ Storefront **My Account** hub — built and linked from navbar.
2. ✅ **Admin user/guest** CRM-style profiles — built.
3. ✅ Order status visuals and bilingual patterns unified across shop and admin.

For storefront shell details, see [storefront-redesign.md](./storefront-redesign.md).  
Visual rules: [storefront-ui-ux.md](./storefront-ui-ux.md). Recent shop work: [storefront-changelog.md](./storefront-changelog.md).
