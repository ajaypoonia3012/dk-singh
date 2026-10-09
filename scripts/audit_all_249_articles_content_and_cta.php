<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Services\ArticleCtaService;
use Illuminate\Http\Request;

echo "=== AUDIT OF ALL 249 PUBLISHED ARTICLES: CONTENT INTEGRITY & CTAS ===\n\n";

$posts = BlogPost::with(['category', 'media', 'tags'])->where('status', true)->get();
$totalCount = $posts->count();
echo "Total published articles found: {$totalCount}\n\n";

$ctaService = app(ArticleCtaService::class);

$ctaBreakdown = [];
$destinationCounts = [];
$contentIntegrityIssues = [];
$brokenCtaLinks = [];
$renderedErrors = 0;

// Set of verified URLs to avoid duplicate HTTP requests
$verifiedUrls = [];

foreach ($posts as $index => $post) {
    // 1. Content Integrity Check
    if (empty($post->content) || strlen($post->content) < 100) {
        $contentIntegrityIssues[] = "Post #{$post->id} ({$post->slug}) has suspiciously short or empty content.";
    }
    if (empty($post->seo_title)) {
        $contentIntegrityIssues[] = "Post #{$post->id} ({$post->slug}) missing seo_title.";
    }
    if (empty($post->seo_description)) {
        $contentIntegrityIssues[] = "Post #{$post->id} ({$post->slug}) missing seo_description.";
    }
    if (empty($post->media_id)) {
        $contentIntegrityIssues[] = "Post #{$post->id} ({$post->slug}) missing media_id.";
    }

    // 2. CTA Resolution Check
    $cta = $ctaService->forPost($post);
    $categorySlug = $post->category?->slug ?? 'uncategorized';
    
    $badge = $cta['badge'];
    $ctaBreakdown[$badge] = ($ctaBreakdown[$badge] ?? 0) + 1;
    
    $primaryUrl = $cta['button_url'];
    $destinationCounts[$primaryUrl] = ($destinationCounts[$primaryUrl] ?? 0) + 1;

    // Verify primary URL responds with 200
    if (!isset($verifiedUrls[$primaryUrl])) {
        $path = parse_url($primaryUrl, PHP_URL_PATH);
        $req = Request::create($path, 'GET');
        $res = $app->handle($req);
        $status = $res->getStatusCode();
        $verifiedUrls[$primaryUrl] = $status;
        if ($status !== 200) {
            $brokenCtaLinks[] = "Broken primary CTA [{$primaryUrl}] (HTTP {$status}) on post #{$post->id} ({$post->slug})";
        }
    }

    // Verify secondary URL responds with 200
    if (!empty($cta['secondary_button_url'])) {
        $secUrl = $cta['secondary_button_url'];
        if (!isset($verifiedUrls[$secUrl])) {
            $secPath = parse_url($secUrl, PHP_URL_PATH);
            $secReq = Request::create($secPath, 'GET');
            $secRes = $app->handle($secReq);
            $secStatus = $secRes->getStatusCode();
            $verifiedUrls[$secUrl] = $secStatus;
            if ($secStatus !== 200) {
                $brokenCtaLinks[] = "Broken secondary CTA [{$secUrl}] (HTTP {$secStatus}) on post #{$post->id} ({$post->slug})";
            }
        }
    }

    // 3. Rendered Article View Check
    $viewReq = Request::create('/blog/' . $post->slug, 'GET');
    $viewRes = $app->handle($viewReq);
    if ($viewRes->getStatusCode() !== 200) {
        $renderedErrors++;
    }
}

echo "--- 1. CONTENT INTEGRITY RESULTS ---\n";
echo "Content issues detected in database: " . count($contentIntegrityIssues) . "\n";
if (!empty($contentIntegrityIssues)) {
    foreach (array_slice($contentIntegrityIssues, 0, 5) as $ci) {
        echo "  - {$ci}\n";
    }
} else {
    echo "  ✓ All 249 articles have intact body content, SEO titles, SEO descriptions, and media_ids.\n";
}

echo "\n--- 2. CONTEXTUAL CTA BREAKDOWN ACROSS 249 ARTICLES ---\n";
foreach ($ctaBreakdown as $badge => $count) {
    echo "  - [{$count} articles] {$badge}\n";
}

echo "\n--- 3. CTA DESTINATIONS & LINK HEALTH ---\n";
foreach ($destinationCounts as $url => $count) {
    $status = $verifiedUrls[$url] ?? 'unknown';
    echo "  - [{$count} articles] {$url} => HTTP {$status}\n";
}

echo "\n--- 4. BROKEN CTA LINKS ---\n";
echo "Broken links count: " . count($brokenCtaLinks) . "\n";

echo "\n--- 5. RENDERED ARTICLE VIEW TEST ---\n";
echo "Rendered view HTTP errors: {$renderedErrors} / {$totalCount}\n";

$auditOutput = [
    'total_posts' => $totalCount,
    'content_integrity_issues' => $contentIntegrityIssues,
    'cta_breakdown' => $ctaBreakdown,
    'destination_counts' => $destinationCounts,
    'verified_urls' => $verifiedUrls,
    'broken_links' => $brokenCtaLinks,
    'rendered_errors' => $renderedErrors,
];

file_put_contents(__DIR__ . '/audit_249_full_cta_results.json', json_encode($auditOutput, JSON_PRETTY_PRINT));
echo "\nDetailed report saved to audit_249_full_cta_results.json\n";
