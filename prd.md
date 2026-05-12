# Product Requirements Document (PRD)
# Black Sky Asia Enterprise — Event Management MVP

**Version:** 1.0  
**Date:** 2026-05-12  
**Prepared For:** Black Sky Asia Enterprise  
**Prepared By:** Development Team  
**Status:** Draft for Development Sprint

---

## 1. Executive Summary

Black Sky Asia Enterprise adalah entitas manajemen hiburan, produksi acara, dan promosi konser di Asia Tenggara (fokus Indonesia & Malaysia). MVP ini bertujuan membangun platform digital yang menjembatani teknologi dengan hiburan, mencakup:

- **Landing Page & Event Discovery** — Informasi acara, countdown, banner dinamis.
- **User Authentication** — Register, login, email verification, reset password.
- **Ticketing Integration** — Pembelian tiket via vendor eksternal (Ticket2U / pihak ketiga) dengan sinkronisasi status pembayaran melalui API.
- **Admin CMS** — Manajemen konten event, banner, user, notifikasi, laporan.
- **User Dashboard** — Riwayat tiket, notifikasi, aktivitas.
- **Notification & Reporting** — Push notifikasi targetted, export CSV/Excel.

**Target Scale:** Up to **50,000 concurrent users** dengan zero-downtime deployment dan admin CRUD yang tidak terganggu oleh traffic tinggi.

---

## 2. Tech Stack & Non-Deprecated Libraries

### 2.1 Backend — Laravel 11.x
| Layer | Technology / Library | Purpose | Status (2026) |
|-------|---------------------|---------|---------------|
| Framework | **Laravel 11.x** | Core framework | ✅ Active LTS |
| Auth (API) | **Laravel Sanctum** (`laravel/sanctum`) | SPA cookie-based auth + API tokens | ✅ First-party, maintained |
| Auth Backend | **Laravel Fortify** (`laravel/fortify`) | Headless auth logic (login, register, 2FA, verification) | ✅ First-party |
| Queue | **Laravel Horizon** (`laravel/horizon`) | Redis queue monitoring & management | ✅ First-party, essential for scale |
| High Perf | **Laravel Octane** (`laravel/octane`) | Swoole/RoadRunner in-memory server | ✅ First-party, throughput multiplier |
| Realtime | **Laravel Reverb** (`laravel/reverb`) | First-party WebSocket server (notifikasi, broadcast) | ✅ First-party, replaces SSE for scale |
| Monitoring | **Laravel Pulse** (`laravel/pulse`) | Production performance monitoring | ✅ First-party |
| Cache / Session | **Redis** (`predis/predis` or ext-phpredis) | Distributed cache, session store, queue backend | ✅ Industry standard |
| RBAC | **spatie/laravel-permission** | Role-based access control (Admin vs User) | ✅ 12K+ stars, actively maintained |
| Excel/CSV | **SpartnerNL/Laravel-Excel** (`maatwebsite/excel`) | Export laporan | ✅ v3.1.69 (Apr 2026), 12K+ stars |
| Response Cache | **spatie/laravel-responsecache** | Cache full HTTP response | ✅ Active |
| Phone Validation | **Propaganistas/Laravel-Phone** | Validasi nomor telepon | ✅ Active |
| API Docs | **DarkaOnLine/L5-Swagger** | OpenAPI 3.0 / Swagger documentation | ✅ Active |
| PDF (optional) | **barryvdh/laravel-dompdf** | Generate tiket/report PDF | ✅ Active |
| IDE Helper | **barryvdh/laravel-ide-helper** | Dev productivity | ✅ Active |
| Routing JS | **tighten/ziggy** | Use Laravel routes in React | ✅ Active |

### 2.2 Frontend — React 18+ (Vite)
| Layer | Technology | Purpose |
|-------|-----------|---------|
| Framework | **React 18+** + **Vite** | SPA (Landing Page + Admin Panel + User Dashboard) |
| HTTP Client | **Axios** | API calls with CSRF/Sanctum integration |
| State / Cache | **TanStack Query (React Query) v5** | Server state, caching, background refetch, cursor pagination | 
| Forms | **React Hook Form** + **Zod** | Performant forms + schema validation |
| Tables | **TanStack Table v8** | Admin datatables (sorting, filtering, pagination) |
| UI Components | **shadcn/ui** atau **Ant Design 5.x** | Pre-built accessible components |
| Date/Time | **date-fns** | Manipulasi tanggal event |
| Realtime | **Laravel Echo** + **Reverb** | Subscribe notifikasi real-time |

