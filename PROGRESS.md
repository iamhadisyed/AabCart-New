# Society Management System — Progress Tracker

Legend: [ ] not started · [~] in progress · [x] done

## 0. Foundation & Docs
- [x] Repo structure (/backend /web /mobile /docs)
- [x] docs/decisions.md (ambiguity log)
- [x] docs/schema.md
- [ ] docs/api.md + Postman collection
- [ ] docs/screens.md
- [ ] docs/deployment.md (GoDaddy cPanel guide)
- [~] PROGRESS.md kept up to date (this file)

## 1. Backend — Core Platform
- [x] Laravel project bootstrap (composer, .env.example, config)
- [x] All 84 tables migrated as Laravel migrations (verified: migrate + rollback round-trip clean on SQLite)
- [x] Sanctum auth setup, API versioning /api/v1 (staff login, resident login, platform admin login, /me, logout — all tested end-to-end)
- [x] Multi-tenancy: society_id global scope (SocietyScope) + Tenant helper for console/queue context
- [x] Base model traits: SoftDeletes, HasAuditColumns (created_by/updated_by), BelongsToSociety, LogsAuditTrail
- [x] Audit log system (LogsAuditTrail trait + AuditLog::record() for business actions; verified writing rows)
- [x] Dynamic RBAC (roles, permissions, role_permission/role_user pivots) + PermissionSeeder + RoleProvisioningService (10 default roles)
- [x] No-OTP resident verification: auto-match, pending-manual-approval, claim-dispute, lockout/cooldown — all tested end-to-end
- [ ] File storage: private/public disks, upload service w/ image compression
- [ ] Queue: database driver, scheduler wiring (schedule:run every minute)
- [ ] Notification system: FCM push + in-app notification center + queued bulk sends
- [ ] PDF service (DomPDF + Urdu font embedding) — package installed
- [ ] Excel import/export service (Laravel Excel) — package installed
- [ ] QR code service (pure-PHP) — package installed (simplesoftwareio/simple-qrcode, bacon/bacon-qr-code, no external binaries)
- [ ] DB backup scheduler (daily mysqldump, keep 7)

## 2. Backend — Platform Admin Module
- [ ] Society onboarding CRUD (+ society admin account creation)
- [ ] Subscription plans & society subscriptions
- [ ] Platform-wide ads (advertisers, campaigns, placements, impressions/clicks)
- [ ] Platform audit log & global reports

## 3. Backend — Units & Property Setup
- [x] Blocks / Streets / Units hierarchy (models + relations + CRUD API)
- [x] Unit categories (dynamic), Tariff types (dynamic) (models + CRUD API)
- [x] Unit CSV/Excel bulk import (UnitsImport, auto-creates missing lookups, per-row error reporting)
- [x] Unit CRUD API (reference number auto-generated + permanent, residence status, app-linked status, release-unit) — tested end-to-end

## 4. Backend — Billing Engine
- [ ] Charge heads (dynamic, frequency)
- [ ] Rate matrix (category × tariff × charge head) with versioning (effective_from)
- [ ] Unit-level overrides (extra charge / waiver) with approval
- [ ] One-off charges per unit
- [ ] Arrears carry-forward
- [ ] Adjustments (credit/debit lines)
- [ ] Late payment surcharge config
- [ ] Bill runs (generate/preview/lock/regenerate, queued chunked jobs)
- [ ] Bill PDF (3-copy layout, QR, ads slot, announcements, app-linked line)
- [ ] Bulk bill print/download (queued, chunked)
- [ ] Due-date & overdue reminder scheduler

## 5. Backend — Payments, Receipts, Reconciliation
- [ ] Cash/bank payment recording
- [ ] Payment proof upload + approve/reject workflow
- [ ] Bank statement import + auto-reconciliation (matched/unmatched/conflicting)
- [ ] Payment gateway abstraction (JazzCash/Easypaisa/Raast stub + callback routes)
- [ ] Receipts (own numbering, PDF)

## 6. Backend — Resident Registration & Verification (no OTP)
- [ ] Society lookup by code/search
- [ ] Verification flow (block/street/unit + name + latest bill number match)
- [ ] Pending manual approval queue
- [ ] Claim disputes
- [ ] Failed-attempt lockout & cooldown, IP rate limiting
- [ ] Password reset (admin-driven + optional email SMTP)
- [ ] Unit switcher (multi-unit login)
- [ ] Release unit (admin action)

