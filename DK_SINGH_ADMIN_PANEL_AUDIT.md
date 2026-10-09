# DK SINGH FITNESS & NUTRITION
## ADMIN PANEL — COMPREHENSIVE ARCHITECTURE, UX/UI & SECURITY AUDIT
### FULL AUDIT OF THE FILAMENT CMS & ADMINISTRATIVE ECOSYSTEM

**Audit Date:** October 5, 2026  
**Target Surface:** Admin Panel (`/admin`) via `App\Providers\Filament\AdminPanelProvider`  
**Framework Version:** Laravel 12.x / Filament 3.x  
**Scope:** All 32 Filament Resources, 1 Custom Admin Page, 10 Dashboard Widgets, Policies, Navigation, and Data Integrity  
**Audit Status:** **COMPLETE — COMPREHENSIVE AUDIT REPORT**

---

## 1. CURRENT ADMIN ARCHITECTURE

The administrative back-office for DK Singh Fitness & Nutrition is built on **Filament 3.x**, running under the panel ID `admin` configured via `App\Providers\Filament\AdminPanelProvider`.

```
FILAMENT ADMIN ARCHITECTURE:
├── Provider: App\Providers\Filament\AdminPanelProvider
├── Path: /admin
├── Brand: Dynamic site_name from database settings (Setting model)
├── Primary Color: Amber (Theme Token Harmonized)
├── Authentication Middleware:
│   ├── Filament\Http\Middleware\Authenticate
│   ├── 'throttle:admin' (120 req/min per IP/user)
│   ├── VerifyCsrfToken
│   └── AuthenticateSession
├── Access Control Contract:
│   └── App\Models\User implements FilamentUser::canAccessPanel() -> $this->isAdmin()
├── Authorization Engine:
│   └── App\Policies\AdminPolicy (Enforced via Gate::policy() in AppServiceProvider)
├── Auto-Discovery:
│   ├── Resources: app/Filament/Resources (32 resources detected)
│   ├── Pages: app/Filament/Pages (WebsiteBuilder)
│   └── Widgets: app/Filament/Widgets (10 widgets configured)
└── Database Layer:
    ├── MySQL 8.x (Live Production: dk_singh_fitness)
    └── SQLite In-Memory (Test Suite)
```

---

## 2. COMPLETE ADMIN RESOURCE INVENTORY (32 RESOURCES)