### 2.3 Database & Infrastructure
| Layer | Technology | Reasoning |
|-------|-----------|-----------|
| Database | **MySQL 8.0+** atau **MariaDB 10.6+** | User familiar; support read-replicas untuk scale |
| Search | **Meilisearch** via **Laravel Scout** | Full-text search event (self-hosted, lebih cepat & murah dari Algolia) |
| Queue Backend | **Redis** | Horizon-compatible, performant |
| Cache | **Redis** | Distributed, atomic, support tagging |
| Web Server | **Nginx** + **PHP-FPM** (dev) / **Octane** (production) | Octane untuk 50K concurrency |
| Load Balancer | **Nginx** / **HAProxy** / **Cloudflare LB** | Distribute traffic ke multiple Octane workers |
| Object Storage | **AWS S3** / **MinIO** / **Cloudflare R2** | Banner, event thumbnail, asset statis |
| CDN | **Cloudflare** / **AWS CloudFront** | Static asset delivery, DDoS protection |

---

## 3. System Architecture for High Traffic (50K Users)

### 3.1 Deployment Topology
```
[User] 
   → [CDN / Cloudflare] 
   → [Load Balancer (Nginx)] 
   → [App Server 1 .. N] — Laravel Octane (Swoole/RoadRunner)
         ↓
   [Redis Cluster] — Session | Cache | Queue | Broadcast
         ↓
   [MySQL Master] — Write operations (Admin CRUD, Transactions)
         ↓
   [MySQL Slave 1 .. N] — Read operations (Event list, Landing page, Reports)
         ↓
   [Queue Worker 1 .. N] — Horizon managed (Email, Export, Ticket sync)
         ↓
   [Meilisearch] — Search index
```

### 3.2 High-Traffic Strategy
| Problem | Solution |
|---------|----------|
| **50K Concurrent Reads** (landing, event list) | Read replicas MySQL + Redis response cache (`spatie/laravel-responsecache`) + CDN untuk banner/thumbnail |
| **Admin CRUD Bottleneck** | Admin API route ke **database MASTER** dengan queue untuk heavy operation (export, mass notification). Admin panel di-serve dari subdomain terpisah (`admin.*`) dengan connection pool dedicated. |
| **Write Bottleneck** (ticket transaction) | Queue semua write non-critical. Gunakan `QUEUE_CONNECTION=redis` + Horizon. Ticket sync dari Ticket2U di-queue. |
| **Session Store** | Redis (shared across app servers, sticky session tidak wajib) |
| **Real-time Notifikasi** | Reverb WebSocket (horizontal scalable, lebih efisien dari polling/SSE pada 50K users) |
| **Search Performance** | Meilisearch (sub-millisecond search, tidak bebani MySQL) |
| **Zero Downtime Deploy** | Laravel Envoyer / Deployer (symlink switching) + Octane reload graceful |

### 3.3 Laravel Octane Configuration
- **Driver:** `swoole` (lebih mature untuk production) atau `roadrunner`
- **Workers:** `auto` (sesuai CPU core) atau tetap `8–16` per instance
- **Task Workers:** Dedicated untuk background task dalam Octane
- **Max Requests:** 1000–5000 per worker (prevent memory leak)
- **Watch:** Disabled in production

---

## 4. Database Schema Design

### 4.1 Core Tables

