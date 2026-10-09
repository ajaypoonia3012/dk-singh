# DK SINGH FITNESS & NUTRITION
## SINGLE SOURCE OF TRUTH ARCHITECTURE AUDIT
### Settings · Website Builder · Homepage Cards · Theme Settings

**Document Version:** 1.0.0  
**Audit Date:** October 8, 2026  
**Audited Systems:**
1. `/admin/settings/1/edit` (`SettingResource`)
2. `/admin/website-builder` (`WebsiteBuilder` Livewire Page)
3. `/admin/homepage-cards` (`HomepageCardResource`)
4. `/admin/theme-settings` / `/admin/theme-settings/1/edit` (`ThemeSettingResource`)

---

## 1. Executive Summary

A comprehensive audit of the administrative architecture across `/admin/settings`, `/admin/website-builder`, `/admin/homepage-cards`, and `/admin/theme-settings` was executed to diagnose editing redundancies, database ownership conflicts, and synchronization gaps.

### Key Audit Finding
The administrative system currently spreads website management across four disconnected interfaces with overlapping responsibilities:
- **Hero Messaging & CTAs**: Editable in both `SettingResource` (Content tab) and `WebsiteBuilder` (Hero settings).
- **Hero Image & Media**: Editable via file upload in `SettingResource` (`settings.hero_image`) and via Media Library in `WebsiteBuilder` (`hero_settings.background_media_id`).
- **Section Visibility**: Toggled in `ThemeSettingResource` (`theme_settings.show_*`) AND toggled in `WebsiteBuilder` (`website_sections.enabled`).
- **Homepage Cards**: Dual full-CRUD capability in `HomepageCardResource` (Filament table/form) AND in `WebsiteBuilder` (Livewire cards manager).
- **Contact Information**: Form fields for homepage contact heading/description/map embed exist in both `SettingResource` (Contact tab) and `WebsiteBuilder` (Contact settings).
- **Design System Isolation**: Theme tokens and styling live exclusively inside `ThemeSettingResource` with no visual feedback on the actual website layout, while `WebsiteBuilder` has a live canvas with no access to theme tokens.

---

## 2. Current Architecture & Database Ownership

| Layer | System / Resource | Database Table | Model Class | Primary Function |
|---|---|---|---|---|
| **System 1** | `SettingResource` | `settings` | `App\Models\Setting` | Site identity, business info, SEO, analytics, navigation labels, footer, maintenance mode, legacy hero/about/contact copy. |
| **System 2** | `WebsiteBuilder` | `website_sections`, `hero_settings`, `homepage_cards`, `programs`, `products`, `transformations`, `testimonials`, `blogs`, `settings` | `WebsiteSection`, `HeroSetting`, `HomepageCard`, `Setting`, etc. | Interactive 3-column visual builder: section list, device preview canvas, properties editor. |
| **System 3** | `HomepageCardResource` | `homepage_cards` | `App\Models\HomepageCard` | Filament Table and Form resource for creating and reordering standalone homepage feature cards. |
| **System 4** | `ThemeSettingResource` | `theme_settings` | `App\Models\ThemeSetting` | Global design system tokens (colors, typography, buttons, cards, layout, navbar, footer, login appearance, dark mode) stored in `design_configuration` JSON. |

---

## 3. Data & Responsibility Mapping Matrix