| # | Resource File | Eloquent Model | Table Name | Record Count | Policy | Current Nav Group | Current Label | Current Sort | Status / Assessment |
| :-: | :--- | :--- | :--- | :-: | :--- | :--- | :--- | :-: | :--- |
| **1** | `BlogPostResource` | `BlogPost` | `blog_posts` | **249** | `AdminPolicy` | `Blog CMS` | Posts | 3 | **Primary CMS.** Needs editor & filter upgrades. |
| **2** | `BlogCategoryResource` | `BlogCategory` | `blog_categories` | **11** | `AdminPolicy` | `Blog CMS` | Categories | 1 | Functional. Move to Content group. |
| **3** | `BlogTagResource` | `BlogTag` | `blog_tags` | **21** | `AdminPolicy` | `Blog CMS` | Tags | 2 | Functional. Move to Content group. |
| **4** | `BlogResource` | `Blog` | `blogs` | **1** | `AdminPolicy` | `Content` | Blogs | null | **Legacy confusion.** 1 record vs 249 in `blog_posts`. |
| **5** | `ExerciseResource` | `Exercise` | `exercises` | **25** | **None** | `Fitness Platform` | Exercise Library | 1 | **SECURITY RISK:** Missing Gate policy mapping! |
| **6** | `WorkoutPlanResource` | `WorkoutPlan` | `workout_plans` | **1** | `AdminPolicy` | *(No Group)* | Workout Plans | null | Orphaned nav item. Move to Fitness group. |
| **7** | `DietPlanResource` | `DietPlan` | `diet_plans` | **1** | `AdminPolicy` | `Commerce` | Diet Plans | null | Misallocated. Move to Fitness & Diets group. |
| **8** | `MediaResource` | `Media` | `media` | **376** | `AdminPolicy` | `Website Builder` | Media Library | 1 | Hidden under Website Builder. Needs dedicated group. |
| **9** | `MediaCategoryResource`| `MediaCategory` | `media_categories` | **9** | `AdminPolicy` | `Website Builder` | Media Categories | 0 | Move to Media group. |
| **10**| `UserResource` | `User` | `users` | **2** | `AdminPolicy` | `CRM` | Users | null | Functional. Protect admin privileges from elevation. |
| **11**| `MembershipResource` | `Membership` | `memberships` | **4** | `AdminPolicy` | `Members` | Memberships | 2 | Functional. Connect to Members & Coaching group. |
| **12**| `PlanResource` | `Plan` | `plans` | **4** | `AdminPolicy` | `Commerce` | Plans | null | Move to Members & Coaching group. |
| **13**| `ActionPlanResource` | `ActionPlan` | `action_plans` | **2** | `AdminPolicy` | `Coaching` | Action Plans | 3 | Move to Members & Coaching group. |
| **14**| `CoachNoteResource` | `CoachNote` | `coach_notes` | **1** | `AdminPolicy` | `Members` | Coach Notes | null | Move to Members & Coaching group. |
| **15**| `ContactLeadResource` | `ContactLead` | `contact_leads` | **1** | `AdminPolicy` | `CRM` | Inquiries | 1 | Move to Members & Coaching group. |
| **16**| `ProductResource` | `Product` | `products` | **11** | `AdminPolicy` | `Commerce` | Products | null | **PROTECTED.** Commerce logic intact. |
| **17**| `OrderResource` | `Order` | `orders` | **11** | `AdminPolicy` | `Commerce` | Orders | null | Functional. Core commerce tracking. |
| **18**| `ShipmentResource` | `Shipment` | `shipments` | **2** | `AdminPolicy` | `Commerce` | Shipments | null | Functional. Courier order fulfillment. |
| **19**| `CourierProviderResource`| `CourierProvider`| `courier_providers`| **1**| `AdminPolicy` | `Commerce` | Courier Providers | null | Functional. Third-party logistics settings. |
| **20**| `ProgramResource` | `Program` | `programs` | **3** | `AdminPolicy` | *(No Group)* | Programs | null | Orphaned nav item. Move to Website & Growth. |
| **21**| `ServiceResource` | `Service` | `services` | **3** | `AdminPolicy` | `Business` | Services | null | Move to Website & Growth group. |
| **22**| `TransformationResource`| `Transformation` | `transformations`| **1** | `AdminPolicy` | `Content` | Transformations | null | Move to Website & Growth group. |
| **23**| `TransformationPhotoResource`| `TransformationPhoto`| `transformation_photos`| **1**| `AdminPolicy`| `Coaching` | Transformation Photos | null | Move to Website & Growth. |
| **24**| `TestimonialResource` | `Testimonial` | `testimonials` | **5** | `AdminPolicy` | `Content` | Testimonials | null | Move to Website & Growth group. |
| **25**| `HomepageCardResource`| `HomepageCard` | `homepage_cards` | **4** | `AdminPolicy` | `Content` | Homepage Cards | null | Move to Website & Growth group. |
| **26**| `WebsiteSectionResource`| `WebsiteSection`| `website_sections`| **8**| `AdminPolicy` | `Website Builder` | Homepage Builder | null | Nav hidden (`shouldRegisterNavigation=false`). |
| **27**| `NotificationResource`| `Notification` | `notifications` | **3** | `AdminPolicy` | `Members` | Notifications | null | Move to Communications group. |
| **28**| `MessageTemplateResource`| `MessageTemplate`| `message_templates`| **0**| `AdminPolicy`| *(No Group)* | Message Templates | null | Orphaned nav item. Move to Communications. |
| **29**| `CommunicationLogResource`| `CommunicationLog`| `communication_logs`| **0**| `AdminPolicy`| *(No Group)* | Communication Logs | null | Orphaned nav item. Move to Communications. |
| **30**| `CommunicationProviderResource`| `CommunicationProvider`| `communication_providers`| **0**| `AdminPolicy`| *(No Group)*| Communication Providers | null | Orphaned nav item. Move to Communications. |
| **31**| `SettingResource` | `Setting` | `settings` | **1** | `AdminPolicy` | `Business` | Site Settings | 90 | Enterprise-grade settings & SEO defaults. |
| **32**| `ThemeSettingResource`| `ThemeSetting` | `theme_settings` | **1** | `AdminPolicy` | `Business` | Design System | 91 | Enterprise-grade theme & appearance settings. |

---

## 3. NAVIGATION INVENTORY & DEFECT ANALYSIS