```sql
-- users (Fortify + Sanctum compatible)
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    phone VARCHAR(20) NULL,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(500) NULL,
    is_active TINYINT(1) DEFAULT 1,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_phone (phone)
) ENGINE=InnoDB;

-- roles & permissions (spatie/laravel-permission)
-- Package akan generate: roles, permissions, model_has_roles, model_has_permissions, role_has_permissions

-- events
CREATE TABLE events (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description LONGTEXT NULL,
    venue VARCHAR(255) NOT NULL,
    venue_address TEXT NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NULL,
    timezone VARCHAR(50) DEFAULT 'Asia/Jakarta',
    status ENUM('draft','scheduled','published','archived','cancelled') DEFAULT 'draft',
    thumbnail VARCHAR(500) NULL,
    banner_image VARCHAR(500) NULL,
    meta_title VARCHAR(255) NULL,
    meta_description TEXT NULL,
    created_by BIGINT UNSIGNED NOT NULL,
    published_at TIMESTAMP NULL,
    scheduled_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status_published (status, published_at),
    INDEX idx_start_date (start_date),
    INDEX idx_slug (slug),
    FULLTEXT INDEX ft_title_desc (title, description), -- untuk MySQL 8 fulltext
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- event_tickets (master data tiket dari vendor eksternal / internal cache)
CREATE TABLE event_tickets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_id BIGINT UNSIGNED NOT NULL,
    ticket_type VARCHAR(100) NOT NULL, -- e.g., VIP, Regular, Early Bird
    price DECIMAL(15,2) NOT NULL,
    quota INT UNSIGNED NULL,
    sold INT UNSIGNED DEFAULT 0,
    ticket2u_package_id VARCHAR(100) NULL, -- mapping ke vendor
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_event_active (event_id, is_active),
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- banners (CMS slider)
CREATE TABLE banners (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    image VARCHAR(500) NOT NULL,
    link_url VARCHAR(500) NULL,
    position TINYINT UNSIGNED DEFAULT 0,
    status ENUM('draft','published','scheduled','archived') DEFAULT 'draft',
    scheduled_at TIMESTAMP NULL,
    published_at TIMESTAMP NULL,
    start_date DATE NULL,
    end_date DATE NULL,
    created_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status_position (status, position),
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- transactions (sinkronisasi dari vendor ticketing)
CREATE TABLE transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    event_id BIGINT UNSIGNED NOT NULL,
    ticket_id BIGINT UNSIGNED NULL,
    vendor ENUM('ticket2u','internal') DEFAULT 'ticket2u',
    vendor_transaction_id VARCHAR(255) NULL, -- ID dari Ticket2U
    vendor_payload JSON NULL, -- raw payload dari API vendor
    amount DECIMAL(15,2) NOT NULL,
    currency CHAR(3) DEFAULT 'MYR',
    status ENUM('pending','processing','success','failed','cancelled','refunded') DEFAULT 'pending',
    payment_method VARCHAR(50) NULL,
    paid_at TIMESTAMP NULL,
    checked_in_at TIMESTAMP NULL,
    qr_code VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_status (user_id, status),
    INDEX idx_vendor_txn (vendor, vendor_transaction_id),
    INDEX idx_event_status (event_id, status),
    INDEX idx_created_at (created_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE RESTRICT,
    FOREIGN KEY (ticket_id) REFERENCES event_tickets(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- notifications (database + broadcast)
CREATE TABLE notifications (
    id CHAR(36) PRIMARY KEY, -- UUID
    type VARCHAR(255) NOT NULL,
    notifiable_type VARCHAR(255) NOT NULL,
    notifiable_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    data JSON NULL,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_notifiable (notifiable_type, notifiable_id, read_at),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB;

-- admin_push_logs (log notifikasi push dari admin ke selected/all users)
CREATE TABLE admin_push_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_id BIGINT UNSIGNED NOT NULL,
    target_type ENUM('all','selected','role') NOT NULL,
    target_ids JSON NULL, -- array user_id jika selected
    title VARCHAR(255) NOT NULL,
    body TEXT NOT NULL,
    sent_count INT UNSIGNED DEFAULT 0,
    failed_count INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- news / articles (optional CMS)
CREATE TABLE news (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content LONGTEXT NULL,
    excerpt TEXT NULL,
    featured_image VARCHAR(500) NULL,
    status ENUM('draft','published','scheduled','archived') DEFAULT 'draft',
    published_at TIMESTAMP NULL,
    created_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_status_published (status, published_at),
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- activity_logs (audit trail admin)
CREATE TABLE activity_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    log_name VARCHAR(100) NULL,
    description TEXT NOT NULL,
    subject_type VARCHAR(255) NULL,
    subject_id BIGINT UNSIGNED NULL,
    causer_type VARCHAR(255) NULL,
    causer_id BIGINT UNSIGNED NULL,
    properties JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_subject (subject_type, subject_id),
    INDEX idx_causer (causer_type, causer_id),
    INDEX idx_log_name (log_name)
) ENGINE=InnoDB;
```

