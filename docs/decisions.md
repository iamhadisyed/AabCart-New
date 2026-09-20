# Decisions Log

Ambiguities in the spec and the decisions made to resolve them. Updated continuously as the build progresses.

## Sequencing
- Build order follows the spec's required order: DB schema → API list → screens list → business rules → implement backend module-by-module → web → mobile.
- `/web` (Materialize "full-version", TypeScript, App Router) was supplied by the user as a zip and extracted as-is into `/web`. The "starter-kit" and `demo-configs` variants from the zip were discarded (not needed).

## Web template adaptation (Materialize → static export + Laravel API)
The stock template is built for a Node server: it uses `next.config.ts` `redirects()`, Prisma + `next-auth` (`src/prisma`, `src/app/api/auth`), and App Router route handlers under `src/app/api/**`. None of these work with `output: 'export'`. Decisions:
- **Auth**: rip out `next-auth`/Prisma entirely. Replace with a thin client-side auth context that calls the Laravel Sanctum API (`/api/v1/auth/login`) and stores the bearer token (memory + httpOnly-unavailable-on-static-export constraint means we use a JS-accessible storage: `localStorage`, mitigated by short token TTL + refresh-on-401 + CSRF not applicable to token auth).
- **Routing/redirects**: remove `next.config.ts` `redirects()` (unsupported under static export). Replace root `/` behavior with a client component that redirects via `router.replace()` based on auth state, and keep the `[lang]` segment but resolve it at build time via `generateStaticParams` (already required for static export of dynamic segments).
- **Route handlers** (`src/app/api/**`): deleted. All data fetching goes to the Laravel API via a shared `apiClient` (axios/fetch wrapper) using `NEXT_PUBLIC_API_BASE_URL`.
- **Fake DB** (`src/fake-db`): deleted per-module as each real module is wired to the Laravel API; kept temporarily as reference only for UI shape while building.
- **Demo apps** not in spec (Academy, Chat, Email, Kanban, Logistics, eCommerce demo, Invoice demo, front-pages marketing site) are removed from navigation and eventually deleted from `src/views/apps` once confirmed unused, to keep the codebase maintainable per spec instruction ("remove/hide demo apps").
- **Menu**: `src/data/navigation/verticalMenuData.tsx` is replaced with a menu built dynamically from the logged-in user's permissions (fetched from `/api/v1/me`), not a static file.
- One Next.js app serves both Platform Admin and Society panels; post-login redirect target and menu tree are decided by `user.user_type` (`platform_admin` | `society_staff` | fallback) plus `user.permissions[]`.

## Multi-tenancy
- `society_id` lives on every tenant-owned table. Platform-level tables (societies, subscription_plans, society_subscriptions, platform_admins, platform_ad tables, platform audit log) are the only ones without it.
- Enforced via a global Eloquent scope (`BelongsToSociety` trait + `SocietyScope`) applied automatically from the authenticated user's `society_id`, plus a `EnsureSocietyContext` middleware that 403s any request missing tenant context for tenant routes.
- Platform Administrator accounts are a separate `platform_admins` table/guard (not a role inside a society) since they are not scoped to any single society.

## Auth / no-OTP verification
- Resident login identity = unit's permanent `reference_number` + password (spec explicit). Staff/admin login = email + password.
- "Latest bill number" check is implemented as: `units.current_bill_number` (denormalized, updated every bill run) compared against user input; older numbers rejected with a clear message (not silently accepted).
- Claim dispute: if `units.app_user_id` already set, a second verification attempt with matching data does NOT overwrite it — instead creates a `unit_claim_disputes` row for admin resolution, and the original link is untouched.
- Failed-attempt lockout is tracked per `(device_id, unit_id)` and per source IP (`verification_attempts` table), 5 attempts → cooldown (configurable, default 30 min), enforced by both app-level check and Laravel's rate limiter middleware.

## Billing
- Rate versioning: `rate_matrix` rows are never updated in place after use; a new row with a later `effective_from` is inserted and the old row's `effective_to` is set. Bill generation always picks the rate row effective for the bill's billing month.
- Bill lock: `bill_runs.status` = draft → generated → locked. Only `draft`/`generated` runs can be regenerated; `locked` runs are immutable, corrections happen via `bill_adjustments` on the next run.
- Bill numbering: `bill_number` unique per `(society_id, bill_run_id sequence)`, human-readable format `{society_code}-{YYYYMM}-{unit_seq}`. `reference_number` is permanent per unit, assigned once at unit creation, never reused.

## SOS
- Escalation is double-guarded: the scheduler (`schedule:run` every minute) checks all unacknowledged SOS older than the escalation threshold AND the mobile polling endpoint (hit every 5–10s from the security dashboard) also runs the same escalation check inline on each call, so escalation isn't delayed by up to a full minute of cron granularity.

## Hosting/queues
- All "real-time" behavior (SOS, notifications) is push (FCM) + polling, never sockets, per the GoDaddy shared-hosting constraint.
- Queue driver: `database`. `schedule:run` cron (every minute) chains `queue:work --stop-when-empty --max-time=50` (kept under the 1-minute cron cadence) so no long-running worker process is needed.

## Currency/locale
- All monetary columns are `decimal(12,2)` in PKR, no multi-currency.
- All datetime columns stored UTC in DB (Laravel default), converted to `Asia/Karachi` and `DD-MM-YYYY` at the presentation layer (API resources / Blade PDF templates), per spec.