### Current Deficiencies:
1. **5 Ungrouped Resources:** `ProgramResource`, `WorkoutPlanResource`, `CommunicationLogResource`, `CommunicationProviderResource`, and `MessageTemplateResource` have no `$navigationGroup` assigned. They float at the top or bottom of the sidebar.
2. **The "Blog" vs "Blog CMS" Duality:**
   - Under `Blog CMS`: `BlogPostResource` (the real 249-article catalog).
   - Under `Content`: `BlogResource` (a single legacy record in the `blogs` table).
   - Content managers frequently click "Blogs" under Content and panic thinking 248 articles are missing.
3. **Buried Media Library:** With 376 images, the Media Library is nested under "Website Builder" rather than enjoying top-level accessibility.
4. **Lack of Numerical Sorting:** Over 20 resources have `null` for `$navigationSort`, leading to unstable alphabetical sort orders that vary across environments.
5. **Inconsistent Icons:** Multiple resources reuse generic icons (`heroicon-o-document-text`, `heroicon-o-squares-2x2`) without semantic distinction.

---

## 4. DASHBOARD AUDIT

The admin dashboard currently registers 10 widgets in `AdminPanelProvider`:
- `StatsOverview`
- `QuickActions`
- `RevenueChart`
- `MembershipChart`
- `RecentOrders`
- `RecentMembers`
- `ExpiringMemberships`
- `ActivityFeed`
- `LeadsChart`
- `LeadsOverview`

### Deficiencies Found:
1. **Zero Content Metrics:** Despite DK Singh Fitness being a major 249-article publishing hub, the dashboard displays **zero** article metrics, draft metrics, exercise metrics, or media statistics.
2. **Broken QuickAction Link:** `QuickActions.blade.php` hardcodes a button to `/admin/blogs/create` (the 1-record legacy Blog) rather than `/admin/blog-posts/create` (the 249-article active catalog)!
3. **No Movement or Exercise Quick Actions:** No buttons to quickly add exercises or upload media.
4. **Limited Activity Feed:** `ActivityFeed` only queries Orders, Users, and Memberships. It ignores new article publishing, content updates, and exercise additions.

---

## 5. ARTICLE CMS AUDIT (`BlogPostResource`)

The application houses **249 published articles** in `blog_posts`.

### Deficiencies Found:
1. **Single-Column Linear Form:** The form stacks 5 large sections in a single vertical column. On desktop, this causes excessive scrolling (over 3,500px height).
2. **Image Dropdown Without Visual Preview:** The `media_id` field is rendered as a plain searchable select dropdown (`Select::make('media_id')`). Content editors must remember file names (e.g. `blogs/walking-vs-jogging...`) with no thumbnail preview rendered in the form.
3. **Under-Powered Table Filters:**
   - Available: Category, Featured, Status.
   - Missing: Filter by **Content Type** (`exercise_guide`, `workout_guide`, `comparison`, etc.).
   - Missing: Filter by **Missing SEO** (articles with empty `seo_title` or `seo_description`).
   - Missing: Filter by **Missing Media** (articles with no attached featured image).
4. **No Character Counters on SEO Fields:** Editors cannot see when their SEO title exceeds the 60-character Google snippet limit or when meta descriptions exceed 160 characters.

---

## 6. EXERCISE CMS AUDIT (`ExerciseResource`)

The database contains **25 comprehensive exercise records** powering `/fitness/exercise-library`.

### Deficiencies Found:
1. **CRITICAL SECURITY FLAW:** `Exercise::class` was completely omitted from `AppServiceProvider::registerResourcePolicies()`. Gate has no policy bound to `Exercise`, resulting in `Policy: None`. While `canAccessPanel()` blocks non-admins from the entire panel, model-level authorization was unprotected.
2. **No Visual Media Preview:** Like `BlogPostResource`, `media_id` is an unassisted dropdown without a visual thumbnail.
3. **Missing Filters:** No table filters for missing media, missing SEO, or equipment type.
4. **Global Search Missing:** Search was only enabled on local table columns, not wired into Filament's global search.

---

## 7. MEDIA LIBRARY AUDIT (`MediaResource`)

The system manages **376 media assets** in the `media` table, categorized into 9 categories.

### Deficiencies Found:
1. **Table Lacks Crucial Asset Metadata:** The table displays path, name, category, and date. It completely hides:
   - File dimensions (`width` &times; `height`)
   - Formatted file size (KB / MB)
   - MIME type / format (`webp`, `jpeg`, `png`)
   - Alt text presence / status