### 4.2 Read Replica Strategy
- **Master:** Semua write (Admin CRUD, user register, transaction insert/update).
- **Slave 1–3:** Read queries (landing page events, event detail, user ticket list, report generation).
- **Laravel Config:** Gunakan `DB::connection('mysql::read')->...` atau package `matthewbdaly/laravel-read-replicas` jika diperlukan. Laravel 11 native mulai support read/write splitting via config.

---

## 5. API Specification (REST + JSON)

### 5.1 Authentication Flow (Sanctum + Fortify)
```
POST /api/sanctum/csrf-cookie     → Set XSRF-TOKEN cookie
POST /api/register                → Fortify handled
POST /api/login                   → Fortify handled
POST /api/logout                  → Fortify handled
POST /api/forgot-password         → Fortify handled
POST /api/reset-password          → Fortify handled
POST /api/email/verification-notification → Fortify handled
GET  /api/verify-email/{id}/{hash}→ Fortify handled
GET  /api/user                    → auth:sanctum
```
**Security:** Rate limit `authentication` — 5 requests/minute per IP+email.

### 5.2 Landing & Public Event (Cached)
```
GET  /api/v1/banners?status=published&position=hero → List banner (ResponseCache: 300s)
GET  /api/v1/events?status=published&page=1&per_page=12 → List event (ResponseCache: 60s)
GET  /api/v1/events/{slug}      → Event detail (ResponseCache: 120s)
GET  /api/v1/events/{slug}/tickets → Available ticket types
```

### 5.3 User Dashboard (auth:sanctum)
```
GET  /api/v1/me/dashboard       → Widget: upcoming event, active ticket, unread notif count
GET  /api/v1/me/tickets?status=active|past → User ticket list (cursor pagination)
GET  /api/v1/me/tickets/{id}    → Ticket detail + QR
GET  /api/v1/me/notifications   → Notification list (cursor pagination)
PATCH /api/v1/me/notifications/{id}/read → Mark read
```

### 5.4 Ticket Purchase Flow (Ticket2U Integration)
```
POST /api/v1/events/{slug}/buy  → Initiate purchase
  Body: { ticket_id, quantity, callback_url }
  Response: { redirect_url: "https://ticket2u.com/...", vendor_txn_id: "T2U-xxx" }
  → Frontend redirect ke Ticket2U
  → Ticket2U callback/webhook ke backend

POST /api/v1/webhooks/ticket2u  → Callback dari Ticket2U (idempotent, signed)
  Body: { vendor_transaction_id, status, payload }
  → Queue job: SyncTransactionJob → update transactions table
  → Broadcast: TransactionUpdated ke user via Reverb
```

### 5.5 Admin CMS (auth:sanctum + role:admin)
```
GET    /api/v1/admin/dashboard      → Stats cards (cache 60s)
GET    /api/v1/admin/events         → Event list (paginated, filter: status, date, venue)
POST   /api/v1/admin/events         → Create event
GET    /api/v1/admin/events/{id}    → Detail event
PUT    /api/v1/admin/events/{id}    → Update event
DELETE /api/v1/admin/events/{id}    → Soft delete / archive
POST   /api/v1/admin/events/{id}/publish    → Publish now
POST   /api/v1/admin/events/{id}/schedule → Schedule publish

GET    /api/v1/admin/banners        → Banner list
POST   /api/v1/admin/banners        → Upload banner
PUT    /api/v1/admin/banners/{id}   → Update
DELETE /api/v1/admin/banners/{id}   → Delete

GET    /api/v1/admin/users          → User list (filter, search)
GET    /api/v1/admin/users/{id}     → User detail
PUT    /api/v1/admin/users/{id}     → Update status / role

GET    /api/v1/admin/transactions   → Transaction list (filter: event, date, status)
GET    /api/v1/admin/reports/export → Queue export job, return download URL (Laravel Excel)

POST   /api/v1/admin/notifications/push → Push notif (target: all | selected[] | role)
  → Dispatch SendPushNotificationJob ke queue
  → Job mengirim broadcast Reverb + insert ke DB notifications
```

