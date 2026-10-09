<?php

$baseUrl = 'http://127.0.0.1:8000';

function testEndpoint(string $path, int $expectedStatus = 200, ?string $mustContain = null): array {
    global $baseUrl;
    $url = $baseUrl . $path;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    $passed = ($status === $expectedStatus);
    if ($passed && $mustContain !== null && !str_contains($body, $mustContain)) {
        $passed = false;
        $reason = "Missing expected string: '{$mustContain}'";
    } else {
        $reason = $passed ? 'OK' : "Expected {$expectedStatus}, got {$status}. Error: {$error}";
    }

    return [
        'path' => $path,
        'status' => $status,
        'expected' => $expectedStatus,
        'passed' => $passed,
        'reason' => $reason,
        'bytes' => strlen($body ?? ''),
    ];
}

$endpoints = [
    // 1. Homepage & Navigation
    ['path' => '/', 'status' => 200, 'contain' => 'DK Singh'],
    ['path' => '/about', 'status' => 200, 'contain' => 'DK Singh'],
    ['path' => '/contact', 'status' => 200, 'contain' => 'Contact'],

    // 2. Fitness Hub & Article Pages
    ['path' => '/fitness-hub', 'status' => 200, 'contain' => 'Fitness Hub'],
    ['path' => '/fitness', 'status' => 200, 'contain' => 'Exercise'],
    ['path' => '/fitness/exercise-library', 'status' => 200, 'contain' => 'Exercise Library'],
    ['path' => '/blog', 'status' => 200, 'contain' => 'Blog'],
    ['path' => '/blog/post-pregnancy-fitness-safe-workouts-for-indian-moms', 'status' => 200, 'contain' => 'Post-Pregnancy Fitness'],

    // 3. Program Details & Plans
    ['path' => '/programs', 'status' => 200, 'contain' => 'Programs'],
    ['path' => '/programs/12-week-fat-loss-transformation', 'status' => 200, 'contain' => '12 Week Fat Loss'],
    ['path' => '/plans', 'status' => 200, 'contain' => 'Plans'],
    ['path' => '/plans?program=12-week-fat-loss-transformation', 'status' => 200, 'contain' => '12 Week Fat Loss'],

    // 4. Products & Checkout (guest redirect to login for checkout)
    ['path' => '/products', 'status' => 200, 'contain' => 'Products'],
    ['path' => '/checkout/product/1', 'status' => 302, 'contain' => null], // Auth-protected, redirects guests to login

    // 5. Coaching & Services
    ['path' => '/services', 'status' => 200, 'contain' => 'Services'],
    ['path' => '/services/diet-nutrition-coaching', 'status' => 200, 'contain' => 'Diet & Nutrition Coaching'],
    ['path' => '/services/personal-online-coaching', 'status' => 200, 'contain' => 'Personal Online Coaching'],

    // 6. Free Resources (ungated)
    ['path' => '/fitness-hub/workouts', 'status' => 200, 'contain' => 'Free Workout Plans'],
    ['path' => '/fitness-hub/workouts/1-week-workout-plan-designed-for-weight-loss', 'status' => 200, 'contain' => 'Workout plan designed for weight loss'],
    ['path' => '/fitness-hub/diets', 'status' => 200, 'contain' => 'Free Diet & Nutrition Plans'],
    ['path' => '/fitness-hub/diets/sustainable-high-protein-weight-loss-plan', 'status' => 200, 'contain' => 'Sustainable High-Protein Weight Loss Plan'],

    // 7. Admin & Auth
    ['path' => '/admin/login', 'status' => 200, 'contain' => 'Sign in'],
    ['path' => '/admin/website-builder', 'status' => 302, 'contain' => null], // Redirects unauthenticated to login

    // 8. Sitemap & Robots
    ['path' => '/sitemap.xml', 'status' => 200, 'contain' => '<urlset'],
    ['path' => '/robots.txt', 'status' => 200, 'contain' => 'https://dksinghfitness.com/sitemap.xml'],

    // 9. Error Pages
    ['path' => '/this-page-definitely-does-not-exist-404', 'status' => 404, 'contain' => 'Page Not Found'],
];

$results = [];
$allPassed = true;

foreach ($endpoints as $ep) {
    $res = testEndpoint($ep['path'], $ep['status'], $ep['contain']);
    $results[] = $res;
    if (!$res['passed']) {
        $allPassed = false;
    }
    printf("[%s] %-55s (HTTP %d, expected %d): %s\n", 
        $res['passed'] ? 'PASS' : 'FAIL', 
        $res['path'], 
        $res['status'], 
        $res['expected'], 
        $res['reason']
    );
}

echo "\nSummary: " . count($results) . " endpoints tested. All passed: " . ($allPassed ? "YES" : "NO") . "\n";
file_put_contents('scripts/deployment_smoke_test_results.json', json_encode($results, JSON_PRETTY_PRINT));
exit($allPassed ? 0 : 1);