2. **Accessibility & SEO Blind Spot:** Content managers have no way to filter or identify media items with missing `alt` text, risking WCAG accessibility failures and image SEO degradation.
3. **Buried Location:** Located under `Website Builder` instead of having its own dedicated `Media` navigation group.

---

## 8. SEO ADMIN AUDIT

### Current State:
- Site-wide SEO: Handled in `SettingResource` (Tab 5: Site SEO Defaults, Meta Keywords, OpenGraph fallback, Twitter Cards, Sitemap toggles).
- Post SEO: `BlogPostResource` provides `seo_title` and `seo_description`.
- Exercise SEO: `ExerciseResource` provides `seo_title`, `meta_description`, `canonical_url`.

### Deficiencies Found:
- No visual snippet preview (Google desktop/mobile simulation).
- No character limit indicators.
- No quick-filter in the post table to identify articles published with empty SEO fields.

---

## 9. USER & MEMBERSHIP AUDIT (`UserResource`, `MembershipResource`)

- `UserResource` manages registered users and coaches.
- Privilege Escalation Protection: Verified! Neither `is_admin` nor `account_type` is exposed as an editable form field in `UserResource`, preventing an admin from accidentally demoting themselves or a malicious actor from elevating a customer via form manipulation.
- Eager Loading: `UserResource` eager-loads `activeMembership.plan`. `MembershipResource` eager-loads `user:id,name` and `plan:id,name`.

---

## 10. PRODUCT ADMIN AUDIT (`ProductResource`)

- Total Products: **11**
- Protected Area: Products commerce logic, prices, Stripe/Razorpay integrations, and checkout flows are completely preserved.
- Table & Form: Functional, uses `media_id` relation to `Media`.

---

## 11. SECURITY AUDIT

| Security Check | Implementation Status | Risk Level | Finding & Verification |
| :--- | :---: | :---: | :--- |
| **Panel Access Control** | Implemented | **Secure** | `User::canAccessPanel()` enforces `$this->isAdmin()`. Non-admins receive HTTP 403. |
| **Policy Registration** | **Defect Identified** | **High** | `Exercise::class` was missing from `AppServiceProvider::registerResourcePolicies()`. Must be registered to enforce `AdminPolicy`. |
| **Mass Assignment** | Implemented | **Secure** | Sensitive columns (`is_admin`, `account_type`) protected in model fillables and booted listeners. |
| **Rate Limiting** | Implemented | **Secure** | `throttle:admin` limits requests to 120/min. |
| **File Upload Security** | Implemented | **Secure** | `AppServiceProvider::configureSecureUploads()` enforces 20MB limit and strict MIME whitelist (`image/jpeg`, `image/png`, `image/webp`, `image/gif`, `video/mp4`, `application/pdf`). |
| **HTML Sanitization** | Implemented | **Secure** | RichEditor outputs sanitized HTML tags; Blade views escape user-supplied variables. |

---

## 12. DATA INTEGRITY AUDIT

- **Media Relationship Architecture:** The primary media pattern is `media_id` (foreign key pointing to `media.id`). All models (`BlogPost`, `Exercise`, `Product`, `Program`, `Testimonial`, `Transformation`, `HomepageCard`) adhere to this schema.
- **Rule:** DO NOT introduce a second media library or Spatie media-library migration. The native `media_id` relationship must be retained.
- **Cascade Behavior:** Deleting a `Media` record does not cascade-delete the attached `BlogPost` or `Exercise` (foreign keys are nullable or protected).

---

## 13. PERFORMANCE AUDIT

- All resource queries use `getEloquentQuery()` overrides with explicit eager loading (`with(['category', 'media', 'tags'])`).
- Zero N+1 query leaks detected on table listings.
- Dashboard queries use database aggregation (`selectRaw('COUNT(*) as aggregate, SUM(...)')`).

---

## 14. ACCESSIBILITY & RESPONSIVENESS AUDIT

- Filament 3 provides WCAG-compliant form components, ARIA landmarks, and keyboard focus rings.
- Mobile Viewport (390px): Filament's sidebar collapses into a slide-over off-canvas drawer.
- Table Viewports (768px - 1024px): Horizontal scrolling on tables is clean; no layout-breaking elements found.

---

## 15. PROPOSED NAVIGATION ARCHITECTURE (9 STRUCTURED GROUPS)

