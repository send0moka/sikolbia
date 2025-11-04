# Chatbot Improvement Roadmap

Date: 2025-10-28
Scope: Public chatbot page (`resources/views/chatbot/index.blade.php`) and Alpine component (`resources/js/components/pertanianReportForm.js`) plus related routes/services.

## Current state (baseline)
- Dedicated page `/chatbot` with guided + free-typing flows.
- Help modal with persistent tabs: Examples, Concept Guide, Data & Attributes, Visual, Advanced Tips.
- Quick examples and month chips; wilayah helper route available.
- Icons: info (help) and restart (reset) in header.

## Prioritized backlog

### P1 — UX flow and robustness
1) Sticky input + keyboard shortcuts
- Why: Faster conversational loop; mobile-friendly.
- What:
  - Make footer input sticky on small screens; keep always visible.
  - Keyboard: Enter to send, Shift+Enter for newline, Esc to cancel/reset confirmation, “/” to focus input.
- Files: `index.blade.php` (footer), `pertanianReportForm.js` (handlers).
- Acceptance:
  - Input remains visible while scrolling long threads.
  - Shortcuts work and are documented in Help > Tips.
- Effort: M

2) Typing indicator and streaming feedback
- Why: Perceived latency for slow queries.
- What:
  - Show a proper typing animation when `isLoading=true`.
  - Optional: incremental streaming UI (progressively append text).
- Files: `index.blade.php`, `pertanianReportForm.js`.
- Acceptance: Indicator appears within 100ms of send and hides on completion.
- Effort: M (S without streaming)

3) Persist conversation across reloads
- Why: Users may navigate away by accident.
- What: Save `conversation` and `wizard` to `localStorage`; auto-restore on init.
- Files: `pertanianReportForm.js`.
- Acceptance: Refreshing keeps the thread and selections; "Mulai Ulang" clears storage.
- Effort: M

4) Guided flow refinements
- Why: Reduce back-and-forth questions.
- What:
  - Remember last module and topik (recent history).
  - Smart defaults: auto-select "Tahun terbaru" and suggest "Semua bulan" where relevant.
  - Allow multi-provinsi selection before kabupaten step.
- Files: `pertanianReportForm.js`.
- Acceptance: 1–2 fewer clicks on common paths; no regressions.
- Effort: M

### P1 — Reliability and safety
5) Robust entity parsing (ID locale)
- Why: Handle flexible user phrasing in Bahasa Indonesia.
- What: Expand parsing for years, months (Jan/Jan–Mar/Jan-Mar), provinces/kab/kota; normalize using reference lists.
- Files: `pertanianReportForm.js`, reference JSON under `resources/js/data/`.
- Acceptance: Prompts with different month separators and province aliases resolve correctly.
- Effort: M

6) HTML sanitization for chat content
- Why: Prevent unsafe HTML in `x-html` bubbles.
- What: Sanitize bot/user-rendered HTML (allow whitelisted tags) before injection.
- Files: `pertanianReportForm.js` (+ small sanitizer lib or custom allowlist).
- Acceptance: `<script>`/inline event handlers removed; links safe; look unchanged.
- Effort: M

### P2 — Performance and scalability
7) Virtualized chat list
- Why: Long threads degrade performance.
- What: Render only visible messages; keep layout stable.
- Files: `index.blade.php` (container), `pertanianReportForm.js` (windowing state).
- Acceptance: 500+ messages remain smooth (<16ms frame budget on mid devices).
- Effort: L

8) Cache and dedupe API calls
- Why: Reduce load and speed up repeat queries.
- What: Cache `wilayahs`, `topiks/variabels`, and identical `filter` queries (keyed by params) with TTL and in-flight de‑duplication.
- Files: `pertanianReportForm.js`, `app/Services/ReportService.php` (optional server cache), Redis (optional).
- Acceptance: Second request for same params hits cache; stale invalidated on seed/migrate.
- Effort: M