---

## 6. Development Sprint Breakdown (10 Hari)

### Day 1 — Auth & User Foundation
**Goal:** Full auth live, protected route, dashboard layout ready.

- **Backend:**
  - Install Laravel 11 + Fortify + Sanctum + spatie/laravel-permission.
  - Configure Sanctum SPA auth (cookie-based, same top-level domain).
  - Setup Redis session & cache driver.
  - Implement API routes: register, login, logout, forgot/reset password, email verification.
  - Rate limiting auth endpoints (5 req/min).
  - Seeder: Admin user, default roles.

- **Frontend:**
  - Axios instance with `withCredentials: true`, CSRF init flow.
  - Pages: Login, Register, Forgot Password, Reset Password, Email Verification.
  - Route guard: redirect unauthenticated ke login.
  - Layout: Sidebar/Navbar responsive, Dashboard shell.

**Deliverable:** ✅ Auth end-to-end tested via Postman & React.

---

### Day 2 — Landing Page & Event Display
**Goal:** Landing page dynamic, countdown working, responsive.

- **Backend:**
  - Migration & Model: `events`, `banners`.
  - API: `GET /banners`, `GET /events`, `GET /events/{slug}`.
  - ResponseCache middleware untuk public endpoints (60–300s).
  - Scout + Meilisearch indexing untuk event (optional jika waktu cukup, fallback fulltext MySQL).
  - Banner CMS API: upload image ke S3/R2, draft/publish/schedule.

- **Frontend:**
  - Hero Banner slider (auto-play, swipeable).
  - Event Card: thumbnail, name, venue, date.
  - Event Detail: description, venue map embed, ticket list, countdown timer.
  - Footer, About section.
  - Responsive: mobile-first Tailwind / Ant Design grid.

**Deliverable:** ✅ Landing page fully dynamic dari API.

---

### Day 3 — Admin CMS & Event Management
**Goal:** Full event CMS, draft/schedule/archive, admin dashboard usable.

- **Backend:**
  - Event CRUD API dengan validation (slug auto-generate, unique).
  - Status workflow: draft → scheduled → published → archived.
  - `scheduled_at` handler via Laravel Scheduler (publish otomatis).
  - Policy: hanya admin boleh create/update/delete.
  - Activity log untuk setiap CRUD (insert ke `activity_logs`).

- **Frontend (Admin Panel React):**
  - Route `/admin` dengan role guard.
  - Dashboard: stat card (total event, user, ticket sold today).
  - Event Manager: DataTable (TanStack Table) dengan filter, sort, pagination.
  - Form Event: React Hook Form + Zod validation, image upload preview.
  - Banner Manager: drag-drop sort (optional), CRUD.

**Deliverable:** ✅ Admin bisa CRUD event & banner tanpa gangguan traffic (write ke master, read dari cache).

---

### Day 4 — User Dashboard & Ticket System
**Goal:** User dashboard widgets, my tickets, ticket detail.

- **Backend:**
  - Migration: `event_tickets`, `transactions`.
  - API: `GET /me/dashboard`, `GET /me/tickets`, `GET /me/tickets/{id}`.
  - Ticket QR generation (URL signed atau hash unik) — bisa pakai `simplesoftwareio/simple-qrcode` atau generate URL ke PDF.
  - Cursor pagination untuk ticket list (performance pada dataset besar).

- **Frontend:**
  - Dashboard Widgets: Upcoming Event, Active Ticket count, Unread Notif badge, Countdown.
  - My Tickets: tab Active / Past, card dengan QR thumbnail.
  - Ticket Detail: QR besar, event info, download PDF (dompdf).

**Deliverable:** ✅ User dashboard & ticket structure ready.

---

### Day 5 — Notification & Report System
**Goal:** Push notif, notif center, export CSV/Excel.