## 7. Backend — Complaints
- [ ] Categories (dynamic) → department mapping
- [ ] Departments, heads, agents
- [ ] Complaint CRUD + status machine + reassignment + priority
- [ ] SLA timers + overdue scheduler alerts
- [ ] Timeline/comments/photos
- [ ] Reopen window, rating after closure

## 8. Backend — SOS
- [ ] SOS create (type, GPS, resident info) + FCM high-priority push
- [ ] Acknowledge / resolve workflow
- [ ] Escalation timer (60s configurable) via scheduler + polling trigger
- [ ] SOS log & response-time report

## 9. Backend — Blood Bank
- [ ] Donor registry (multiple donors per unit) + cooldown auto-hide
- [ ] Donor search/filter (block-only privacy)
- [ ] Blood requests + compatibility push targeting
- [ ] "I can help" contact reveal flow

## 10. Backend — Home Services
- [ ] Categories (dynamic) + providers CRUD
- [ ] Provider ratings/reviews
- [ ] Optional listing fee

## 11. Backend — Car Pooling
- [ ] Offer ride / request ride
- [ ] Seat requests accept/decline + contact reveal
- [ ] Verified-residents-only restriction

## 12. Backend — Online Forms
- [ ] Dynamic form builder (fields, fee)
- [ ] Default forms seeded (NOC, Transfer, Construction, Tenant Reg, Move-in/out, Material Gate Pass)
- [ ] Dues clearance check for NOC + NOC PDF
- [ ] Submission workflow + comments/attachments

## 13. Backend — Visitor & Gate Management
- [ ] Pre-approved guest + QR/code generation
- [ ] Gate scan/entry logging
- [ ] Unexpected visitor approve/deny flow
- [ ] Delivery/ride-hailing entries
- [ ] Domestic staff registry + entry/exit logs
- [ ] Vehicle registration + sticker + guard search

## 14. Backend — Community Modules
- [ ] Notices/News (targeting, pin, push)
- [ ] Events + RSVP
- [ ] Gallery (albums/photos)
- [ ] Info Desk (contacts, bylaws, FAQs, documents)
- [ ] Polls & Surveys (one vote/unit)
- [ ] Lost & Found
- [ ] Marketplace + moderation
- [ ] Resident directory (opt-in)
- [ ] Facility/amenity booking + fees
- [ ] Water tanker requests

## 15. Backend — Advertisements
- [ ] Advertisers & campaigns (platform + society)
- [ ] Placements (carousel, ad list, bill PDF slot)
- [ ] Impressions/clicks tracking + reports
- [ ] Default house ad fallback

## 16. Backend — Expenses & Finance
- [ ] Expense categories, vendors, expense entries
- [ ] Vendor payables
- [ ] Reports (income/expense, collection, defaulters aging, charge-head income, payment methods)
- [ ] Resident transparency report
- [ ] PDF/Excel export

## 17. Backend — Staff & Payroll
- [ ] Staff records
- [ ] Attendance
- [ ] Payroll generation + salary slip PDF

## 18. Backend — Notifications & Scheduler wiring
- [ ] All triggers wired (bills, complaints, SOS, blood, forms, visitors, notices, events, polls, bookings)
- [ ] Bulk batched sends

## 19. Web Panel (Next.js + Materialize) — awaiting template upload
- [ ] Template explored & demo content stripped
- [ ] Auth pages wired to Sanctum
- [ ] Role/permission-based dynamic navigation
- [ ] Platform Admin Panel screens
- [ ] Society Management Panel screens (all roles/dashboards)
- [ ] Static export build (output: 'export') verified

## 20. Mobile App (React Native)
- [ ] Project bootstrap + navigation shell
- [ ] Auth + unit switcher
- [ ] Resident mode screens
- [ ] Staff/Operations mode screens
- [ ] FCM integration (incl. SOS full-screen alarm)
- [ ] i18n English/Urdu + RTL

## 21. Non-functional
- [ ] Seeders with realistic PK sample data
- [ ] .env.example (backend/web/mobile) fully documented
- [ ] Rate limiting on auth/verification
- [ ] CORS configured
- [ ] docs/deployment.md complete & verified steps