| Setting / Data Field | Current Owners | DB Table(s) | Model(s) | Admin Location(s) | Public Frontend Consumer | Duplicated? | Canonical Source of Truth | Recommended Admin Owner |
|---|---|---|---|---|---|---|---|---|
| **Site Name & Tagline** | Settings | `settings` | `Setting` | `/admin/settings/1/edit` (General) | Nav, Footer, SEO, Meta tags | **No** | `settings` | `WebsiteBuilder` (General / Branding) |
| **Logo & Favicon** | Settings | `settings` | `Setting` | `/admin/settings/1/edit` (General) | Nav, Head, Footer | **No** | `settings` | `WebsiteBuilder` (Branding / Media) |
| **Business Contact Info** (Phone, Email, Address, Hours) | Settings | `settings` | `Setting` | `/admin/settings/1/edit` (Contact) | Footer, Contact page, Schema markup | **No** | `settings` | `SettingResource` & `WebsiteBuilder` sync |
| **Social Media Links** | Settings | `settings` | `Setting` | `/admin/settings/1/edit` (General/Contact) | Footer, Navbar | **No** | `settings` | `WebsiteBuilder` (Footer / General) |
| **SEO Defaults & Meta** | Settings | `settings` | `Setting` | `/admin/settings/1/edit` (SEO) | All public Blade heads | **No** | `settings` | `WebsiteBuilder` (SEO Defaults) |
| **Analytics & Webmaster** | Settings | `settings` | `Setting` | `/admin/settings/1/edit` (Analytics) | Head script tag | **No** | `settings` | `SettingResource` (System-level) |
| **Hero Title / Heading** | Settings & HeroSetting | `settings`, `hero_settings` | `Setting`, `HeroSetting` | `/admin/settings/1/edit` & `/admin/website-builder` | `home/sections/hero.blade.php` | **YES** | `hero_settings.heading` (fallback to `settings`) | `WebsiteBuilder` (Hero) |
| **Hero Subtitle** | Settings & HeroSetting | `settings`, `hero_settings` | `Setting`, `HeroSetting` | `/admin/settings/1/edit` & `/admin/website-builder` | `home/sections/hero.blade.php` | **YES** | `hero_settings.subheading` | `WebsiteBuilder` (Hero) |
| **Hero CTA Buttons** | Settings & HeroSetting | `settings`, `hero_settings` | `Setting`, `HeroSetting` | `/admin/settings/1/edit` & `/admin/website-builder` | `home/sections/hero.blade.php` | **YES** | `hero_settings.button_*` | `WebsiteBuilder` (Hero) |
| **Hero Background Image** | Settings & HeroSetting | `settings.hero_image`, `hero_settings.background_media_id` | `Setting`, `HeroSetting`, `Media` | `/admin/settings/1/edit` & `/admin/website-builder` | `home/sections/hero.blade.php` | **YES** | `hero_settings.background_media_id` (Media Library) | `WebsiteBuilder` (Hero) |
| **Homepage Section Visibility** | ThemeSetting & WebsiteSection | `theme_settings.show_*`, `website_sections.enabled` | `ThemeSetting`, `WebsiteSection` | `/admin/theme-settings` & `/admin/website-builder` | `home/index.blade.php` (`$theme->show_*`) | **YES** | Synchronized state (`ThemeSetting` & `WebsiteSection`) | `WebsiteBuilder` (Sections) |
| **Homepage Section Order** | WebsiteSection | `website_sections` | `WebsiteSection` | `/admin/website-builder` | Planned dynamic ordering | **No** | `website_sections.sort_order` | `WebsiteBuilder` (Sections) |
| **Homepage Feature Cards** | HomepageCard | `homepage_cards` | `HomepageCard` | `/admin/homepage-cards` & `/admin/website-builder` | `home/index.blade.php` | **YES** (Dual admin entry points) | `homepage_cards` | `WebsiteBuilder` (Cards) primary |
| **Contact Section Copy** | Settings | `settings` | `Setting` | `/admin/settings/1/edit` (Contact) & `/admin/website-builder` | `home/index.blade.php` (Contact section) | **YES** | `settings` | `WebsiteBuilder` (Contact) |
| **Brand Colors & Theme Tokens** | ThemeSetting | `theme_settings` | `ThemeSetting` | `/admin/theme-settings` | `x-theme.tokens`, CSS variables | **No** | `theme_settings` | `WebsiteBuilder` (Theme & Design) + Standalone |
| **Typography Scale & Fonts** | ThemeSetting | `theme_settings` | `ThemeSetting` | `/admin/theme-settings` | Global CSS / Tokens | **No** | `theme_settings` | `WebsiteBuilder` (Theme & Design) |
| **Button, Card & Surface Styles** | ThemeSetting | `theme_settings` | `ThemeSetting` | `/admin/theme-settings` | Component tokens | **No** | `theme_settings` | `WebsiteBuilder` (Theme & Design) |
| **Login Appearance & Overlays** | ThemeSetting | `theme_settings` | `ThemeSetting` | `/admin/theme-settings` (Login Appearance tab) | `/login`, `/admin/login` | **No** | `theme_settings` | `ThemeSettingResource` / Dedicated Tab |

---

## 4. Root Problems Identified