- **Backend:**
  - Migration: `notifications` (UUID), `admin_push_logs`.
  - Notification classes: `PushNotification` via `Broadcast` (Reverb) + `Database` channel.
  - Queue Job: `SendPushNotificationJob` — kirim ke ribuan user tanpa blocking request.
  - Export API: `POST /admin/reports/export` → dispatch `ExportReportJob` → generate file di storage → return download URL (signed, expire 24h).
  - Laravel Excel configuration: chunk reading/writing untuk memory efficiency.

- **Frontend:**
  - Admin: Push Notif form (target: all / selected users / role), preview, history log.
  - User: Notification bell icon, dropdown list, mark-as-read, detail page.
  - Admin: Report filter (date range, event, status), export button dengan loading state.

**Deliverable:** ✅ Notifikasi & report export stable.

---

### Day 6 — Ticket2U Integration & Testing
**Goal:** Ticket purchase flow full, sync stable, error handling.

- **Backend:**
  - Service class: `Ticket2UService` — wrapper API Ticket2U (config: base_url, api_key, secret).
  - API: `POST /events/{slug}/buy` — validate ticket, call Ticket2U API untuk create transaction, return redirect URL.
  - Webhook handler: `POST /webhooks/ticket2u` — verify signature/payload, idempotency check (cek `vendor_transaction_id`), dispatch `SyncTransactionJob`.
  - Job: `SyncTransactionJob` — update `transactions` table, broadcast ke user, kirim email notifikasi (queue).
  - Error handling: retry 3x dengan exponential backoff, dead letter queue (failed_jobs).

- **Frontend:**
  - Flow: Event Detail → Pilih Ticket → Klik Buy → Cek Login → Redirect Ticket2U → Callback ke Success/Failed page.
  - Polling halaman status (atau listen Reverb channel) untuk update real-time setelah payment.
  - Error states: payment failed, ticket sold out, timeout.

**Deliverable:** ✅ Ticket2U integration tested end-to-end.

---

### Day 7 — Final QA & Deployment
**Goal:** Production ready, zero-downtime deploy.

- **DevOps:**
  - Server provisioning: Nginx, PHP 8.3+, MySQL 8.0, Redis, Supervisor (queue worker).
  - Octane setup: `php artisan octane:start --server=swoole --workers=auto`.
  - Horizon: `php artisan horizon` via Supervisor.
  - Reverb: `php artisan reverb:start` via Supervisor (scale horizontal).
  - SSL: Let's Encrypt / Cloudflare Full Strict.
  - Envoyer / Deployer: zero-downtime deployment.
  - `.env` production: `APP_ENV=production`, `SESSION_SECURE_COOKIE=true`, `SESSION_DRIVER=redis`, `CACHE_STORE=redis`, `QUEUE_CONNECTION=redis`.

- **QA:**
  - Load testing: k6 / Locust — simulate 50K concurrent read landing page.
  - Admin CRUD test selama load test berjalan — pastikan tidak bottleneck.
  - Mobile responsive check (Chrome DevTools).
  - Security: XSS, CSRF, SQL injection (Laravel handle, tapi verify), rate limit test.

**Deliverable:** ✅ LIVE MVP, production ready.

---

## 7. Security Requirements

| Threat | Mitigation |
|--------|-----------|
| **XSS** | React escaping default, sanitize HTML pakai DOMPurify jika raw HTML. Laravel `e()` untuk Blade (jika ada). |
| **CSRF** | Sanctum SPA auth dengan CSRF cookie + X-XSRF-TOKEN header otomatis Axios. |
| **SQL Injection** | Eloquent ORM / Query Builder (parameterized). Raw query dilarang kecuali extreme case. |
| **Rate Limiting** | Laravel Throttle: auth 5/min, API public 60/min, API auth 1000/min. |
| **File Upload** | Validate mime type, size max 2MB, store di S3/R2 (bukan local), filename random, scan ClamAV (opsional). |
| **Webhook Security** | Ticket2U webhook: verify HMAC signature atau secret key. Idempotency key cegah replay. |
| **IDOR** | Policy Laravel: user hanya bisa akses `transactions` miliknya sendiri. Admin middleware role check. |
| **Sensitive Data** | Password bcrypt 12 rounds. API token hash SHA-256 (Sanctum default). Phone/email encrypt jika diperlukan regulasi. |

