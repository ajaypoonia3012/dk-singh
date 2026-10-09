<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Transformation;
use App\Models\Service;
use App\Models\Setting;
use App\Models\HeroSetting;

// 1. Fix Transformation 1 image and raw HTML in story
$t = Transformation::find(1);
if ($t) {
    if (empty($t->image) && !empty($t->after_image)) {
        $t->image = $t->after_image;
    }
    // Clean raw HTML in story and description
    $t->story = trim(strip_tags($t->story));
    $t->description = trim(strip_tags($t->description));
    $t->save();
    echo "Transformation 1 updated: image={$t->image}, story=" . substr($t->story, 0, 50) . "...\n";
}

// 2. Fix Service 3 typo: "Diet & Nutrition Coachinin" -> "Diet & Nutrition Coaching"
$service3 = Service::where('title', 'like', '%Coachinin%')->first();
if ($service3) {
    $service3->title = 'Diet & Nutrition Coaching';
    if ($service3->slug === 'diet-nutrition-coachinin') {
        $service3->slug = 'diet-nutrition-coaching';
    }
    $service3->save();
    echo "Service 3 updated: title={$service3->title}, slug={$service3->slug}\n";
}

// 3. Check and fix any subtitle typo "withh"
$services = Service::all();
foreach ($services as $s) {
    if (str_contains($s->subtitle ?? '', 'withh')) {
        $s->subtitle = str_replace('withh', 'with', $s->subtitle);
        $s->save();
        echo "Service {$s->id} subtitle typo fixed.\n";
    }
    if (str_contains($s->description ?? '', 'withh')) {
        $s->description = str_replace('withh', 'with', $s->description);
        $s->save();
        echo "Service {$s->id} description typo fixed.\n";
    }
}

// 4. Check Setting / HeroSetting for "16++"
$setting = Setting::first();
if ($setting) {
    foreach ($setting->getAttributes() as $k => $v) {
        if (is_string($v) && str_contains($v, '16++')) {
            $setting->{$k} = str_replace('16++', '16+', $v);
            $setting->save();
            echo "Setting {$k} updated: 16++ -> 16+\n";
        }
        if (is_string($v) && str_contains($v, 'Coachinin')) {
            $setting->{$k} = str_replace('Coachinin', 'Coaching', $v);
            $setting->save();
            echo "Setting {$k} updated: Coachinin -> Coaching\n";
        }
    }

    // Concise navigation labels
    $setting->home_label = 'Home';
    $setting->program_label = 'Programs';
    $setting->service_label = 'Coaching';
    $setting->transformation_label = 'Transformations';
    $setting->blog_label = 'Blog';
    $setting->about_label = 'About';
    $setting->plan_label = 'Plans';
    $setting->product_label = 'Products';
    $setting->contact_label = 'Contact';
    $setting->save();
    echo "Setting navigation labels updated to concise standard.\n";
}

$hero = HeroSetting::first();
if ($hero) {
    foreach ($hero->getAttributes() as $k => $v) {
        if (is_string($v) && str_contains($v, '16++')) {
            $hero->{$k} = str_replace('16++', '16+', $v);
            $hero->save();
            echo "HeroSetting {$k} updated: 16++ -> 16+\n";
        }
    }
}

echo "Data fixes complete.\n";