1. **Dual Hero Editors with Divergent Storage**:
   An administrator editing the hero title in `SettingResource` updates `settings.hero_title`. However, `home/sections/hero.blade.php` evaluates `$hero->heading` from `HeroSetting` first. If `HeroSetting.heading` is populated, changes made in `SettingResource` are silently ignored on the live frontend.

2. **Divergent Section Visibility Controls**:
   `WebsiteBuilder` provides toggle switches for `WebsiteSection.enabled`. Meanwhile, `home/index.blade.php` checks `@if($theme->show_hero)` and `@if($theme->show_programs)`. If an admin turns off a section in `WebsiteBuilder`, but `ThemeSetting.show_*` remains true (or vice-versa), the frontend and builder can fall out of sync.

3. **Redundant Admin Navigation Destinations**:
   The admin navigation menu under "Website & Growth" presents both:
   - `Website Builder`
   - `Homepage Cards`
   Both edit the exact same `HomepageCard` table records. Having both in the primary navigation causes user confusion over where card updates should occur.

4. **Fragmented Settings Model**:
   `SettingResource` mixes high-level website presentation fields (Hero, About image, Contact copy) with true system-level configuration (Google Analytics ID, Tax ID, Maintenance Mode, Legal Business Name).

---

## 5. Single Source of Truth Architectural Solution

### Conceptual Hierarchy
```
WEBSITE BUILDER (/admin/website-builder)
   │── Primary Management Experience
   │
   ├── [General & Branding] ───────► Reads & Writes: App\Models\Setting (site_name, tagline, logo, favicon)
   ├── [Homepage & Hero] ──────────► Reads & Writes: App\Models\HeroSetting + App\Models\Setting (synchronized)
   ├── [Sections & Ordering] ──────► Reads & Writes: App\Models\WebsiteSection + App\Models\ThemeSetting (synchronized)
   ├── [Homepage Cards] ───────────► Reads & Writes: App\Models\HomepageCard
   ├── [Contact Section] ──────────► Reads & Writes: App\Models\Setting (contact_title, map_embed_url, etc.)
   ├── [SEO Defaults] ─────────────► Reads & Writes: App\Models\Setting (meta_title, meta_description)
   └── [Theme & Design System] ────► Reads & Writes: App\Models\ThemeSetting (presets, colors, fonts, styles)
```

### Resource Specialization
1. **`WebsiteBuilder`**: The unified, visual, single entry point for all website-facing content, cards, hero, sections, SEO defaults, and theme design tokens.
2. **`SettingResource` (`Site Settings & System`)**: Focused strictly on system-level configuration (Tax ID, Legal Name, Analytics IDs, Mail/Support routing, System Maintenance). Hero and duplicate section copy fields are either synchronized or linked to Website Builder.
3. **`HomepageCardResource`**: Preserved as an advanced table manager with a prominent shortcut banner to `WebsiteBuilder`, or streamlined to maintain zero functional regression while keeping `WebsiteBuilder` as primary.
4. **`ThemeSettingResource`**: Preserved as the canonical deep-level design system resource while providing seamless access from the `WebsiteBuilder` Theme tab with real-time live preview.

---

## 6. Implementation Plan by Phase

1. **Synchronize Hero Storage**: When hero copy or imagery is updated in `WebsiteBuilder`, update both `HeroSetting` and the corresponding `Setting` fields atomically in a database transaction.
2. **Synchronize Section Visibility**: Map `WebsiteSection.enabled` directly to `ThemeSetting.show_{section}` so toggling in the builder instantly syncs with public frontend template conditions.
3. **Add General & SEO Tab in Website Builder**: Enable editing site name, tagline, logo, favicon, and SEO defaults directly inside Website Builder.
4. **Add Theme & Design System Panel in Website Builder**: Expose theme preset selection (DK Singh Signature, Editorial Fitness, Modern Fitness, Minimal, Bold Performance), brand color pickers, typography, button styles, and card styles with instant live preview in the builder canvas.
5. **Streamline Admin Navigation**: Ensure clear labeling and direct cross-links between `SettingResource`, `HomepageCardResource`, `ThemeSettingResource`, and `WebsiteBuilder`.
6. **Zero Regression Verification**: Preserve all public routes, checkout and payments, 249 blog articles, and existing database tables.
