@props(['theme' => null])

<style data-theme-token-emitter>
    :root {
        --primary-color: {{ $theme?->primary_color ?: '#facc15' }};
        --secondary-color: {{ $theme?->secondary_color ?: '#111111' }};
        --accent-color: {{ $theme?->accent_color ?: '#ffffff' }};
        --success-color: {{ $theme?->success_color ?: '#22c55e' }};
        --warning-color: {{ $theme?->warning_color ?: '#f59e0b' }};
        --danger-color: {{ $theme?->danger_color ?: '#ef4444' }};
        --info-color: {{ $theme?->info_color ?: '#3b82f6' }};
        --neutral-color: {{ $theme?->neutral_color ?: '#6b7280' }};
        --heading-font: "{{ $theme?->heading_font ?: 'Poppins' }}", sans-serif;
        --body-font: "{{ $theme?->body_font ?: 'Poppins' }}", sans-serif;
        --font-scale: {{ $theme?->font_scale ?: 1 }};
        --heading-weight: {{ $theme?->heading_weight ?: 800 }};
        --body-weight: {{ $theme?->body_weight ?: 400 }};
        --letter-spacing: {{ $theme?->letter_spacing ?: 0 }}px;
        --line-height: {{ $theme?->line_height ?: 1.5 }};
        --primary-button-text: {{ $theme?->primary_button_text_color ?: '#111111' }};
        --secondary-button-bg: {{ $theme?->secondary_button_background ?: '#111111' }};
        --secondary-button-text: {{ $theme?->secondary_button_text_color ?: '#ffffff' }};
        --button-radius: {{ $theme?->button_radius ?: '1rem' }};
        --button-shadow: {{ $theme?->button_shadow ?: '0 10px 25px rgba(0,0,0,.12)' }};
        --button-hover-transform: {{ $theme?->button_hover_animation ?: 'translateY(-2px)' }};
        --card-background: {{ $theme?->card_background ?: '#ffffff' }};
        --card-radius: {{ $theme?->card_radius ?: '1.5rem' }};
        --card-shadow: {{ $theme?->card_shadow ?: '0 20px 40px rgba(0,0,0,.08)' }};
        --navbar-height: {{ $theme?->navbar_height ?: 96 }}px;
        --navbar-background: {{ $theme?->navbar_background ?: '#ffffff' }};
        --navbar-text: {{ $theme?->navbar_text_color ?: '#111111' }};
        --navbar-hover: {{ $theme?->navbar_hover_color ?: '#facc15' }};
        --hero-overlay-color: {{ $theme?->hero_overlay_color ?: '#000000' }};
        --hero-overlay-opacity: {{ $theme?->hero_overlay_opacity ?? 40 }};
        --hero-gradient: {{ filled($theme?->hero_gradient) ? $theme->hero_gradient : 'none' }};
        --footer-background: {{ $theme?->footer_background ?: '#000000' }};
        --footer-text: {{ $theme?->footer_text_color ?: '#ffffff' }};
        --footer-link: {{ $theme?->footer_link_color ?: '#9ca3af' }};
        --input-radius: {{ $theme?->input_radius ?: '1rem' }};
        --input-border: {{ $theme?->input_border_color ?: '#d1d5db' }};
        --input-focus: {{ $theme?->input_focus_color ?: '#facc15' }};
        --container-width: {{ $theme?->container_width ?: 1280 }}px;
        --section-padding: {{ $theme?->section_padding ?: 96 }}px;
        --spacing-scale: {{ $theme?->spacing_scale ?: 1 }};
        --sidebar-width: {{ $theme?->sidebar_width ?: 320 }}px;
        --dark-background: {{ $theme?->dark_background ?: '#111111' }};
        --dark-surface: {{ $theme?->dark_surface ?: '#1f2937' }};
        --dark-text: {{ $theme?->dark_text ?: '#f9fafb' }};
    }

    @if($theme?->animations_enabled === false)
        *, *::before, *::after { animation: none !important; transition: none !important; }
    @endif

    {!! $theme?->custom_css !!}
</style>
