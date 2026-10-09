<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Blog;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\Program;
use App\Models\Service;
use App\Models\Product;
use App\Models\Transformation;
use App\Models\Exercise;
use App\Models\WorkoutPlan;
use App\Models\DietPlan;

$baseUrl = 'http://127.0.0.1:8000';

function fetchPage($url) {
    global $app;
    $path = parse_url($url, PHP_URL_PATH) ?: '/';
    $query = parse_url($url, PHP_URL_QUERY);
    if ($query) {
        $path .= '?' . $query;
    }
    
    $req = Request::create($path, 'GET');
    $req->headers->set('Accept', 'text/html,application/xhtml+xml');
    
    try {
        $res = $app->handle($req);
        $status = $res->getStatusCode();
        $content = $res->getContent();
        return [$status, $content, $res->headers->get('Location')];
    } catch (\Throwable $e) {
        return [500, $e->getMessage(), null];
    }
}

// 1. Initial known seed routes
$seedRoutes = [
    '/',
    '/about',
    '/services',
    '/programs',
    '/plans',
    '/transformations',
    '/blog',
    '/contact',
    '/products',
    '/workout-plans',
    '/diet-plans',
    '/fitness',
    '/fitness/exercise',
    '/fitness/cardio',
    '/fitness/strength-training',
    '/fitness/yoga',
    '/fitness/holistic-fitness',
    '/fitness/wellness',
    '/fitness/exercise-library',
    '/fitness/search',
    '/fitness-hub',
    '/fitness-hub/workouts',
    '/fitness-hub/diets',
    '/login',
    '/register',
    '/forgot-password',
    '/sitemap.xml',
];

// Add samples of dynamic routes
$sampleProgram = Program::first();
if ($sampleProgram) $seedRoutes[] = '/programs/' . $sampleProgram->slug;

$sampleService = Service::first();
if ($sampleService) $seedRoutes[] = '/services/' . $sampleService->slug;

$sampleProduct = Product::first();
if ($sampleProduct) $seedRoutes[] = '/products/' . $sampleProduct->slug;

$sampleTransformation = Transformation::first();
if ($sampleTransformation) $seedRoutes[] = '/transformations/' . $sampleTransformation->slug;

$sampleCategory = BlogCategory::first();
if ($sampleCategory) $seedRoutes[] = '/blog/category/' . $sampleCategory->slug;

$sampleTag = BlogTag::first();
if ($sampleTag) $seedRoutes[] = '/blog/tag/' . $sampleTag->slug;

$sampleBlog = BlogPost::where('status', true)->first();
if ($sampleBlog) $seedRoutes[] = '/blog/' . $sampleBlog->slug;

$sampleExercise = Exercise::first();
if ($sampleExercise) $seedRoutes[] = '/fitness/exercise-library/' . $sampleExercise->slug;

$sampleWorkout = WorkoutPlan::first();
if ($sampleWorkout) $seedRoutes[] = '/workout-plans/' . $sampleWorkout->id;

$sampleDiet = DietPlan::first();
if ($sampleDiet) $seedRoutes[] = '/diet-plans/' . $sampleDiet->id;

echo "Seeded " . count($seedRoutes) . " initial routes.\n";

$visited = [];
$queue = $seedRoutes;
$routeInventory = [];
$brokenLinks = [];
$imageIssues = [];
$seoIssues = [];
$allDiscoveredLinks = [];

