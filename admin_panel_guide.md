# 🎛️ Black Sky Asia — Admin Panel Guide
## Halaman, User Flow & Library Stack (Role: Admin)

**Versi:** 5.1  
**Date:** 2026-05-13  
**Role:** Admin Only  
**Platform:** Filament v3 + Laravel 11

---

## 📑 Daftar Halaman Admin

| # | Halaman | Tipe | Deskripsi |
|---|---------|------|-----------|
| 1 | **Login** | Auth | Masuk ke panel admin |
| 2 | **Dashboard** | Custom Page | Ringkasan statistik & chart |
| 3 | **Events** | Resource | CRUD event + SEO + vendor links |
| 4 | **Artists** | Resource | CRUD artis + SEO |
| 5 | **Blog Posts** | Resource | CRUD artikel blog + SEO |
| 6 | **Blog Categories** | Resource | Kategori blog |
| 7 | **Blog Tags** | Resource | Tag blog |
| 8 | **Authors** | Resource | Profil penulis blog |
| 9 | **Vendors** | Resource | Konfigurasi API vendor tiket |
| 10 | **Synced Transactions** | Resource (Read-Only) | Monitoring penjualan tiket |
| 11 | **Users** | Resource (Admin-only) | Manajemen user publik |
| 12 | **Banners** | Resource | Slider hero landing page |
| 13 | **News** | Resource | Pengumuman/press release |
| 14 | **Send Notification** | Custom Page | Push notifikasi ke user |
| 15 | **Sales Reports** | Custom Page | Laporan & export Excel/CSV |
| 16 | **Sync Status** | Custom Page | Status sinkronisasi vendor |

---

## 🔐 1. Login Page

