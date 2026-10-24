# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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

[Unreleased]: https://github.com/salento-dev/website/compare/v1.1.0...HEAD
[1.1.0]: https://github.com/salento-dev/website/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/salento-dev/website/compare/v0.2.0...v1.0.0
[0.2.0]: https://github.com/salento-dev/website/compare/v0.1.0...v0.2.0
[0.1.0]: https://github.com/salento-dev/website/releases/tag/v0.1.0