while (!empty($queue)) {
    $current = array_shift($queue);
    $normalized = parse_url($current, PHP_URL_PATH) ?: '/';
    $query = parse_url($current, PHP_URL_QUERY);
    $fullPath = $query ? $normalized . '?' . $query : $normalized;
    
    if (isset($visited[$fullPath])) continue;
    $visited[$fullPath] = true;
    
    // Skip external or non-GET / admin / member / logout
    if (strpos($fullPath, '/admin') === 0 || strpos($fullPath, '/member') === 0 || strpos($fullPath, '/logout') === 0) {
        continue;
    }
    
    echo "Auditing: $fullPath ... ";
    [$status, $html, $redirect] = fetchPage($fullPath);
    echo "Status: $status\n";
    
    $entry = [
        'url' => $fullPath,
        'status' => $status,
        'redirect' => $redirect,
        'title' => null,
        'h1' => null,
        'meta_description' => null,
        'canonical' => null,
        'og_title' => null,
        'og_description' => null,
        'og_image' => null,
        'schema_types' => [],
        'has_empty_destination' => false,
    ];
    
    if ($status >= 400) {
        $brokenLinks[] = [
            'url' => $fullPath,
            'status' => $status,
            'source' => 'Crawl'
        ];
    }
    
    if ($status == 200 && is_string($html)) {
        // Parse metadata using DOMDocument
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        
        // Title
        $titleNodes = $dom->getElementsByTagName('title');
        if ($titleNodes->length > 0) {
            $entry['title'] = trim($titleNodes->item(0)->textContent);
        }
        
        // H1
        $h1Nodes = $dom->getElementsByTagName('h1');
        if ($h1Nodes->length > 0) {
            $entry['h1'] = trim($h1Nodes->item(0)->textContent);
        }
        
        // Metas
        $metas = $dom->getElementsByTagName('meta');
        foreach ($metas as $meta) {
            $name = strtolower($meta->getAttribute('name') ?: $meta->getAttribute('property'));
            $content = $meta->getAttribute('content');
            if ($name === 'description') $entry['meta_description'] = $content;
            if ($name === 'og:title') $entry['og_title'] = $content;
            if ($name === 'og:description') $entry['og_description'] = $content;
            if ($name === 'og:image') $entry['og_image'] = $content;
        }
        
        // Canonical
        $links = $dom->getElementsByTagName('link');
        foreach ($links as $link) {
            if ($link->getAttribute('rel') === 'canonical') {
                $entry['canonical'] = $link->getAttribute('href');
            }
        }
        
        // Schema scripts
        $scripts = $dom->getElementsByTagName('script');
        foreach ($scripts as $script) {
            if ($script->getAttribute('type') === 'application/ld+json') {
                $json = json_decode($script->textContent, true);
                if ($json && isset($json['@type'])) {
                    $entry['schema_types'][] = $json['@type'];
                }
            }
        }
        
        // Extract all internal <a> links
        $aTags = $dom->getElementsByTagName('a');
        foreach ($aTags as $a) {
            $href = trim($a->getAttribute('href'));
            $linkText = trim($a->textContent);
            
            if ($href === '#' || $href === '') {
                // If it looks like a navigation/action link without destination
                if (strlen($linkText) > 2 && !in_array(strtolower($linkText), ['next', 'prev', 'menu', 'close'])) {
                    // Potential dummy link
                }
                continue;
            }
            
            if (strpos($href, 'javascript:') === 0 || strpos($href, 'mailto:') === 0 || strpos($href, 'tel:') === 0) {
                continue;
            }
            
            // Check if internal
            if (strpos($href, 'http') === 0) {
                $host = parse_url($href, PHP_URL_HOST);
                if ($host !== '127.0.0.1' && $host !== 'localhost' && $host !== 'dksinghfitness.com') {
                    continue; // external link
                }
                $href = parse_url($href, PHP_URL_PATH) . (parse_url($href, PHP_URL_QUERY) ? '?' . parse_url($href, PHP_URL_QUERY) : '');
            }
            
            // If relative internal
            if (strpos($href, '/') === 0) {
                // Clean hash
                $hashPos = strpos($href, '#');
                if ($hashPos !== false) {
                    $hash = substr($href, $hashPos);
                    $pathOnly = substr($href, 0, $hashPos) ?: '/';
                } else {
                    $pathOnly = $href;
                }
                
                if (!isset($visited[$pathOnly]) && !in_array($pathOnly, $queue)) {
                    // Limit queue size for blogs to avoid crawling 250 in loop during first pass, but add them
                    if (strpos($pathOnly, '/blog/') === 0 && count($queue) > 120) {
                        // queue limited
                    } else {
                        $queue[] = $pathOnly;
                    }
                }
                $allDiscoveredLinks[] = ['source' => $fullPath, 'dest' => $href, 'text' => $linkText];
            }
        }
        
        // Extract <img> tags to verify public images
        $imgTags = $dom->getElementsByTagName('img');
        foreach ($imgTags as $img) {
            $src = $img->getAttribute('src');
            $alt = $img->getAttribute('alt');
            if (empty($alt)) {
                $imageIssues[] = [
                    'page' => $fullPath,
                    'src' => $src,
                    'issue' => 'Missing alt attribute'
                ];
            }
        }
    }
    
    $routeInventory[] = $entry;
}

// 2. Audit all 249 Blog Articles
echo "\nAuditing 249 Blog articles in database...\n";
$articles = BlogPost::where('status', true)->get();
$articleAudit = [];
foreach ($articles as $art) {
    $issues = [];
    if (empty($art->seo_title) && empty($art->meta_title)) $issues[] = 'Missing seo_title';
    if (empty($art->seo_description) && empty($art->meta_description)) $issues[] = 'Missing seo_description';
    if (empty($art->featured_image) && empty($art->media_id)) $issues[] = 'Missing featured_image';
    if (empty($art->slug)) $issues[] = 'Missing slug';
    if (empty($art->title)) $issues[] = 'Missing title';
    
    // Check if URL resolves
    [$status, $html, $redir] = fetchPage('/blog/' . $art->slug);
    if ($status !== 200) {
        $issues[] = "HTTP Status: $status (Redirect: $redir)";
    }
    
    $articleAudit[] = [
        'id' => $art->id,
        'title' => $art->title,
        'slug' => $art->slug,
        'status_code' => $status,
        'issues' => $issues,
    ];
}

$report = [
    'inventory_count' => count($routeInventory),
    'inventory' => $routeInventory,
    'broken_links' => $brokenLinks,
    'image_issues' => array_slice($imageIssues, 0, 50),
    'total_image_issues' => count($imageIssues),
    'article_count' => count($articleAudit),
    'articles_with_issues' => array_values(array_filter($articleAudit, fn($a) => !empty($a['issues']))),
];

file_put_contents(__DIR__ . '/public_audit_results.json', json_encode($report, JSON_PRETTY_PRINT));
echo "\nAudit complete! Results written to scripts/public_audit_results.json\n";
echo "Total routes checked: " . count($routeInventory) . "\n";
echo "Total broken routes: " . count($brokenLinks) . "\n";
echo "Total image issues: " . count($imageIssues) . "\n";
echo "Articles with issues: " . count($report['articles_with_issues']) . "\n";