To eliminate orphaned resources and ambiguity, all 32 resources and pages are organized into **9 logical, priority-sorted groups**:

```
PROPOSED ADMIN NAVIGATION STRUCTURE:

├── 1. DASHBOARD
│     └── Overview (Home icon)

├── 2. EDITORIAL CONTENT (heroicon-o-document-text)
│     ├── Articles / Posts [Sort: 1] (BlogPostResource - 249 records)
│     ├── Categories [Sort: 2] (BlogCategoryResource)
│     ├── Tags [Sort: 3] (BlogTagResource)
│     └── Legacy Blog Archive [Sort: 4] (BlogResource - 1 record)

├── 3. FITNESS & MOVEMENT (heroicon-o-bolt)
│     ├── Exercise Library [Sort: 1] (ExerciseResource - 25 movements)
│     ├── Workout Plans [Sort: 2] (WorkoutPlanResource)
│     └── Diet Plans [Sort: 3] (DietPlanResource)

├── 4. MEDIA & ASSETS (heroicon-o-photo)
│     ├── Media Library [Sort: 1] (MediaResource - 376 assets)
│     └── Media Categories [Sort: 2] (MediaCategoryResource)

├── 5. MEMBERS & COACHING (heroicon-o-user-group)
│     ├── Users & Clients [Sort: 1] (UserResource)
│     ├── Memberships [Sort: 2] (MembershipResource)
│     ├── Membership Plans [Sort: 3] (PlanResource)
│     ├── Action Plans [Sort: 4] (ActionPlanResource)
│     ├── Coach Notes [Sort: 5] (CoachNoteResource)
│     └── Inquiries & Leads [Sort: 6] (ContactLeadResource)

├── 6. COMMERCE (heroicon-o-shopping-bag)
│     ├── Products [Sort: 1] (ProductResource - 11 items)
│     ├── Orders [Sort: 2] (OrderResource)
│     ├── Shipments [Sort: 3] (ShipmentResource)
│     └── Courier Providers [Sort: 4] (CourierProviderResource)

├── 7. WEBSITE & GROWTH (heroicon-o-globe-alt)
│     ├── Website Builder [Sort: 1] (WebsiteBuilder Page)
│     ├── Homepage Cards [Sort: 2] (HomepageCardResource)
│     ├── Coaching Programs [Sort: 3] (ProgramResource)
│     ├── Services [Sort: 4] (ServiceResource)
│     ├── Transformations [Sort: 5] (TransformationResource)
│     ├── Transformation Photos [Sort: 6] (TransformationPhotoResource)
│     └── Testimonials [Sort: 7] (TestimonialResource)

├── 8. COMMUNICATIONS (heroicon-o-chat-bubble-left-right)
│     ├── Notifications [Sort: 1] (NotificationResource)
│     ├── Message Templates [Sort: 2] (MessageTemplateResource)
│     ├── Communication Logs [Sort: 3] (CommunicationLogResource)
│     └── Providers [Sort: 4] (CommunicationProviderResource)

└── 9. SETTINGS & SYSTEM (heroicon-o-cog-6-tooth)
      ├── Site Settings & SEO [Sort: 1] (SettingResource)
      └── Design System & Themes [Sort: 2] (ThemeSettingResource)
```

---

## 16. PROPOSED COMPONENT REDESIGNS

### A. Article Editor (`BlogPostResource`)
- **Layout:** High-density 2-column grid.
  - **Main Area (Column Span 2):**
    - Section 1: Core Article Information (Title with live slug generation, Category, Format / Content Type, Tags, Author, Reading Time).
    - Section 2: Excerpt & Rich Text Body (Full-featured editor with H2, H3, blockquotes, code, lists).
  - **Sidebar Area (Column Span 1):**
    - Section 1: Publishing Workflow (Status toggle, Featured toggle, Publication Timestamp with sticky save action).
    - Section 2: Featured Image (Select with visual thumbnail preview, file name, and dimensions helper).
    - Section 3: Search Engine Optimization (SEO Title with character counter, Meta Description with counter, Canonical URL).
- **Table:**
  - Thumbnail preview column.
  - Color-coded badges for Categories and Content Types (`exercise_guide`, `workout_guide`, `comparison`, etc.).
  - New Filters: Filter by Content Type, Filter by Missing SEO, Filter by Missing Media, Status filter.
  - Bulk Actions: Bulk Publish, Bulk Unpublish, Bulk Delete.

