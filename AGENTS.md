# Black Sky Enterprise - Agent Instructions

Laravel 11 + React 18 SPA monolith for event management. Backend serves API, frontend is Vite-built React SPA.

## Quick Start

**Development (all services):**
```bash
composer dev
```
This runs 4 concurrent processes: Laravel server (8000), queue worker, Pail logs, and Vite dev server (5173).

**Individual commands:**
```bash
php artisan serve              # Backend only (port 8000)
npm run dev                    # Vite dev server only (port 5173)
php artisan queue:listen       # Queue worker
php artisan pail               # Real-time logs
```

**Testing:**
```bash
vendor/bin/phpunit             # Run all tests
vendor/bin/phpunit tests/Unit  # Unit tests only
vendor/bin/phpunit tests/Feature  # Feature tests only
```

**Database:**
```bash
php artisan migrate            # Run migrations
php artisan db:seed            # Seed database
php artisan migrate:fresh --seed  # Fresh start
```

## Architecture

### Backend (Laravel 11)
- **Auth**: Laravel Fortify (headless) + Sanctum (SPA cookie auth)
- **RBAC**: Spatie Laravel Permission
- **Queue**: Database driver (dev), Redis (production per PRD)
- **Cache**: Database driver (dev), Redis (production per PRD)
- **Session**: Database driver (dev), Redis (production per PRD)
- **Database**: SQLite (dev), MySQL 8.0+ with read replicas (production per PRD)

### Frontend (React 18)
- **Build**: Vite with Laravel plugin
- **Router**: React Router 7
- **State**: TanStack Query v5 for server state
- **Forms**: React Hook Form + Zod validation
- **UI**: Radix UI primitives + custom components
- **Styling**: Tailwind CSS v4 with custom design system (see DESIGN.md)

### File Structure
```
app/                    # Laravel backend
  Http/Controllers/Api/ # API controllers
  Models/              # Eloquent models
routes/
  api.php              # API routes (Fortify + custom)
  web.php              # SPA catch-all route
src/                   # React frontend
  app/                 # React app code
  assets/              # Images, fonts
  styles/              # Global CSS
  main.tsx             # React entry point
database/
  migrations/          # Database schema
  seeders/             # Data seeders
tests/
  Unit/                # Unit tests
  Feature/             # Feature tests
```

## API Structure

**Base URL**: `http://localhost:8000/api`

**Auth endpoints** (Laravel Fortify):
- `POST /sanctum/csrf-cookie` - Get CSRF token (required first)
- `POST /register` - User registration
- `POST /login` - Login
- `POST /logout` - Logout
- `POST /forgot-password` - Request password reset
- `POST /reset-password` - Reset password
- `GET /verify-email/{id}/{hash}` - Email verification
- `GET /user` - Get authenticated user (requires auth:sanctum)

**Public API** (v1):
- `GET /v1/events` - List published events (rate limit: 60/min)

**Authenticated API** (v1):
- `POST /v1/logout` - Logout (requires auth:sanctum)

## Development Workflow

### Adding New API Endpoints
1. Define route in `routes/api.php`
2. Create controller in `app/Http/Controllers/Api/`
3. Add rate limiting and auth middleware as needed
4. Test with `vendor/bin/phpunit`

### Adding Frontend Features
1. Components go in `src/app/components/`
2. Use `@/` alias for imports (resolves to `src/`)
3. Follow design system in `DESIGN.md`
4. Use TanStack Query for API calls

### Database Changes
1. Create migration: `php artisan make:migration create_table_name`
2. Run migration: `php artisan migrate`
3. Rollback if needed: `php artisan migrate:rollback`

## Important Conventions

### Backend
- **API versioning**: Use `/api/v1/` prefix for all custom endpoints
- **Rate limiting**: Public endpoints 60/min, auth endpoints 1000/min (per PRD)
- **Validation**: Use Form Requests for complex validation
- **Responses**: Return JSON with consistent structure
- **Queue**: Use for heavy operations (email, exports, notifications per PRD)

### Frontend
- **Component naming**: PascalCase (e.g., `EventCard.tsx`)
- **Hooks**: Prefix with `use` (e.g., `useAuth.ts`)
- **API calls**: Use TanStack Query hooks, not raw axios
- **Styling**: Tailwind classes, refer to DESIGN.md for design tokens
- **Forms**: React Hook Form + Zod schema validation

### Design System
- **Typography**: Barlow Condensed (headings), Barlow (body), Inter (UI)
- **Colors**: See DESIGN.md for full palette and semantic tokens
- **Spacing**: Use clamp() for fluid responsive values
- **Components**: Follow patterns in DESIGN.md (buttons, cards, navigation)

## Testing

- **Unit tests**: Test individual classes/methods in isolation
- **Feature tests**: Test HTTP endpoints and full request/response cycles
- **Test database**: Uses in-memory SQLite (see phpunit.xml)
- **Test environment**: `APP_ENV=testing`, queue/cache use `array` driver

## Production Targets (from PRD)

The PRD specifies production architecture for 50K concurrent users:
- **Laravel Octane** (Swoole) for high throughput
- **Redis** for session, cache, queue, broadcast
- **MySQL 8.0+** with read replicas (1 master, 2-3 slaves)
- **Laravel Horizon** for queue management
- **Laravel Reverb** for WebSockets
- **Meilisearch** for full-text search
- **CDN** (Cloudflare) for static assets

Development uses simpler drivers (database/file) for ease of setup.

## Common Pitfalls

1. **CSRF token**: Frontend must call `/sanctum/csrf-cookie` before any auth requests
2. **Sanctum domains**: Update `SANCTUM_STATEFUL_DOMAINS` in `.env` if changing ports
3. **Vite HMR**: If hot reload breaks, restart `npm run dev`
4. **Queue jobs**: In dev, queue uses `database` driver - run `php artisan queue:listen` or jobs won't process
5. **Asset paths**: Use `@/` alias in React imports, not relative paths
6. **Migrations**: Always test rollback before committing new migrations
7. **Design system**: Check DESIGN.md before creating new components - patterns likely exist

## Environment Setup

1. Copy `.env.example` to `.env`
2. Generate app key: `php artisan key:generate`
3. Create SQLite database: `touch database/database.sqlite` (or let Laravel create it)
4. Run migrations: `php artisan migrate`
5. Seed admin user: `php artisan db:seed` (credentials in `.env.example`)
6. Install frontend deps: `npm install`
7. Start dev: `composer dev` (or individual commands above)

## Key Dependencies

**Backend:**
- Laravel 11.31+
- PHP 8.2+
- Laravel Fortify (auth backend)
- Laravel Sanctum (SPA auth)
- Spatie Laravel Permission (RBAC)

**Frontend:**
- React 18.3
- React Router 7
- TanStack Query v5
- React Hook Form + Zod
- Radix UI + Tailwind CSS v4

## Documentation References

- **PRD**: `prd.md` - Full product requirements and production architecture
- **Design System**: `DESIGN.md` - Complete UI/UX guidelines, colors, typography, components
- **Guidelines**: `guidelines/Guidelines.md` - Template for custom rules (currently empty)
