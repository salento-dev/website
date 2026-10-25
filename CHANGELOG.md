# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.3.0] - 2026-10-25

Cookie-free analytics ([#10](https://github.com/salento-dev/website/pull/10)), automatic event covers ([#13](https://github.com/salento-dev/website/pull/13)) and repository preparation for open source ([#12](https://github.com/salento-dev/website/pull/12)).

Before deploying: run `php artisan migrate`, make sure the scheduler runs (`php artisan schedule:run` via cron) and, behind a proxy or Cloudflare, configure `trustProxies` so visitors are not all counted with the same IP.

### Added

- Cookie-free unique visitors: a hash of IP and user agent with a salt that rotates daily. The IP is not stored.
- Traffic sources: referrer normalized by domain (`l.instagram.com` → `instagram.com`), `utm_source`/`utm_medium`/`utm_campaign` parameters and device type.
- Outbound click tracking (WhatsApp, Instagram, LinkedIn, email, resources and external links) via `sendBeacon` on `POST /analytics/events`, linked to the event or story the click came from.
- 404 tracking by path with the last referrer, to find broken or outdated links.
- Bot detection with `jaybizzle/crawler-detect`.
- `analytics:prune` (daily at 03:30) rolls up data older than 12 months into `analytics_daily_stats` and deletes the raw rows.
- Analytics dashboard with a period filter and comparison with the previous period, summary cards (visits, visitors, bounce rate, WhatsApp conversion) and Overview, Sources, Content and 404 tabs.
- Automatic covers for community events, rendered from Blade templates that match the Figma frames: website (16:9, can be saved as the event image), post (4:5) and story (9:16). Previewed and exported as PNG from the new "Copertine" page in the admin, in the browser (no Node or Chromium needed on the hosting).
- Cover fields on events (`cover_title`, `edition`, `subtitle`, `cover_note`) and a white "cover logo" on partners (`cover_logo_path`).
- `LICENSE` (MIT) and `LICENSE-ASSETS.md` aligned with [`salento-dev/brand`](https://github.com/salento-dev/brand): logo and name reserved, third-party content excluded, `docs/` under CC BY 4.0.
- Project `README.md`, `CODE_OF_CONDUCT.md` and `SECURITY.md` with private vulnerability reporting.
- CI on GitHub Actions: PHPUnit on MySQL 8.4 and Pint on changed files, on every pull request and push to `main`.
- Dependabot: weekly Composer and npm updates, monthly GitHub Actions updates; Vite, `laravel-vite-plugin` and `@tailwindcss/vite` are grouped.

### Changed

- Privacy and cookie policies describe what analytics collect, the legal basis and retention periods; statistics use no cookies.
- Admin dashboard: removed the welcome widget and the 60-second polling.
- Build tooling: Vite 8, `laravel-vite-plugin` 3, `@tailwindcss/vite` 4.3 and `concurrently` 10.
- Dependencies updated: Filament 5.10, `laravel/tinker` 3 and minor/patch updates for Composer and npm packages.
- The page scrollbar is thinner: the existing `scrollbar-thin` class now produces CSS with Tailwind 4.3.
- `composer.json` has the project name, description and support links.
- Whole codebase formatted with Pint.

### Fixed

- Admin login failed after the Filament update: the published Filament assets were outdated and `password-reveal.js` was missing ([#24](https://github.com/salento-dev/website/pull/24)).
- `EventCardTest` failed intermittently: `EventFactory` now decides `work_in_progress` from the actual `starts_at`.

### Security

- `AdminUserSeeder` no longer creates the admin with a hardcoded password: it reads `ADMIN_NAME`, `ADMIN_EMAIL` and `ADMIN_PASSWORD`, generates a random password when none is set and never runs in production.
- `follow-redirects` updated to 1.16.1 and `esbuild` removed from the lockfile, fixing two high and one low severity advisory.

## [1.2.0] - 2026-10-24

Design system, accessibility and UX overhaul of the public site ([#8](https://github.com/salento-dev/website/pull/8)).

### Added

- `DESIGN.md` documenting the "Night Console" design system (tokens, colors, typography, layout, shapes, components, rules), with the `.impeccable/design.json` sidecar.
- "No upcoming events" state on the home page and `/events`, pointing to the community WhatsApp group.
- Site-styled 404, 500 and 503 error pages in Italian, with links to events, home and WhatsApp.
- Event status on the event detail page ("Evento concluso", "Prossimo evento", "In corso") and, for past events, a block with the next event and the WhatsApp group.
- Absolute date and town on event cards.
- Closing "Entra nella community" section on the home page.
- Category filter for stories on mobile.
- "Skip to content" link, keyboard-operable sort control, screen-reader announcement of search result counts.
- Self-hosted Outfit, Albert Sans and Roboto Mono fonts (OFL), with a preload for the above-the-fold heading font.
- Feature tests for events search, event cards, event detail, error pages, resources, stories filter and accessibility (44 tests in total).

### Changed

- Default application locale is now Italian (`lang="it"`, localized dates).
- Heading hierarchy fixed across all pages (section eyebrows are no longer headings).
- Correct landmarks: `header` > `nav`, `main` around page content only, `footer` outside `main`.
- Visible keyboard focus everywhere; mobile menu and modals trap focus and close with Escape.
- Text contrast raised to WCAG AA (4.5:1) on dates, labels, badges, work-in-progress cards and link hover states.
- `prefers-reduced-motion` is respected (global CSS rule, the skills loop stays still).
- External and WhatsApp links announce that they open in a new tab; partner links include the partner name.
- Raw colors replaced by `surface-*` design tokens; off-system shadows, radii and arbitrary text sizes removed.
- Monospace font unified on Roboto Mono; long-form prose uses the `surface` scale (`.prose-mist`).
- Home page: "Gli ultimi incontri" heading, reordered sections, CTA pill for "Scopri gli Eventi", fewer eyebrows.
- Story cover images are shown in full color.
- Resource row dates use the small telemetry style.
- Functional microcopy and screen-reader labels translated to Italian (developer jargon labels stay in English on purpose).
- Event and story page descriptions rewritten to be concrete.
- `.claude/design.md` now points to `DESIGN.md` and only keeps the Blade component catalog.

### Fixed

- Past events search on `/events` threw an SQL error (it queried the renamed `description` column).
- Stories search on `/stories` threw a 500 error (it queried the removed `author` column).
- `Event::isPast()` treated future events without `ends_at` as past.
- "Altri eventi" always showed the two oldest events and could include work-in-progress events.
- "Passed: N" counted only the current page; the results counter was never visible (`md:d-flex`).
- Story filter listed categories with only unpublished stories.
- Resources search with no matches said "no resources published".
- `ResourceFactory` used columns removed from the schema.
- Typo "comunity" in the home hero; "1 visite" pluralization.

### Removed

- Legacy amber/orange utilities (`text-glow`, `text-gradient`, `shadow-glow*`, `shadow-neon*`, `bg-gradient-*`).
- Google Fonts requests (and the related paragraph in the privacy policy).
- The empty `/cosa-facciamo` page (`services` route).
- Unused components: `event-meta-card`, `ui/section-title`, `ui/countdown-bars`.

## [1.1.0] - 2026-07-07

Project documentation ([#4](https://github.com/salento-dev/website/pull/4)).

### Added

- Complete project documentation in `docs/`: functional analysis, architecture, code walkthrough, development and quality, community strategy and competitor analysis.
- Architecture Decision Records and a security & privacy document.
- Task and label management guide, GitHub project management section in `CLAUDE.md`.
- Issue and pull request templates, `CONTRIBUTING.md`.

## [1.0.0] - 2026-04-24

First production release.

### Added

- Real data seeders for events, partners, authors and stories, with partner logos linked to events.
- Resource authors and resource detail modal.
- Page view tracking (no cookies, no third parties) and analytics dashboard in the admin panel.
- Story protagonists, GitHub profile hints and author chips modal.
- `is_community_event` flag to distinguish events organized by the community.
- User management resource and password reset in the admin panel.
- Event dates displayed in the Europe/Rome timezone.

### Changed

- Compiled assets are committed to the repository (deploy via `git pull`).
- Storage URL generation centralized in model accessors.
- Several content fields made optional in the admin panel.
- Story cards fully clickable, with an image-free variant.

### Fixed

- Server PHP version.
- Upload MIME type whitelist.
- Filament panel access configuration.

## [0.2.0] - 2026-04-10

### Added

- Partners with CRUD in the admin panel and event relationships.
- Work-in-progress events, shown in the list but not yet published in detail.
- Events page split into upcoming, work in progress and past events.
- Stories with multiple authors and dynamic reading time.
- Event excerpt and body fields, lightbox component and Markdown bodies.
- Shared detail layout for events and stories.
- Centralized community configuration and dynamic navigation/footer links.
- GDPR privacy policy and cookie policy pages.
- Design system documentation and Tailwind CSS typography plugin.

### Changed

- Color system audit, component extraction, spacing and typography standardization.

## [0.1.0] - 2026-03-09

First version of the site.

### Added

- Laravel 12 application with Filament admin panel.
- Events with categories and media, event detail page with related events.
- Resources (documents and slides) with CRUD.
- Home page with events, documents and mission sections.
- Search filters, paginator, terminal and countdown components.
- Event form with timezone support.

[Unreleased]: https://github.com/salento-dev/website/compare/v1.3.0...HEAD
[1.3.0]: https://github.com/salento-dev/website/compare/v1.2.0...v1.3.0
[1.2.0]: https://github.com/salento-dev/website/compare/v1.1.0...v1.2.0
[1.1.0]: https://github.com/salento-dev/website/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/salento-dev/website/compare/v0.2.0...v1.0.0
[0.2.0]: https://github.com/salento-dev/website/compare/v0.1.0...v0.2.0
[0.1.0]: https://github.com/salento-dev/website/releases/tag/v0.1.0
