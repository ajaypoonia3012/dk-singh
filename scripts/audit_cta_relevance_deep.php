<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BlogPost;
use App\Services\ArticleCtaService;

$posts = BlogPost::with(['category', 'tags'])->where('status', true)->get();
$ctaService = app(ArticleCtaService::class);

$audit = [];

foreach ($posts as $post) {
    $cta = $ctaService->forPost($post);
    $categorySlug = $post->category?->slug ?? 'uncategorized';
    $title = $post->title;
    $titleLower = strtolower($title);

    // Analyze intent keywords
    $tags = $post->tags->pluck('name')->toArray();

    $audit[] = [
        'id' => $post->id,
        'slug' => $post->slug,
        'title' => $title,
        'category' => $categorySlug,
        'tags' => $tags,
        'badge' => $cta['badge'],
        'heading' => $cta['heading'],
        'primary_url' => $cta['button_url'],
        'primary_text' => $cta['button_text'],
        'secondary_url' => $cta['secondary_button_url'] ?? null,
        'secondary_text' => $cta['secondary_button_text'] ?? null,
    ];
}

file_put_contents(__DIR__ . '/all_249_cta_audit.json', json_encode($audit, JSON_PRETTY_PRINT));
echo "Successfully audited " . count($audit) . " articles and saved to scripts/all_249_cta_audit.json\n";
