<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const SETTING_COLUMN = 'enterprise_configuration';

    private const THEME_COLUMN = 'design_configuration';

    private const LEGACY_SETTING_COLUMNS = [
        'dark_logo',
        'apple_touch_icon',
        'support_email',
        'legal_business_name',
        'tax_id',
        'copyright_text',
        'fitness_hub_label',
        'maintenance_enabled',
        'maintenance_message',
    ];

    private const LEGACY_THEME_COLUMNS = [
        'success_color', 'warning_color', 'danger_color', 'info_color', 'neutral_color',
        'heading_font', 'body_font', 'font_scale', 'heading_weight', 'body_weight',
        'letter_spacing', 'line_height', 'primary_button_text_color',
        'secondary_button_background', 'secondary_button_text_color', 'button_radius',
        'button_shadow', 'button_hover_animation', 'card_background', 'card_radius',
        'card_shadow', 'navbar_height', 'navbar_background', 'navbar_text_color',
        'navbar_hover_color', 'hero_overlay_color', 'hero_overlay_opacity', 'hero_gradient',
        'footer_background', 'footer_text_color', 'footer_link_color', 'input_radius',
        'input_border_color', 'input_focus_color', 'container_width', 'section_padding',
        'spacing_scale', 'sidebar_width', 'animations_enabled', 'page_loader_enabled',
        'scroll_reveal_enabled', 'dark_mode_enabled', 'dark_mode_toggle', 'dark_background',
        'dark_surface', 'dark_text', 'custom_css', 'custom_js',
    ];

    public function up(): void
    {
        $this->addJsonColumn('settings', self::SETTING_COLUMN);
        $this->addJsonColumn('theme_settings', self::THEME_COLUMN);

        $this->moveLegacyColumnsToJson('settings', self::SETTING_COLUMN, self::LEGACY_SETTING_COLUMNS);
        $this->moveLegacyColumnsToJson('theme_settings', self::THEME_COLUMN, self::LEGACY_THEME_COLUMNS);
    }

    public function down(): void
    {
        if (Schema::hasColumn('settings', self::SETTING_COLUMN)) {
            Schema::table('settings', fn (Blueprint $table) => $table->dropColumn(self::SETTING_COLUMN));
        }

        if (Schema::hasColumn('theme_settings', self::THEME_COLUMN)) {
            Schema::table('theme_settings', fn (Blueprint $table) => $table->dropColumn(self::THEME_COLUMN));
        }
    }

    private function addJsonColumn(string $tableName, string $column): void
    {
        if (! Schema::hasColumn($tableName, $column)) {
            Schema::table($tableName, fn (Blueprint $table) => $table->json($column)->nullable());
        }
    }

    /**
     * Recover safely if an earlier MySQL attempt added some columns before failing.
     *
     * @param  array<int, string>  $candidateColumns
     */
    private function moveLegacyColumnsToJson(string $tableName, string $jsonColumn, array $candidateColumns): void
    {
        $legacyColumns = array_values(array_filter(
            $candidateColumns,
            fn (string $column): bool => Schema::hasColumn($tableName, $column),
        ));

        if ($legacyColumns === []) {
            return;
        }

        DB::table($tableName)
            ->select(['id', $jsonColumn, ...$legacyColumns])
            ->orderBy('id')
            ->chunkById(100, function ($records) use ($tableName, $jsonColumn, $legacyColumns): void {
                foreach ($records as $record) {
                    $configuration = json_decode($record->{$jsonColumn} ?? '[]', true) ?: [];

                    foreach ($legacyColumns as $legacyColumn) {
                        if ($record->{$legacyColumn} !== null) {
                            $configuration[$legacyColumn] = $record->{$legacyColumn};
                        }
                    }

                    DB::table($tableName)
                        ->where('id', $record->id)
                        ->update([$jsonColumn => json_encode($configuration, JSON_THROW_ON_ERROR)]);
                }
            });

        Schema::table($tableName, fn (Blueprint $table) => $table->dropColumn($legacyColumns));
    }
};