### B. Exercise Library (`ExerciseResource`)
- Fix Gate policy mapping in `AppServiceProvider`.
- Two-column organized schema:
  - Movement execution, anatomical alignment, cues, breathing, safety considerations.
  - Media & video embed support.
  - SEO fields with character limits.
- Table: Target muscle, equipment, difficulty badges, status.
- New Filters: Filter by Missing Media, Missing SEO, Category, Difficulty.

### C. Media Library (`MediaResource`)
- Promote to dedicated "Media & Assets" navigation group.
- Table Enhancements:
  - Formatted file dimensions (`width` &times; `height`).
  - Human-readable file size (`KB` / `MB`).
  - Missing Alt Text indicator badge.
- Filters: Missing Alt Text filter (for rapid accessibility & SEO cleanup), Category filter, Active filter.

### D. Operational Dashboard
- **`StatsOverview` Upgrade:** Add Content & Movement stats:
  - Total Articles (Published vs Drafts)
  - Exercise Database Count
  - Media Asset Count
  - Total Members
  - Revenue & Orders
- **`QuickActions` Upgrade:** Replace broken `/admin/blogs/create` link with real, high-value CMS shortcuts:
  - ✍️ New Article (`/admin/blog-posts/create`)
  - ⚡ Add Exercise (`/admin/exercises/create`)
  - 📷 Upload Media (`/admin/media/create`)
  - 📦 New Product (`/admin/products/create`)
  - 💳 New Plan (`/admin/plans/create`)
  - ⚙️ SEO & Settings (`/admin/settings/1/edit?tab=seo`)
- **`ActivityFeed` Upgrade:** Add recently published articles and exercise additions to the recent orders and members feed.

### E. Global Search Integration
- Enable `$recordTitleAttribute` and search attributes across:
  - `BlogPostResource` (`title`, `slug`)
  - `ExerciseResource` (`name`, `primary_muscle`)
  - `ProductResource` (`name`, `sku`)
  - `UserResource` (`name`, `email`)
  - `MediaResource` (`name`, `file_name`)

---

## 17. FILES EXPECTED TO CHANGE

1. `app/Providers/AppServiceProvider.php` — Register `Exercise::class` policy.
2. `app/Filament/Resources/BlogPostResource.php` — Redesign form (2-column layout, image preview helper, SEO counters) & table (filters, badges, bulk actions, global search).
3. `app/Filament/Resources/ExerciseResource.php` — Redesign form (image preview, SEO helpers) & table (filters, badges, global search).
4. `app/Filament/Resources/MediaResource.php` — Move to dedicated group, add dimensions, size, missing alt indicator & filter, global search.
5. `app/Filament/Resources/ProductResource.php` — Add global search and navigation sort.
6. `app/Filament/Resources/UserResource.php` — Add global search and navigation group alignment.
7. `app/Filament/Resources/BlogCategoryResource.php` — Update group and navigation sort.
8. `app/Filament/Resources/BlogTagResource.php` — Update group and navigation sort.
9. `app/Filament/Resources/BlogResource.php` — Relabel as "Legacy Blog Archive" under Editorial Content.
10. `app/Filament/Resources/WorkoutPlanResource.php` — Assign to Fitness & Movement group.
11. `app/Filament/Resources/DietPlanResource.php` — Assign to Fitness & Movement group.
12. `app/Filament/Resources/MediaCategoryResource.php` — Assign to Media & Assets group.
13. `app/Filament/Resources/PlanResource.php` — Assign to Members & Coaching group.
14. `app/Filament/Resources/ActionPlanResource.php` — Assign to Members & Coaching group.
15. `app/Filament/Resources/CoachNoteResource.php` — Assign to Members & Coaching group.
16. `app/Filament/Resources/ContactLeadResource.php` — Assign to Members & Coaching group.
17. `app/Filament/Resources/OrderResource.php` — Assign to Commerce group with sort.
18. `app/Filament/Resources/ShipmentResource.php` — Assign to Commerce group with sort.
19. `app/Filament/Resources/CourierProviderResource.php` — Assign to Commerce group with sort.
20. `app/Filament/Resources/ProgramResource.php` — Assign to Website & Growth group.
21. `app/Filament/Resources/ServiceResource.php` — Assign to Website & Growth group.
22. `app/Filament/Resources/TransformationResource.php` — Assign to Website & Growth group.
23. `app/Filament/Resources/TransformationPhotoResource.php` — Assign to Website & Growth group.
24. `app/Filament/Resources/TestimonialResource.php` — Assign to Website & Growth group.
25. `app/Filament/Resources/HomepageCardResource.php` — Assign to Website & Growth group.
26. `app/Filament/Resources/NotificationResource.php` — Assign to Communications group.
27. `app/Filament/Resources/MessageTemplateResource.php` — Assign to Communications group.
28. `app/Filament/Resources/CommunicationLogResource.php` — Assign to Communications group.
29. `app/Filament/Resources/CommunicationProviderResource.php` — Assign to Communications group.
30. `app/Filament/Resources/SettingResource.php` — Assign to Settings & System group.
31. `app/Filament/Resources/ThemeSettingResource.php` — Assign to Settings & System group.
32. `app/Filament/Pages/WebsiteBuilder.php` — Assign to Website & Growth group.
33. `app/Services/DashboardService.php` — Add article, exercise, and media stats.
34. `app/Filament/Widgets/StatsOverview.php` — Display editorial and fitness KPIs.
35. `resources/views/filament/widgets/dashboard/quick-actions.blade.php` — Fix links to real CMS routes.
36. `app/Filament/Widgets/Dashboard/ActivityFeed.php` — Add recent articles and exercises.
37. `resources/views/filament/widgets/dashboard/activity-feed.blade.php` — Render recent articles and exercises.

