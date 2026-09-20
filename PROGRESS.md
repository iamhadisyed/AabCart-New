# Society Management System — Progress Tracker

Legend: [ ] not started · [~] in progress · [x] done

## 0. Foundation & Docs
- [x] Repo structure (/backend /web /mobile /docs)
- [ ] docs/decisions.md (ambiguity log)
- [ ] docs/schema.md
- [ ] docs/api.md + Postman collection
- [ ] docs/screens.md
- [ ] docs/deployment.md (GoDaddy cPanel guide)
- [ ] PROGRESS.md kept up to date (this file)

## 1. Backend — Core Platform
- [ ] Laravel project bootstrap (composer, .env.example, config)
- [ ] Sanctum auth setup, API versioning /api/v1
- [ ] Multi-tenancy: society_id global scope + middleware
- [ ] Base model traits: SoftDeletes, Auditable (created_by/updated_by), BelongsToSociety
- [ ] Audit log system (model + observer + listing API)
- [ ] Dynamic RBAC (roles, permissions, role_has_permissions) + seeders for default roles
- [ ] File storage: private/public disks, upload service w/ image compression
- [ ] Queue: database driver, scheduler wiring (schedule:run every minute)
- [ ] Notification system: FCM push + in-app notification center + queued bulk sends
- [ ] PDF service (DomPDF + Urdu font embedding)
- [ ] Excel import/export service (Laravel Excel)
- [ ] QR code service (pure-PHP)
- [ ] DB backup scheduler (daily mysqldump, keep 7)

## 2. Backend — Platform Admin Module
- [ ] Society onboarding CRUD (+ society admin account creation)
- [ ] Subscription plans & society subscriptions
- [ ] Platform-wide ads (advertisers, campaigns, placements, impressions/clicks)
- [ ] Platform audit log & global reports

## 3. Backend — Units & Property Setup
- [ ] Blocks / Streets / Units hierarchy
- [ ] Unit categories (dynamic), Tariff types (dynamic)
- [ ] Unit CSV/Excel bulk import
- [ ] Unit CRUD (reference number, residence status, app-linked status)

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