### P2 — Discoverability and guidance
9) Help search + deep-link tabs
- Why: Users jump straight to the topic they need.
- What: Add a search box inside Help; support `?helpTab=data` URL hash to auto-open modal.
- Files: `index.blade.php` (help modal), `pertanianReportForm.js` (state), optional `resources/js/router-hash.js`.
- Acceptance: Typing filters visible sections; `#help=visual` opens on load.
- Effort: M

10) Module-specific templates
- Why: More relevant starting points.
- What: Load Examples from JSON per module; show 4–6 curated prompts each.
- Files: `resources/js/data/help-templates.json`, `index.blade.php` loader.
- Acceptance: Switching module swaps example chips; editable without code deploy.
- Effort: S

### P2 — Exports and sharing
11) Export results & transcript
- Why: Analysts need artifacts.
- What: Export current preview to CSV/XLSX; export chat transcript to Markdown.
- Files: `app/Exports/*` (Maatwebsite), `index.blade.php` (buttons), `pertanianReportForm.js` (serialize transcript).
- Acceptance: One-click download; preserves locale formatting; streaming-friendly.
- Effort: M

12) Shareable query links
- Why: Reproducibility.
- What: Encode selections (module, variabel, filters) in URL; decode on load.
- Files: `pertanianReportForm.js`.
- Acceptance: Opening the link reconstructs the same preview state.
- Effort: M

### P3 — Analytics, a11y, i18n
13) Event analytics (privacy‑aware)
- Why: Measure help usage and friction points.
- What: Emit events for help open, tab switches, quick-start clicks; store anonymized in DB or forward to GA.
- Files: `pertanianReportForm.js`, new endpoint `POST /api/analytics/chatbot`.
- Acceptance: Basic dashboard counts per day; no PII.
- Effort: M

14) Accessibility polish
- Why: Better keyboard and screen reader support.
- What: Ensure roles, labels, focus traps in modal; visible focus rings; contrast pass (WCAG AA).
- Files: `index.blade.php`, CSS tokens.
- Acceptance: Axe or Lighthouse a11y score ≥ 95 on chatbot page.
- Effort: S–M

15) Internationalization (optional)
- Why: Wider audience.
- What: Extract strings to Lang files; allow switching ID/EN.
- Files: `resources/lang/*`, `index.blade.php`.
- Acceptance: All UI text translatable; fallback to ID.
- Effort: M

### P3 — Testing and operations
16) Automated tests
- Why: Prevent regressions.
- What: Pest tests for routes; browser tests (Dusk/Playwright) covering help tabs and quick-start flow.
- Files: `tests/Feature/*`, `tests/Browser/*`.
- Acceptance: CI green; minimal flakiness.
- Effort: M

17) Feature flags and versioning
- Why: Safer releases.
- What: Gate larger changes (virtualization, analytics) behind config or .env flags; add UI version in footer.
- Files: `config/app.php`, `index.blade.php`.
- Acceptance: Toggle without redeploy; version visible for debugging.
- Effort: S

## Milestones (suggested)
- M1 (1–2 days): P1 items 1–6
- M2 (2–3 days): P2 items 7–12
- M3 (2 days): P3 items 13–17

## Design notes
- Keep existing integration points (routes, Livewire/Alpine component, ML API).
- Prefer additive changes; avoid breaking `NBM` contracts or exports.
- Put constants/labels in small JSON files for easy updates without code changes.

## Risks and mitigations
- Virtualization complexity → start with simple windowing; fall back gracefully.
- Caching consistency → include invalidation triggers on seed/migrate; short TTLs at first.
- Analytics privacy → store only event type/timestamp; no user text contents.

## References
- Chatbot view: `resources/views/chatbot/index.blade.php`
- Alpine logic: `resources/js/components/pertanianReportForm.js`
- Public routes: `routes/web.php`
- Exports pattern: `app/Exports/*` and `app/Http/Controllers/*Export*`