```
┌─────────────────────────────────────────────────────────────┐
│                                                             │
│                    ⚫ BLACK SKY ASIA                        │
│                                                             │
│              Event Promoter & Management                    │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐   │
│  │  Email Address                                      │   │
│  │  ┌───────────────────────────────────────────────┐  │   │
│  │  │ admin@blackskyasia.com                        │  │   │
│  │  └───────────────────────────────────────────────┘  │   │
│  │                                                     │   │
│  │  Password                                           │   │
│  │  ┌───────────────────────────────────────────────┐  │   │
│  │  │ ●●●●●●●●●●●●●●●●                              │  │   │
│  │  └───────────────────────────────────────────────┘  │   │
│  │                                                     │   │
│  │  [x] Remember me                                    │   │
│  │                                                     │   │
│  │  ┌───────────────────────────────────────────────┐  │   │
│  │  │              SIGN IN                          │  │   │
│  │  └───────────────────────────────────────────────┘  │   │
│  │                                                     │   │
│  │  Forgot your password?                              │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│              © 2026 Black Sky Asia Enterprise               │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

**Aksi:** Admin masukkan email + password → redirect ke Dashboard.

---

## 📊 2. Dashboard (Home Admin)

```
┌──────────────────────────────────────────────────────────────────────────────┐
│ ⚫ BLACK SKY ASIA                                      [🔔] [👤 Admin ▼]   │
├──────────────────────────────────────────────────────────────────────────────┤
│  Dashboard    Events    Artists    Blog    Vendors    Users    Reports      │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    │
│  │   12         │  │   8          │  │   1,245      │  │   3          │    │
│  │  Events      │  │ Published    │  │  Users       │  │  Vendors     │    │
│  │  Total       │  │  Events      │  │  Total       │  │  Active      │    │
│  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘    │
│                                                                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    │
│  │  RM 45,200   │  │   892        │  │   156        │  │   98%        │    │
│  │  Total Sales │  │  Tickets     │  │  Matched     │  │  Sync        │    │
│  │  (Synced)    │  │  Sold        │  │  Users       │  │  Success     │    │
│  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘    │
│                                                                              │
│  ┌────────────────────────────┐  ┌──────────────────────────────────────┐  │
│  │  📈 Sales Trend (7 Days)   │  │  🥧 Sales by Vendor                  │  │
│  │                            │  │                                      │  │
│  │    ▲                       │  │        ┌──────────┐                  │  │
│  │  RM│      ╱╲               │  │       /   Tixr   /  65%             │  │
│  │    │    ╱    ╲    ╱╲       │  │      /──────────/                   │  │
│  │    │  ╱        ╲╱    ╲     │  │     / MalamGalau/  25%              │  │
│  │    │╱                    ╲  │  │    /─────────────/                  │  │
│  │    └────┬────┬────┬────┬──┤  │   /    Others    /  10%             │  │
│  │         M    T    W    T  F  │  │  └──────────────┘                  │  │
│  └────────────────────────────┘  └──────────────────────────────────────┘  │
│                                                                              │
│  ┌──────────────────────────────────────────────────────────────────────┐  │
│  │  📝 Recent Synced Transactions                                       │  │
│  │  ┌──────────┬──────────────┬──────────┬────────┬────────┬──────────┐ │  │
│  │  │ Event    │ Buyer        │ Vendor   │ Type   │ Amount │ Status   │ │  │
│  │  ├──────────┼──────────────┼──────────┼────────┼────────┼──────────┤ │  │
│  │  │ Pop Up.. │ budi@mail..  │ Tixr     │ VIP    │ RM400  │ ✅ Success│ │  │
│  │  │ Malam..  │ siti@mail..  │ MalamG.. │ Reg    │ RM150  │ ✅ Success│ │  │
│  │  │ Pop Up.. │ ahmad@ma..   │ Tixr     │ Early  │ RM200  │ ⏳ Pending│ │  │
│  │  └──────────┴──────────────┴──────────┴────────┴────────┴──────────┘ │  │
│  └──────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
│  ┌──────────────────────────────────────────────────────────────────────┐  │
│  │  📅 Upcoming Events                                                  │  │
│  │  ┌──────────┬──────────────┬──────────────┬──────────────┬─────────┐ │  │
│  │  │ Event    │ Venue        │ Date         │ Vendor       │ Status  │ │  │
│  │  ├──────────┼──────────────┼──────────────┼──────────────┼─────────┤ │  │
│  │  │ Pop Up.. │ Zepp KL      │ 15 Oct 2025  │ Tixr         │ ✅ Pub  │ │  │
│  │  │ Malam..  │ Mega Star    │ 22 Nov 2025  │ MalamGalau   │ ✅ Pub  │ │  │
│  │  └──────────┴──────────────┴──────────────┴──────────────┴─────────┘ │  │
│  └──────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
```

**Aksi:** Admin melihat ringkasan bisnis, sales trend, dan aktivitas terbaru.

---

## 🎫 3. Events — List View

```
┌──────────────────────────────────────────────────────────────────────────────┐
│ ⚫ BLACK SKY ASIA                                      [🔔] [👤 Admin ▼]   │
├──────────────────────────────────────────────────────────────────────────────┤
│  Dashboard    Events ►   Artists    Blog    Vendors    Users    Reports     │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Events                                                    [+ New Event]     │
│  Manage all events and concerts                                              │
│                                                                              │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │ [🔍 Search by title...]  [Filter ▼]  [📅 Date Range]  [Export ▼]   │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  ┌────┬──────────────┬──────────────┬──────────┬──────────┬────────────┐    │
│  │ □  │ Thumbnail    │ Title        │ Venue    │ Date     │ Status     │    │
│  ├────┼──────────────┼──────────────┼──────────┼──────────┼────────────┤    │
│  │ □  │ [img]        │ Pop Up Muzik │ Zepp KL  │ 15 Oct   │ 🟢 Publi.. │    │
│  │    │              │ — Armada &.. │          │ 2025     │            │    │
│  ├────┼──────────────┼──────────────┼──────────┼──────────┼────────────┤    │
│  │ □  │ [img]        │ Malam Galau  │ Mega St..│ 22 Nov   │ 🟢 Publi.. │    │
│  │    │              │ Kuala Lumpur │          │ 2025     │            │    │
│  ├────┼──────────────┼──────────────┼──────────┼──────────┼────────────┤    │
│  │ □  │ [img]        │ Jazz Night   │ PJPAC    │ 01 Dec   │ 🟡 Sched.. │    │
│  │    │              │ Festival     │          │ 2025     │            │    │
│  ├────┼──────────────┼──────────────┼──────────┼──────────┼────────────┤    │
│  │ □  │ [img]        │ Indie Music  │ The Bee  │ 10 Jan   │ ⚪ Draft   │    │
│  │    │              │ Showcase     │          │ 2026     │            │    │
│  └────┴──────────────┴──────────────┴──────────┴──────────┴────────────┘    │
│                                                                              │
│  Showing 1-4 of 12 events                    [< Prev]  1  2  3  [Next >]   │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
```

**Bulk Actions:** Publish, Archive, Delete (selected rows)

---

## 🎫 3b. Events — Create/Edit Form

```
┌──────────────────────────────────────────────────────────────────────────────┐
│ ⚫ BLACK SKY ASIA                                      [🔔] [👤 Admin ▼]   │
├──────────────────────────────────────────────────────────────────────────────┤
│  Dashboard    Events ►   Artists    Blog    Vendors    Users    Reports     │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Edit Event: Pop Up Muzik                                    [Save] [Cancel]│
│                                                                              │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │  [📋 Basic Info] [🎫 Ticketing] [🖼️ Media] [📱 Social] [🔍 SEO]     │   │
│  ├──────────────────────────────────────────────────────────────────────┤   │
│  │                                                                      │   │
│  │  Title *                                                             │   │
│  │  ┌───────────────────────────────────────────────────────────────┐  │   │
│  │  │ Pop Up Muzik — Armada & Batas Senja Live at Zepp KL          │  │   │
│  │  └───────────────────────────────────────────────────────────────┘  │   │
│  │                                                                      │   │
│  │  Slug *                                                              │   │
│  │  ┌───────────────────────────────────────────────────────────────┐  │   │
│  │  │ pop-up-muzik-armada-batas-senja-zepp-kl-2025                 │  │   │
│  │  └───────────────────────────────────────────────────────────────┘  │   │
│  │                                                                      │   │
│  │  Subtitle                                                            │   │
│  │  ┌───────────────────────────────────────────────────────────────┐  │   │
│  │  │ Konser terbesar Oktober 2025 di Kuala Lumpur                 │  │   │
│  │  └───────────────────────────────────────────────────────────────┘  │   │
│  │                                                                      │   │
│  │  Description                                                         │   │
│  │  ┌───────────────────────────────────────────────────────────────┐  │   │
│  │  │ [Rich Text Editor — Bold, Italic, Link, Image, Heading]      │  │   │
│  │  │                                                               │  │   │
│  │  │ Saksikan penampilan spesial Armada dan Batas Senja...        │  │   │
│  │  └───────────────────────────────────────────────────────────────┘  │   │
│  │                                                                      │   │
│  │  Venue *              City *          Country *                      │   │
│  │  ┌───────────────┐    ┌──────────┐    ┌───────────────┐             │   │
│  │  │ Zepp KL       │    │ Kuala L..│    │ Malaysia ▼    │             │   │
│  │  └───────────────┘    └──────────┘    └───────────────┘             │   │
│  │                                                                      │   │
│  │  Start Date *         End Date          Timezone                     │   │
│  │  ┌───────────────┐    ┌──────────┐    ┌───────────────┐             │   │
│  │  │ 📅 15/10/2025 │    │ 📅 --    │    │ Asia/Kuala.. ▼│             │   │
│  │  └───────────────┘    └──────────┘    └───────────────┘             │   │
│  │                                                                      │   │
│  │  Status *                                                            │   │
│  │  ┌───────────────┐                                                   │   │
│  │  │ Published ▼   │                                                   │   │
│  │  └───────────────┘                                                   │   │
│  │                                                                      │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  [📋 Basic Info] [🎫 Ticketing] [🖼️ Media] [📱 Social] [🔍 SEO]             │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
```

**Tab Ticketing:**
```
│  Primary Vendor *       External Ticket URL *                              │
│  ┌───────────────┐      ┌──────────────────────────────────────────────┐   │
│  │ Tixr ▼        │      │ https://hpelive-my.tixr.com/pop-up-muzik    │   │
│  └───────────────┘      └──────────────────────────────────────────────┘   │
│                                                                              │
│  Ticket Info                                                               │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │ Early Bird: RM150 | Regular: RM200 | VIP: RM400                     │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  Additional Vendor Links                                                   │
│  ┌───────────────┬────────────────────────────────────────┬──────────┐     │
│  │ Vendor        │ Ticket URL                             │ Primary  │     │
│  ├───────────────┼────────────────────────────────────────┼──────────┤     │
│  │ Tixr          │ https://tixr.com/...                   │ [x]      │     │
│  │ MalamGalau    │ https://malamgalau.com/...             │ [ ]      │     │
│  └───────────────┴────────────────────────────────────────┴──────────┘     │
```

**Tab SEO:**
```
│  Meta Title (60 chars max)                                                 │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │ Pop Up Muzik 2025 — Armada & Batas Senja Live at Zepp KL | BSA     │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  Meta Description (160 chars max)                                          │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │ Saksikan konser Armada dan Batas Senja di Zepp KL, 15 Oktober 2025. │   │
│  │ Beli tiket sekarang!                                                │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  Meta Keywords                                                             │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │ armada, batas senja, konser kl, zepp kl, tiket konser, 2025         │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  Canonical URL                                                             │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │ https://blackskyasia.com/events/pop-up-muzik-armada-batas-senja..   │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  OG Image (1200x630)                                                       │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │ [📷 Upload / Preview Image]                                         │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
```

---

## 🎤 4. Artists — List & Form

```
┌──────────────────────────────────────────────────────────────────────────────┐
│ ⚫ BLACK SKY ASIA                                      [🔔] [👤 Admin ▼]   │
├──────────────────────────────────────────────────────────────────────────────┤
│  Dashboard    Events    Artists ►   Blog    Vendors    Users    Reports     │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Artists                                                   [+ New Artist]    │
│                                                                              │
│  ┌────┬──────────────┬──────────────┬──────────┬──────────┬────────────┐    │
│  │ □  │ Photo        │ Name         │ Genre    │ Origin   │ Status     │    │
│  ├────┼──────────────┼──────────────┼──────────┼──────────┼────────────┤    │
│  │ □  │ [img]        │ Armada       │ Pop Rock │ Indonesia│ 🟢 Active  │    │
│  │ □  │ [img]        │ Batas Senja  │ Indie    │ Indonesia│ 🟢 Active  │    │
│  │ □  │ [img]        │ Juicy Luicy  │ Pop      │ Indonesia│ 🟢 Active  │    │
│  └────┴──────────────┴──────────────┴──────────┴──────────┴────────────┘    │
│                                                                              │
│  Form Fields:                                                                │
│  • Name * | Slug * | Bio (Rich Editor) | Genre | Origin Country            │
│  • Photo (Upload) | Social Media Links (Instagram, Twitter, TikTok, YT)    │
│  • Website URL | Meta Title | Meta Description | Meta Keywords              │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
```

---

## 📝 5. Blog Posts — List & Form

```
┌──────────────────────────────────────────────────────────────────────────────┐
│ ⚫ BLACK SKY ASIA                                      [🔔] [👤 Admin ▼]   │
├──────────────────────────────────────────────────────────────────────────────┤
│  Dashboard    Events    Artists    Blog ►   Vendors    Users    Reports     │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Blog Posts                                              [+ New Blog Post]   │
│                                                                              │
│  ┌────┬──────────────┬──────────────┬──────────┬──────────┬────────────┐    │
│  │ □  │ Thumbnail    │ Title        │ Category │ Author   │ Status     │    │
│  ├────┼──────────────┼──────────────┼──────────┼──────────┼────────────┤    │
│  │ □  │ [img]        │ 5 Alasan Har..│ Konser   │ Budi S.  │ 🟢 Publi.. │    │
│  │ □  │ [img]        │ Behind The S..│ Behind   │ Ani R.   │ 🟢 Publi.. │    │
│  │ □  │ [img]        │ Tips Beli Ti..│ Tips     │ Budi S.  │ 🟡 Sched.. │    │
│  └────┴──────────────┴──────────────┴──────────┴──────────┴────────────┘    │
│                                                                              │
│  Form Fields (Tabs):                                                         │
│  [📋 Content] [🔍 SEO] [🏷️ Category & Tags]                                  │
│                                                                              │
│  Content Tab:                                                                │
│  • Title * | Slug * | Excerpt * | Featured Image                            │
│  • Content (Rich Editor — Heading H2/H3, Bold, Link, Image, Embed)          │
│  • Author * (Select from Authors)                                           │
│                                                                              │
│  Category & Tags Tab:                                                        │
│  • Category * (Select: Konser, Berita Artis, Behind The Scenes, Tips)       │
│  • Tags (Multi-select: Armada, Zepp KL, Pop Up Muzik, Tiket Murah)          │
│                                                                              │
│  SEO Tab:                                                                    │
│  • Meta Title | Meta Description | Meta Keywords | Canonical URL | OG Image  │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
```

---

## 🏷️ 6. Blog Categories

```
┌──────────────────────────────────────────────────────────────────────────────┐
│ ⚫ BLACK SKY ASIA                                      [🔔] [👤 Admin ▼]   │
├──────────────────────────────────────────────────────────────────────────────┤
│  Dashboard    Events    Artists    Blog ►   Vendors    Users    Reports     │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Blog Categories                                       [+ New Category]      │
│                                                                              │
│  ┌────┬──────────────┬──────────────┬──────────┬─────────────────────────┐  │
│  │ □  │ Name         │ Slug         │ Posts    │ Description             │  │
│  ├────┼──────────────┼──────────────┼──────────┼─────────────────────────┤  │
│  │ □  │ Konser       │ konser       │ 12       │ Artikel seputar konser  │  │
│  │ □  │ Berita Artis │ berita-artis │ 8        │ Update terbaru artis    │  │
│  │ □  │ Behind The.. │ behind-the.. │ 5        │ Cerita di balik panggung│  │
│  │ □  │ Tips & Trick │ tips-trick   │ 3        │ Panduan event & tiket   │  │
│  └────┴──────────────┴──────────────┴──────────┴─────────────────────────┘  │
│                                                                              │
│  Form: Name * | Slug * | Description | Meta Title | Meta Description        │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
```

---

## 🏷️ 7. Blog Tags

```
┌──────────────────────────────────────────────────────────────────────────────┐
│ ⚫ BLACK SKY ASIA                                      [🔔] [👤 Admin ▼]   │
├──────────────────────────────────────────────────────────────────────────────┤
│  Dashboard    Events    Artists    Blog ►   Vendors    Users    Reports     │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Blog Tags                                               [+ New Tag]         │
│                                                                              │
│  ┌────┬──────────────┬──────────────┬──────────┐                            │
│  │ □  │ Name         │ Slug         │ Posts    │                            │
│  ├────┼──────────────┼──────────────┼──────────┤                            │
│  │ □  │ Armada       │ armada       │ 5        │                            │
│  │ □  │ Zepp KL      │ zepp-kl      │ 3        │                            │
│  │ □  │ Pop Up Muzik │ pop-up-muzik │ 4        │                            │
│  │ □  │ Tiket Murah  │ tiket-murah  │ 2        │                            │
│  └────┴──────────────┴──────────────┴──────────┘                            │
│                                                                              │
│  Form: Name * | Slug * | Description                                        │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
```

---

## ✍️ 8. Authors

```
┌──────────────────────────────────────────────────────────────────────────────┐
│ ⚫ BLACK SKY ASIA                                      [🔔] [👤 Admin ▼]   │
├──────────────────────────────────────────────────────────────────────────────┤
│  Dashboard    Events    Artists    Blog ►   Vendors    Users    Reports     │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Authors                                                 [+ New Author]      │
│                                                                              │
│  ┌────┬──────────────┬──────────────┬──────────┬──────────┐                 │
│  │ □  │ Photo        │ Name         │ Email    │ Posts    │                 │
│  ├────┼──────────────┼──────────────┼──────────┼──────────┤                 │
│  │ □  │ [img]        │ Budi Santoso │ budi@..  │ 8        │                 │
│  │ □  │ [img]        │ Ani Rahayu   │ ani@..   │ 5        │                 │
│  └────┴──────────────┴──────────────┴──────────┴──────────┘                 │
│                                                                              │
│  Form: Name * | Slug * | Bio | Photo | Email | Social Media | Link to User  │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
```

---

## 🏢 9. Vendors

```
┌──────────────────────────────────────────────────────────────────────────────┐
│ ⚫ BLACK SKY ASIA                                      [🔔] [👤 Admin ▼]   │
├──────────────────────────────────────────────────────────────────────────────┤
│  Dashboard    Events    Artists    Blog    Vendors ►   Users    Reports     │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Vendors                                                 [+ New Vendor]      │
│                                                                              │
│  ┌────┬──────────────┬──────────────┬──────────┬──────────┬────────────┐    │
│  │ □  │ Name         │ Website      │ API URL  │ Status   │ Actions    │    │
│  ├────┼──────────────┼──────────────┼──────────┼──────────┼────────────┤    │
│  │ □  │ Tixr         │ tixr.com     │ api.t..  │ 🟢 Active│ [Test] [🗑️]│    │
│  │ □  │ MalamGalau   │ malamgalau.. │ api.m..  │ 🟢 Active│ [Test] [🗑️]│    │
│  └────┴──────────────┴──────────────┴──────────┴──────────┴────────────┘    │
│                                                                              │
│  Form:                                                                       │
│  • Name * | Slug * | Website URL | API Base URL *                            │
│  • API Key (Encrypted) | API Secret (Encrypted)                              │
│  • Config (JSON: additional settings per vendor)                             │
│  • Is Active (Toggle)                                                        │
│                                                                              │
│  Action: [🧪 Test Connection] — Ping vendor API dengan credentials           │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
```

---

## 💰 10. Synced Transactions (Read-Only Monitoring)

```
┌──────────────────────────────────────────────────────────────────────────────┐
│ ⚫ BLACK SKY ASIA                                      [🔔] [👤 Admin ▼]   │
├──────────────────────────────────────────────────────────────────────────────┤
│  Dashboard    Events    Artists    Blog    Vendors    Users    Reports ►    │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Synced Transactions                                     [📥 Export Excel]   │
│  Monitoring penjualan tiket dari vendor                                      │
│                                                                              │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │ [🔍 Search] [Event ▼] [Vendor ▼] [Status ▼] [📅 Date Range]        │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  ┌──────────┬──────────────┬──────────────┬────────┬────────┬──────────┐    │
│  │ Event    │ Buyer        │ Vendor   │ Type   │ Amount │ Status   │    │
│  ├──────────┼──────────────┼──────────┼────────┼────────┼──────────┤    │
│  │ Pop Up.. │ budi@mail..  │ Tixr     │ VIP    │ RM400  │ ✅ Succ..│    │
│  │ Pop Up.. │ siti@mail..  │ Tixr     │ Reg    │ RM200  │ ✅ Succ..│    │
│  │ Malam..  │ ahmad@ma..   │ MalamG.. │ VIP    │ RM350  │ ✅ Succ..│    │
│  │ Pop Up.. │ (unmatched)  │ Tixr     │ Early  │ RM150  │ ✅ Succ..│    │
│  └──────────┴──────────────┴──────────┴────────┴────────┴──────────┘    │
│                                                                              │
│  Detail Panel (click row):                                                   │
│  • Vendor Transaction ID: TIXR-8839201                                       │
│  • Matched User: Budi Santoso (budi@mail.com)                                │
│  • Raw Payload: [View JSON]                                                  │
│  • Last Synced: 13 May 2025, 14:30                                           │
│                                                                              │
│  Action: [🔗 Link to User] — Manual match jika auto-match gagal              │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
```

---

## 👥 11. Users (Admin-Only Management)

```
┌──────────────────────────────────────────────────────────────────────────────┐
│ ⚫ BLACK SKY ASIA                                      [🔔] [👤 Admin ▼]   │
├──────────────────────────────────────────────────────────────────────────────┤
│  Dashboard    Events    Artists    Blog    Vendors    Users ►   Reports     │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Users                                                                     │
│                                                                              │
│  ┌────┬──────────────┬──────────────┬──────────┬──────────┬────────────┐    │
│  │ □  │ Name         │ Email        │ Phone    │ Role     │ Status     │    │
│  ├────┼──────────────┼──────────────┼──────────┼──────────┼────────────┤    │
│  │ □  │ Super Admin  │ admin@bla..  │ 0123..   │ Admin    │ 🟢 Active  │    │
│  │ □  │ Budi Santoso │ budi@mail..  │ 0145..   │ User     │ 🟢 Active  │    │
│  │ □  │ Siti Aminah  │ siti@mail..  │ 0167..   │ User     │ 🔴 Inact.. │    │
│  └────┴──────────────┴──────────────┴──────────┴──────────┴────────────┘    │
│                                                                              │
│  Actions per row: [👁️ View] [✏️ Edit Role] [🚫 Deactivate]                  │
│                                                                              │
│  Note: Admin tidak bisa delete user — hanya deactivate (soft disable).       │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
```

---

## 🖼️ 12. Banners

```
┌──────────────────────────────────────────────────────────────────────────────┐
│ ⚫ BLACK SKY ASIA                                      [🔔] [👤 Admin ▼]   │
├──────────────────────────────────────────────────────────────────────────────┤
│  Dashboard    Events    Artists    Blog    Vendors    Users    Reports      │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Banners                                                 [+ New Banner]      │
│                                                                              │
│  ┌────┬──────────────┬──────────────┬──────────┬──────────┬────────────┐    │
│  │ □  │ Image        │ Title        │ Position │ Status   │ Date Range │    │
│  ├────┼──────────────┼──────────────┼──────────┼──────────┼────────────┤    │
│  │ □  │ [img]        │ Pop Up Muzik │ 1        │ 🟢 Pub   │ 1-31 Oct   │    │
│  │ □  │ [img]        │ Malam Galau  │ 2        │ 🟢 Pub   │ 1-30 Nov   │    │
│  └────┴──────────────┴──────────────┴──────────┴──────────┴────────────┘    │
│                                                                              │
│  Form: Title * | Image (Upload) | Link URL | Position (Number)              │
│        Status (Draft/Published/Scheduled) | Start Date | End Date            │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
```

---

## 📰 13. News

```
┌──────────────────────────────────────────────────────────────────────────────┐
│ ⚫ BLACK SKY ASIA                                      [🔔] [👤 Admin ▼]   │
├──────────────────────────────────────────────────────────────────────────────┤
│  Dashboard    Events    Artists    Blog    Vendors    Users    Reports      │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  News                                                      [+ New News]      │
│                                                                              │
│  ┌────┬──────────────┬──────────────┬──────────┬──────────┐                 │
│  │ □  │ Title        │ Excerpt      │ Status   │ Date     │                 │
│  ├────┼──────────────┼──────────────┼──────────┼──────────┤                 │
│  │ □  │ Pengumuman.. │ Kami dengan..│ 🟢 Pub   │ 12 May   │                 │
│  │ □  │ Lineup Resmi │ Berikut da.. │ 🟢 Pub   │ 10 May   │                 │
│  └────┴──────────────┴──────────────┴──────────┴──────────┘                 │
│                                                                              │
│  Form: Title * | Slug * | Excerpt | Content (Rich Editor) | Featured Image  │
│        Status (Draft/Published/Scheduled) | Published At                     │
│                                                                              │
│  Note: News lebih ringkas dari Blog — untuk press release & pengumuman.      │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
```

---

## 🔔 14. Send Notification (Custom Page)

```
┌──────────────────────────────────────────────────────────────────────────────┐
│ ⚫ BLACK SKY ASIA                                      [🔔] [👤 Admin ▼]   │
├──────────────────────────────────────────────────────────────────────────────┤
│  Dashboard    Events    Artists    Blog    Vendors    Users    Reports ►    │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Send Push Notification                                                      │
│  Kirim notifikasi ke user                                                    │
│                                                                              │
│  Target *                                                                    │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │ ○ All Users (1,245 users)                                           │   │
│  │ ○ Selected Users                                                    │   │
│  │   ┌───────────────────────────────────────────────────────────────┐  │   │
│  │   │ [Search user...]                                              │  │   │
│  │   │ ☑ Budi Santoso  ☑ Siti Aminah  ☐ Ahmad Fauzi                │  │   │
│  │   └───────────────────────────────────────────────────────────────┘  │   │
│  │ ○ By Role                                                           │   │
│  │   ┌───────────────────────────────────────────────────────────────┐  │   │
│  │   │ User ▼                                                        │  │   │
│  │   └───────────────────────────────────────────────────────────────┘  │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  Title *                                                                     │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │ 🎵 Pop Up Muzik — Early Bird Berakhir Besok!                        │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  Body *                                                                      │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │ Jangan lewatkan harga Early Bird untuk konser Armada & Batas Senja  │   │
│  │ di Zepp KL. Beli sekarang sebelum harga naik!                       │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │                    [📤 SEND NOTIFICATION]                            │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  Status: Queued for sending...                                               │
│  Estimated delivery: ~2 minutes (depending on queue)                         │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
```

---

## 📈 15. Sales Reports (Custom Page)

```
┌──────────────────────────────────────────────────────────────────────────────┐
│ ⚫ BLACK SKY ASIA                                      [🔔] [👤 Admin ▼]   │
├──────────────────────────────────────────────────────────────────────────────┤
│  Dashboard    Events    Artists    Blog    Vendors    Users    Reports ►    │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Sales Reports                                                               │
│                                                                              │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │  Event: [All Events ▼]  Vendor: [All Vendors ▼]                     │   │
│  │  From: [📅 01/01/2025]  To: [📅 31/12/2025]                         │   │
│  │                                                                     │   │
│  │  [🔍 Generate Report]  [📥 Export Excel]  [📥 Export CSV]           │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐    │
│  │  RM 45,200   │  │   892        │  │   3          │  │   156        │    │
│  │  Revenue     │  │  Tickets     │  │  Vendors     │  │  Matched     │    │
│  │  Total       │  │  Sold        │  │  Active      │  │  Users       │    │
│  └──────────────┘  └──────────────┘  └──────────────┘  └──────────────┘    │
│                                                                              │
│  ┌────────────────────────────┐  ┌──────────────────────────────────────┐  │
│  │  📈 Revenue Trend          │  │  🥧 Revenue by Vendor                │  │
│  │                            │  │                                      │  │
│  │    ▲                       │  │        ┌──────────┐                  │  │
│  │  RM│      ╱╲               │  │       /   Tixr   /  65%             │  │
│  │    │    ╱    ╲    ╱╲       │  │      /──────────/                   │  │
│  │    │  ╱        ╲╱    ╲     │  │     / MalamGalau/  25%              │  │
│  │    │╱                    ╲  │  │    /─────────────/                  │  │
│  │    └────┬────┬────┬────┬──┤  │   /    Others    /  10%             │  │
│  │         M    T    W    T  F  │  │  └──────────────┘                  │  │
│  └────────────────────────────┘  └──────────────────────────────────────┘  │
│                                                                              │
│  ┌──────────────────────────────────────────────────────────────────────┐  │
│  │  📋 Transaction Details                                              │  │
│  │  ┌──────────┬──────────────┬──────────┬────────┬────────┬──────────┐ │  │
│  │  │ Date     │ Event        │ Vendor   │ Buyer  │ Amount │ Status   │ │  │
│  │  ├──────────┼──────────────┼──────────┼────────┼────────┼──────────┤ │  │
│  │  │ 12 May   │ Pop Up Muzik │ Tixr     │ Budi   │ RM400  │ Success  │ │  │
│  │  │ 11 May   │ Pop Up Muzik │ Tixr     │ Siti   │ RM200  │ Success  │ │  │
│  │  └──────────┴──────────────┴──────────┴────────┴────────┴──────────┘ │  │
│  └──────────────────────────────────────────────────────────────────────┘  │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
```

---

## 🔄 16. Sync Status (Custom Page)

```
┌──────────────────────────────────────────────────────────────────────────────┐
│ ⚫ BLACK SKY ASIA                                      [🔔] [👤 Admin ▼]   │
├──────────────────────────────────────────────────────────────────────────────┤
│  Dashboard    Events    Artists    Blog    Vendors    Users    Reports ►    │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Vendor Sync Status                                                          │
│                                                                              │
│  ┌──────────────────────────────────────────────────────────────────────┐   │
│  │  [🔄 Sync All Now]  [⏰ Last Scheduled: 13 May 2025, 14:15]          │   │
│  └──────────────────────────────────────────────────────────────────────┘   │
│                                                                              │
│  ┌──────────────┬──────────────┬──────────────┬──────────┬──────────┐       │
│  │ Vendor       │ Last Sync    │ Status       │ Records  │ Action   │       │
│  ├──────────────┼──────────────┼──────────────┼──────────┼──────────┤       │
│  │ Tixr         │ 14:15:30     │ ✅ Success   │ 45 new   │ [Sync]   │       │
│  │ MalamGalau   │ 14:15:45     │ ✅ Success   │ 12 new   │ [Sync]   │       │
│  └──────────────┴──────────────┴──────────────┴──────────┴──────────┘       │
│                                                                              │
│  Sync Logs:                                                                  │
│  ┌──────────┬──────────────┬──────────┬──────────┬──────────┬──────────┐   │
│  │ Time     │ Vendor       │ Type     │ Status   │ Fetched  │ Error    │   │
│  ├──────────┼──────────────┼──────────┼──────────┼──────────┼──────────┤   │
│  │ 14:15    │ Tixr         │ Scheduled│ Success  │ 45       │ —        │   │
│  │ 14:15    │ MalamGalau   │ Scheduled│ Success  │ 12       │ —        │   │
│  │ 13:45    │ Tixr         │ Manual   │ Success  │ 8        │ —        │   │
│  │ 13:30    │ Tixr         │ Scheduled│ Partial  │ 0        │ Timeout  │   │
│  └──────────┴──────────────┴──────────┴──────────┴──────────┴──────────┘   │
│                                                                              │
└──────────────────────────────────────────────────────────────────────────────┘
```

---

## 🧭 Admin User Flow (Step-by-Step)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         ADMIN USER FLOW                                      │
└─────────────────────────────────────────────────────────────────────────────┘

[START]
   │
   ▼
┌─────────────────┐
│  1. LOGIN       │  → Masuk ke admin.blackskyasia.com
│  (Filament Auth)│  → Email + Password
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│  2. DASHBOARD   │  → Lihat ringkasan: total event, sales, users, vendors
│  (Stats & Chart)│  → Cek upcoming events & recent transactions
└────────┬────────┘
         │
    ┌────┴────┬────────────┬────────────┬────────────┬────────────┐
    ▼         ▼            ▼            ▼            ▼            ▼
┌───────┐ ┌────────┐  ┌────────┐  ┌────────┐  ┌────────┐  ┌────────┐
│Events │ │Artists │  │ Blog   │  │Vendors │  │ Users  │  │Reports │
│  CRUD │ │  CRUD  │  │  CRUD  │  │ Config │  │ Manage │  │ Export │
└───┬───┘ └────┬───┘  └────┬───┘  └────┬───┘  └────┬───┘  └────┬───┘
    │          │           │           │           │           │
    ▼          ▼           ▼           ▼           ▼           ▼
┌──────────────────────────────────────────────────────────────────────┐
│  3. CONTENT CREATION (Event / Artist / Blog / News / Banner)        │
│  → Buat event baru: judul, venue, tanggal, vendor links, SEO meta   │
│  → Upload poster, banner, gallery                                   │
│  → Pilih artist lineup                                              │
│  → Set status: Draft → Scheduled → Published                        │
└──────────────────────────────────────────────────────────────────────┘
         │
         ▼
┌──────────────────────────────────────────────────────────────────────┐
│  4. VENDOR CONFIGURATION                                             │
│  → Tambah vendor baru (Tixr, MalamGalau, dll.)                      │
│  → Input API Base URL, API Key, API Secret                          │
│  → Test connection                                                  │
│  → Link vendor ke event (ticket URL)                                │
└──────────────────────────────────────────────────────────────────────┘
         │
         ▼
┌──────────────────────────────────────────────────────────────────────┐
│  5. SYNC DATA (Manual or Scheduled)                                  │
│  → Trigger "Sync Now" per vendor atau "Sync All"                    │
│  → Sistem PULL data transaksi dari vendor API                       │
│  → Auto-match buyer_email dengan users.email                        │
│  → Lihat hasil di Sync Status & Synced Transactions                 │
└──────────────────────────────────────────────────────────────────────┘
         │
         ▼
┌──────────────────────────────────────────────────────────────────────┐
│  6. MONITORING & REPORTING                                           │
│  → Lihat Sales Reports: revenue, tickets sold, vendor comparison    │
│  → Filter by event, vendor, date range                              │
│  → Export Excel/CSV                                                 │
└──────────────────────────────────────────────────────────────────────┘
         │
         ▼
┌──────────────────────────────────────────────────────────────────────┐
│  7. USER ENGAGEMENT                                                  │
│  → Lihat daftar user publik (deactivate jika perlu)                 │
│  → Kirim push notifikasi (event baru, reminder, promo)              │
│  → Notifikasi muncul real-time di user dashboard                    │
└──────────────────────────────────────────────────────────────────────┘
         │
         ▼
┌─────────────────┐
│  8. LOGOUT      │
│  (End Session)  │
└─────────────────┘
```

