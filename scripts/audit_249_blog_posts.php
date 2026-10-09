<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BlogPost;
use Illuminate\Http\Request;

$posts = BlogPost::with(['category', 'media', 'tags'])->get();
echo "Found " . $posts->count() . " BlogPost records.\n";

$errors = 0;
$missingSeoTitle = 0;
$missingSeoDesc = 0;
$missingImage = 0;
$missingCategory = 0;
$details = [];

foreach ($posts as $post) {
    $req = Request::create('/blog/' . $post->slug, 'GET');
    $res = $app->handle($req);
    $status = $res->getStatusCode();
    
    $issueList = [];
    if ($status !== 200) {
        $issueList[] = "HTTP $status";
        $errors++;
    }
    
    if (empty($post->seo_title)) {
        $missingSeoTitle++;
        $issueList[] = "Missing seo_title";
    }
    if (empty($post->seo_description)) {
        $missingSeoDesc++;
        $issueList[] = "Missing seo_description";
    }
    if (empty($post->media_id) && empty($post->featured_image)) {
        $missingImage++;
        $issueList[] = "Missing image/media";
    }
    if (empty($post->blog_category_id)) {
        $missingCategory++;
        $issueList[] = "Missing category";
    }
    
    if (!empty($issueList)) {
        $details[] = [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'status' => $status,
            'issues' => $issueList
        ];
    }
}

echo "=== 249 BLOG POST AUDIT SUMMARY ===\n";
echo "Total posts: " . $posts->count() . "\n";
echo "HTTP Errors: " . $errors . "\n";
echo "Missing SEO Title: " . $missingSeoTitle . "\n";
echo "Missing SEO Description: " . $missingSeoDesc . "\n";
echo "Missing Image/Media: " . $missingImage . "\n";
echo "Missing Category: " . $missingCategory . "\n";

if (!empty($details)) {
    echo "\nSample posts with issues (first 10):\n";
    print_r(array_slice($details, 0, 10));
}