---

## 18. FILES THAT MUST NOT CHANGE (PROTECTED SURFACES)

- ALL public routes, views, controllers, and layouts (`routes/web.php`, `resources/views/fitness/**`, `resources/views/blog/**`, `resources/views/home/**`).
- ALL Products business logic and payment integrations (`app/Http/Controllers/Front/ProductController.php`, `app/Services/RazorpayService.php`, `resources/views/products/**`, `resources/views/checkout/**`).
- The 249-article database catalog (`blog_posts` records, slugs, categories, and content).
- Existing media files and database schema (`media_id` architecture).

---

## 19. RISK ASSESSMENT & MITIGATION STRATEGY

| Risk | Severity | Mitigation Strategy |
| :--- | :---: | :--- |
| **Breaking Existing Feature Tests** | Medium | Retain all resource classes, page routes, and model bindings. Run `php artisan test` continuously. |
| **Breaking Filament Form State** | Low | Preserve all existing database column names and validation rules in forms. |
| **Navigation Disorientation** | Low | Clean, intuitive group naming that directly reflects standard CMS workflows. |
| **Database Migration Hazards** | None | Zero database schema migrations required. All improvements leverage existing columns and models. |

---

## 20. IMPLEMENTATION PLAN & EXECUTION SEQUENCE

1. **Step 1 — Security & Policy Fix:** Register `Exercise::class` with `AdminPolicy` in `AppServiceProvider.php`.
2. **Step 2 — Navigation Architecture Harmonization:** Update `$navigationGroup`, `$navigationLabel`, `$navigationIcon`, and `$navigationSort` across all 32 resources and `WebsiteBuilder` page.
3. **Step 3 — Article CMS Optimization (`BlogPostResource`):** Implement 2-column layout, image preview helper, SEO character counters, table filters (Content Type, Missing SEO, Missing Media), and global search.
4. **Step 4 — Exercise CMS Optimization (`ExerciseResource`):** Implement image preview helper, SEO character counters, table filters, and global search.
5. **Step 5 — Media Library Optimization (`MediaResource`):** Add dimensions, file size, missing alt badge, missing alt filter, and global search.
6. **Step 6 — Global Search Wiring:** Enable `$recordTitleAttribute` and search attributes on `ProductResource` and `UserResource`.
7. **Step 7 — Dashboard Redesign:** Update `DashboardService`, `StatsOverview`, `QuickActions`, and `ActivityFeed` with real editorial and movement metrics.
8. **Step 8 — Validation & Automated Testing:** Run full test suite, route listing, and asset compilation (`npm run build`).
9. **Step 9 — Multi-Viewport Responsive Browser QA:** Inspect `/admin` across 1920px, 1440px, 1024px, 768px, and 390px.
10. **Step 10 — Create Final Implementation Report:** Generate `DK_SINGH_ADMIN_PANEL_REDESIGN_REPORT.md`.

---
*End of Audit Report. Proceeding to implementation.*