### Ringkasan Aksi Admin

| # | Aksi | Halaman | Output |
|---|------|---------|--------|
| 1 | Login | Login | Akses panel admin |
| 2 | Lihat dashboard | Dashboard | Statistik & trend |
| 3 | Buat event | Events | Event muncul di landing page |
| 4 | Buat artis | Artists | Profil artis bisa di-link ke event |
| 5 | Tulis blog | Blog Posts | Artikel SEO untuk organic traffic |
| 6 | Atur kategori/tag | Blog Categories / Tags | Struktur blog |
| 7 | Upload banner | Banners | Slider hero di landing page |
| 8 | Konfigurasi vendor | Vendors | Siap sync data penjualan |
| 9 | Trigger sync | Sync Status | Data transaksi masuk |
| 10 | Monitor penjualan | Synced Transactions | Lihat siapa yang beli |
| 11 | Export laporan | Sales Reports | File Excel/CSV |
| 12 | Kirim notifikasi | Send Notification | User terima notif real-time |
| 13 | Kelola user | Users | Aktif/nonaktifkan akun publik |

---

## 📦 Library Stack Khusus Admin (Role: Admin)

Berikut library yang **fokus memenuhi kebutuhan admin**:

### Core Admin Framework
| Library | Fungsi | Status |
|---------|--------|--------|
| **filament/filament:^3.2** | Admin panel scaffold (table, form, resource, page) | ✅ Wajib |
| **filament/notifications:^3.2** | Toast notifikasi dalam panel admin | ✅ Wajib |
| **bezhansalleh/filament-shield:^3.0** | Integrasi RBAC spatie ke Filament (permission matrix) | ✅ Wajib |
| **pxlrbt/filament-excel:^2.0** | Export table ke Excel/CSV langsung dari Filament | ✅ Wajib |
| **filament/spatie-laravel-media-library-plugin:^3.2** | Upload & manage media (poster, banner, foto) | ✅ Wajib |

