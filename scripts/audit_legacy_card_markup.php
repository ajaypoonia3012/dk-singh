<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BlogPost;

$posts = BlogPost::where('status', true)->get();

$matchCount = 0;
$noMatchCount = 0;
$multipleCardCount = 0;
$samples = [];

foreach ($posts as $post) {
    $content = $post->content;
    
    // Check how many '<div class="card' exist in total
    $totalCards = substr_count($content, '<div class="card');
    if ($totalCards > 1) {
        $multipleCardCount++;
        echo "Post #{$post->id} ({$post->slug}) has {$totalCards} '<div class=\"card' tags!\n";
    }

    $pattern = '/<div class="card bg-dark text-[w]hite[^>]*>.*?<\/div>\s*<\/div>\s*<\/div>/s';
    if (preg_match_all($pattern, $content, $matches)) {
        $matchCount++;
        if (count($matches[0]) > 1) {
            echo "Post #{$post->id} ({$post->slug}) matched legacy CTA pattern multiple times: " . count($matches[0]) . "\n";
        }
        if (count($samples) < 3) {
            $samples[] = [
                'id' => $post->id,
                'slug' => $post->slug,
                'snippet' => substr($matches[0][0], 0, 200) . '...' . substr($matches[0][0], -80),
            ];
        }
    } else {
        $noMatchCount++;
        echo "Post #{$post->id} ({$post->slug}) did NOT match legacy CTA pattern!\n";
    }
}

echo "\nSummary:\n";
echo "Total posts: " . $posts->count() . "\n";
echo "Matched legacy CTA pattern: {$matchCount}\n";
echo "Did not match legacy CTA pattern: {$noMatchCount}\n";
echo "Posts with multiple cards: {$multipleCardCount}\n";

print_r($samples);