---

## 8. Performance & Scalability Checklist

- [ ] **Laravel Octane** aktif di production (Swoole).
- [ ] **Redis** untuk session, cache, queue, broadcast.
- [ ] **MySQL Read Replicas** configured (1 master, 2–3 slave).
- [ ] **ResponseCache** untuk public endpoints (events, banners).
- [ ] **Meilisearch** untuk search (jangan query `LIKE %%` pada 50K users).
- [ ] **Queue + Horizon** untuk semua heavy operation: export, push notif, email, ticket sync.
- [ ] **Cursor Pagination** untuk infinite scroll / large dataset (no `OFFSET` pada page besar).
- [ ] **CDN** untuk semua static asset & banner image.
- [ ] **Database Indexing** sesuai query pattern (lihat schema indexes).
- [ ] **Nginx Microcaching** (optional) layer di atas ResponseCache.
- [ ] **Supervisor** monitoring: Octane, Horizon, Reverb, Queue Workers auto-restart.

---

## 9. Deployment & Environment

### 9.1 Required Environment Variables
```env
APP_ENV=production
APP_URL=https://blackskyasia.com
FRONTEND_URL=https://blackskyasia.com
SESSION_DOMAIN=.blackskyasia.com
SANCTUM_STATEFUL_DOMAINS=blackskyasia.com,admin.blackskyasia.com

DB_CONNECTION=mysql
DB_HOST=master.db.internal
DB_READ_HOSTS=slave1.db.internal,slave2.db.internal
DB_DATABASE=blacksky

REDIS_HOST=redis.cluster.internal
REDIS_CLIENT=phpredis

QUEUE_CONNECTION=redis
CACHE_STORE=redis
SESSION_DRIVER=redis

OCTANE_SERVER=swoole
OCTANE_WORKERS=auto
OCTANE_MAX_REQUESTS=5000

TICKET2U_BASE_URL=https://api.ticket2u.com.my
TICKET2U_API_KEY=
TICKET2U_WEBHOOK_SECRET=

AWS_BUCKET=blacksky-assets
AWS_USE_PATH_STYLE_ENDPOINT=false
```

### 9.2 Supervisor Config Example
```ini
[program:octane]
command=php /var/www/html/artisan octane:start --server=swoole --host=0.0.0.0 --port=8000
autostart=true
autorestart=true

[program:horizon]
command=php /var/www/html/artisan horizon
autostart=true
autorestart=true

[program:reverb]
command=php /var/www/html/artisan reverb:start --host=0.0.0.0 --port=8080
autostart=true
autorestart=true
```

---

## 10. Open Questions & Assumptions

1. **Ticket2U API Spec:** Asumsi REST JSON dengan webhook callback. Perlu dokumen resmi dari client untuk field mapping exact.
2. **Payment Currency:** Asumsi MYR/IDR tergantung event region. Perlu konfirmasi.
3. **Email Provider:** Asumsi SMTP/Postmark/SES via Laravel Mail. Perlu setup.
4. **Admin Panel Domain:** Disarankan subdomain `admin.blackskyasia.com` untuk isolasi traffic & security.
5. **Multi-tenancy:** Tidak di-scope MVP. Jika butuh multi-region (ID vs MY), diskusikan post-MVP.

---

## 11. Appendix: Library Installation Commands

```bash
# Laravel Core & First-Party
composer require laravel/sanctum laravel/fortify laravel/horizon laravel/octane laravel/pulse laravel/reverb

# Third-Party (Active & Maintained)
composer require spatie/laravel-permission spatie/laravel-responsecache
composer require maatwebsite/excel
composer require propaganistas/laravel-phone
composer require darkaonline/l5-swagger
composer require barryvdh/laravel-dompdf

# Scout + Meilisearch (optional tapi direkomendasikan)
composer require laravel/scout
# + install Meilisearch server

# Frontend (React — sudah via npm)
npm install @tanstack/react-query @tanstack/react-table axios react-hook-form zod date-fns laravel-echo pusher-js
# Reverb compatible dengan Echo tanpa Pusher key jika self-hosted
```

---

**End of Document**