### Backend Support untuk Admin
| Library | Fungsi untuk Admin | Status |
|---------|-------------------|--------|
| **laravel/fortify** | Auth backend (login admin via session) | ✅ Wajib |
| **laravel/sanctum** | API auth (kalau admin juga pakai API) | ✅ Wajib |
| **spatie/laravel-permission** | RBAC 2 role (admin, user) | ✅ Wajib |
| **maatwebsite/excel** | Export laporan sales (custom logic, bukan table export) | ✅ Wajib |
| **spatie/laravel-responsecache** | Invalidate cache saat admin publish/update konten | ✅ Wajib |
| **laravel/horizon** | Monitor queue job (sync vendor, export, push notif) | ✅ Wajib |
| **laravel/reverb** | Broadcast notifikasi real-time ke user | ✅ Wajib |
| **laravel/pulse** | Monitoring performa production | ✅ Wajib |
| **propaganistas/laravel-phone** | Validasi nomor telepon user (saat admin view/edit user) | ✅ Wajib |
| **darkaonline/l5-swagger** | API docs (untuk referensi integrasi) | ✅ Optional |

### Filament Plugin Tambahan (Opsional tapi Recommended)
| Plugin | Fungsi | Status |
|--------|--------|--------|
| **filament/spatie-laravel-settings-plugin** | Settings page (konfigurasi umum situs) | ➕ Optional |
| **awcodes/filament-versions** | Versioning / activity log per resource | ➕ Optional |
| **pxlrbt/filament-environment-indicator** | Indicator environment (dev/staging/prod) | ➕ Optional |

---

**End of Document — Admin Panel Guide v5.1**
