\# DK Singh Fitness

\# Architecture Blueprint (Version 2)



\---



\# Project Vision



DK Singh Fitness is not just a Laravel website.



It is a complete Fitness CMS similar to Shopify + Elementor + Kajabi.



The system allows administrators to visually build pages, manage products,

programs, transformations, blogs, memberships, and branding without writing code.



\---



\# Core Modules



\## 1. Website Builder



Purpose



Visual homepage/page builder.



Responsibilities



\- Section ordering

\- Live Preview

\- Device Preview

\- Section Properties

\- Drag \& Drop

\- Publish

\- Draft

\- History



Files



app/

&#x20;   Livewire/

&#x20;       Builder/

&#x20;           WebsiteBuilder.php



resources/views/



&#x20;   builder/



&#x20;       toolbar.blade.php



&#x20;       sidebar.blade.php



&#x20;       canvas.blade.php



&#x20;       properties/



&#x20;       preview/



Database



website\_sections



Future



\- Drag Drop

\- Duplicate

\- Undo

\- Version History



\---



\## 2. Theme Builder



Purpose



Global branding.



Controls



\- Colors

\- Fonts

\- Radius

\- Shadows

\- Button Styles

\- Cards

\- Layout Width



Database



theme\_settings



Used by



Entire Website



\---



\## 3. Homepage Engine



Homepage is NOT hardcoded.



Homepage loads sections from database.



Example



Hero



↓



Homepage Cards



↓



Programs



↓



Products



↓



Testimonials



↓



Contact



The order comes from



website\_sections.sort\_order



\---



\## 4. Section Engine



Every section has



Preview



Properties



Database



Example



Hero



preview/

hero.blade.php



properties/

hero.blade.php



Section Data



website\_sections



or



settings



\---



\## 5. Property Engine



Every section owns its own editor.



Example



Hero



Hero Title



Subtitle



Button



Image



Products



Heading



Columns



Show Price



Programs



Heading



Description



Testimonials



Items



Slider



Contact



Map



Phone



Email



No generic editor.



Every section gets its own file.



\---



\## 6. Preview Engine



Preview NEVER queries database directly.



Flow



Database



↓



Livewire State



↓



Preview



Preview only displays state.



Never reads Setting model directly.



\---



\## 7. Save Engine



Properties



↓



Livewire



↓



Database



↓



Preview Refresh



Eventually



Auto Save



\---



\## 8. Media Library



Future



Choose Image



Crop



Replace



Upload



Reuse



\---



\## 9. Blog Engine



CRUD



Categories



SEO



Preview



\---



\## 10. Product Engine



Products



Categories



Coupons



Inventory



Orders



\---



\## 11. Membership Engine



Subscriptions



Plans



Workout Access



Diet Access



Live Classes



Payments



\---



\## 12. Program Engine



Workout Programs



Exercises



Videos



Nutrition



Downloads



\---



\## 13. Transformation Engine



Before



After



Reviews



Weight Loss



Gallery



\---



\## 14. Testimonial Engine



Ratings



Reviews



Video Reviews



Slider



\---



\## 15. Contact Engine



Form



WhatsApp



Maps



Locations



Lead Capture



\---



\# Livewire Rule



Every module owns



Component



Views



Properties



Preview



Save Logic



Never mix logic.



\---



\# Folder Structure



resources/views/



builder/



toolbar.blade.php



sidebar.blade.php



canvas.blade.php



preview/



properties/



home/



products/



blogs/



programs/



transformations/



theme/



\---



\# Golden Rules



Never query models inside preview.



Never duplicate section logic.



Every section has Preview + Properties.



Everything editable.



Everything reusable.



Keep components small.



No business logic inside Blade.



Livewire owns the state.



\---



\# Current Status



Website Builder



80%



Theme Builder



60%



Homepage Engine



70%



Membership



80%



Products



90%



Programs



85%



Transformations



90%



Testimonials



90%



Blogs



80%



Checkout



85%



Admin Panel



90%



Future



Drag Drop



Auto Save



History



Media Library



Multi Page Builder



Template Library



AI Section Generator



