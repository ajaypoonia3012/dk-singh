<?php

use App\Http\Controllers\Front\AboutController;
use App\Http\Controllers\Front\ActionPlanController;
use App\Http\Controllers\Front\BillingController;
use App\Http\Controllers\Front\BlogController;
use App\Http\Controllers\Front\CoachNoteController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\DashboardController;
use App\Http\Controllers\Front\DietCompletionController;
use App\Http\Controllers\Front\DietPlanController;
use App\Http\Controllers\Front\FitnessController;
use App\Http\Controllers\Front\FitnessDietController;
use App\Http\Controllers\Front\FitnessHubController;
use App\Http\Controllers\Front\FitnessWorkoutController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\InvoiceController;
use App\Http\Controllers\Front\MemberProfileController;
use App\Http\Controllers\Front\MemberTransformationController;
use App\Http\Controllers\Front\MyOrderController;
use App\Http\Controllers\Front\MyPlanController;
use App\Http\Controllers\Front\NotificationController;
use App\Http\Controllers\Front\PaymentController;
use App\Http\Controllers\Front\PlanController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\ProductPaymentController;
use App\Http\Controllers\Front\ProgramController;
use App\Http\Controllers\Front\ProgressController;
use App\Http\Controllers\Front\ProgressReportController;
use App\Http\Controllers\Front\PurchaseController;
use App\Http\Controllers\Front\ServiceController;
use App\Http\Controllers\Front\TransformationController;
use App\Http\Controllers\Front\WorkoutCompletionController;
use App\Http\Controllers\Front\WorkoutPlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Livewire\Media\MediaBrowser;
use App\Livewire\Media\MediaLibrary;
use Illuminate\Support\Facades\Route;

/*
    |--------------------------------------------------------------------------
    | PROGRAMS
    |--------------------------------------------------------------------------
    */

Route::get('/programs', [ProgramController::class, 'index'])
    ->name('programs.index');

Route::get('/programs/{slug}', [ProgramController::class, 'show'])
    ->name('programs.show');

/*
|--------------------------------------------------------------------------
| PUBLIC WEBSITE
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index']);

Route::get('/about', [AboutController::class, 'index']);

Route::get('/services', [ServiceController::class, 'index'])
    ->name('services.index');

Route::get('/services/{slug}', [ServiceController::class, 'show'])
    ->name('services.show');

Route::get('/blog', [BlogController::class, 'index'])
    ->name('blog.index');

/*
|--------------------------------------------------------------------------
| BLOG 301 REDIRECTS FOR LEGACY URLS
|--------------------------------------------------------------------------
*/
$legacyCategoryRedirects = [
    'veritatis-distinctio' => '/blog/category/fitness',
    'occaecati-aspernatur' => '/blog/category/workout-and-training',
    'ut-mollitia' => '/blog/category/weight-loss',
    'repellat-et' => '/blog/category/nutrition',
    'ut-quis' => '/blog/category/indian-diet',
];

foreach ($legacyCategoryRedirects as $oldCat => $targetCatUrl) {
    Route::redirect('/blog/category/' . $oldCat, $targetCatUrl, 301);
}

$legacyBlogRedirects = [
    'laboriosam-voluptatem-veniam-praesentium-voluptatem-ab-amet-ea' => '/blog/category/workout-and-training',
    'praesentium-culpa-quisquam-ipsa-reiciendis-ea-aliquid-odio' => '/blog/how-to-build-muscle-on-an-indian-vegetarian-diet',
    'ut-enim-est-omnis-ea-voluptates' => '/blog/walking-vs-jogging-vs-running-for-weight-loss-which-one-burns-more-fat',
    'omnis-maxime-et-nihil-voluptates' => '/blog/how-to-lose-weight-without-giving-up-indian-food',
    'aut-et-ab-veritatis-omnis-cupiditate-alias' => '/blog/how-much-protein-do-indians-actually-need',
    'eos-qui-dicta-et-distinctio-magni-qui' => '/blog/the-science-behind-calorie-tracking-for-sustainable-weight-loss',
    'asperiores-maiores-repudiandae-nisi-nisi-tenetur-consequatur-enim' => '/blog/best-pre-workout-meal-for-indian-vegetarians',
    'nobis-pariatur-sunt-aperiam-quis-quasi-voluptas' => '/blog/how-to-choose-the-right-workout-split-based-on-your-lifestyle-not-trends',
    'illo-adipisci-delectus-quisquam-nihil' => '/blog/how-many-steps-a-day-to-lose-weight-a-complete-guide-based-on-your-goals',
    'consequatur-omnis-ex-quo-blanditiis-minima' => '/blog/how-much-protein-is-in-100g-paneer-the-key-facts-you-should-know',
    'modi-eius-et-sint-explicabo-quod' => '/blog/category/fitness',
    'facere-laudantium-numquam-vero-qui' => '/blog/category/indian-diet',
    'est-maxime-numquam-dolorem-consequatur-beatae-et-possimus' => '/blog/category/nutrition',
    'numquam-quam-tempore-aspernatur-et-sed-vel-sit' => '/blog/category/weight-loss',
    'vel-voluptatibus-et-ipsam-voluptates-nisi' => '/blog/category/healthy-recipes',
    'sit-odio-fugiat-dignissimos-ipsum-quia-impedit' => '/blog/category/lifestyle-and-wellness',
    'inventore-odit-vitae-ut-culpa-est-minus' => '/blog/rice-vs-roti-which-is-better-for-weight-loss',
    'hic-sunt-maiores-consequuntur-voluptate-voluptas' => '/blog/what-to-eat-before-and-after-a-workout-the-best-indian-foods-for-energy-and-reco',
    'est-quibusdam-quibusdam-ea-eos-et-laborum-fugiat' => '/blog/protein-in-soya-chunks-how-much-protein-per-100g-serving-size-and-health-benefit',
    'odio-voluptas-voluptatem-sit-aut-totam-omnis' => '/blog',
];

foreach ($legacyBlogRedirects as $oldSlug => $targetUrl) {
    Route::redirect('/blog/' . $oldSlug, $targetUrl, 301);
}

Route::get('/blog/category/{slug}', [BlogController::class, 'category'])
    ->name('blog.category');

Route::get('/blog/tag/{slug}', [BlogController::class, 'tag'])
    ->name('blog.tag');

Route::get('/blog/{slug}', [BlogController::class, 'show'])
    ->name('blog.show');
Route::get('/contact', [ContactController::class, 'index'])
    ->name('contact');

Route::post('/contact', [ContactController::class, 'submit'])
    ->name('contact.submit');

Route::get(
    '/products',
    [ProductController::class, 'index']
)->name('products.index');

Route::get(
    '/products/{slug}',
    [ProductController::class, 'show']
)->name('products.show');

Route::get('/plans', [PlanController::class, 'index']);

Route::get('/sitemap.xml', [SitemapController::class, 'index'])
    ->name('sitemap');

Route::get('/transformations', [TransformationController::class, 'index'])
    ->name('transformations.index');

Route::get('/transformations/{slug}', [TransformationController::class, 'show'])
    ->name('transformations.show');

Route::get('/workout-plans', [WorkoutPlanController::class, 'index'])
    ->name('workout-plans.index');

Route::get('/workout-plans/{id}', [WorkoutPlanController::class, 'show'])
    ->name('workout-plans.show');
Route::post(
    '/workout-plans/{id}/complete',
    [WorkoutCompletionController::class, 'store']
)->middleware('auth')
    ->name('workout.complete');

Route::get('/diet-plans', [DietPlanController::class, 'index'])
    ->name('diet-plans.index');

Route::get('/diet-plans/{id}', [DietPlanController::class, 'show'])
    ->name('diet-plans.show');

Route::post(
    '/diet-plans/{id}/complete',
    [DietCompletionController::class, 'store']
)->middleware('auth')
    ->name('diet.complete');

/*
|--------------------------------------------------------------------------
| FITNESS CONTENT PLATFORM & EXERCISE LIBRARY
|--------------------------------------------------------------------------
*/
Route::get('/fitness', [FitnessController::class, 'index'])->name('fitness.index');
Route::get('/fitness/exercise', [FitnessController::class, 'exercise'])->name('fitness.exercise');
Route::get('/fitness/cardio', [FitnessController::class, 'cardio'])->name('fitness.cardio');
Route::redirect('/fitness/products', '/fitness', 301)->name('fitness.products');
Route::get('/fitness/strength-training', [FitnessController::class, 'strengthTraining'])->name('fitness.strength-training');
Route::get('/fitness/yoga', [FitnessController::class, 'yoga'])->name('fitness.yoga');
Route::get('/fitness/holistic-fitness', [FitnessController::class, 'holisticFitness'])->name('fitness.holistic-fitness');
Route::get('/fitness/wellness', [FitnessController::class, 'wellness'])->name('fitness.wellness');
Route::get('/fitness/exercise-library', [FitnessController::class, 'exerciseLibrary'])->name('fitness.exercise-library');
Route::get('/fitness/exercise-library/{slug}', [FitnessController::class, 'exerciseDetail'])->name('fitness.exercise-detail');
Route::get('/fitness/search', [FitnessController::class, 'search'])->name('fitness.search');

Route::get(
    '/fitness-hub',
    [FitnessHubController::class, 'index']
)->name('fitness-hub');

Route::get(
    '/fitness-hub/workouts',
    [FitnessWorkoutController::class, 'index']
)->name('fitness-hub.workouts.index');

Route::get(
    '/fitness-hub/workouts/{slug}',
    [FitnessWorkoutController::class, 'show']
)->name('fitness-hub.workouts.show');

Route::get(
    '/fitness-hub/diets',
    [FitnessDietController::class, 'index']
)->name('fitness-hub.diets.index');

Route::get(
    '/fitness-hub/diets/{slug}',
    [FitnessDietController::class, 'show']
)->name('fitness-hub.diets.show');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED USERS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'membership'])->group(function () {

    Route::get('/member/action-plan', [ActionPlanController::class, 'index'])
        ->name('member.action-plan');

    Route::get('/member/coach-notes', [CoachNoteController::class, 'index'])
        ->name('member.coach-notes');

});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return redirect()->route('member.dashboard');
    })->name('dashboard');

    Route::get(
        '/member/profile',
        [MemberProfileController::class, 'index']
    )->name('member.profile');

    Route::post(
        '/member/profile',
        [MemberProfileController::class, 'update']
    )->name('member.profile.update');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

Route::middleware(['auth', 'profile.completed'])->group(function () {

    Route::get(
        '/member/my-plan',
        [MyPlanController::class, 'index']
    )->name('member.my-plan');

    Route::get('/member/dashboard', [DashboardController::class, 'index'])
        ->name('member.dashboard');

    Route::get(
        '/member/billing',
        [BillingController::class, 'index']
    )->name('member.billing');

    Route::get(
        '/member/progress',
        [ProgressController::class, 'index']
    )->name('member.progress');

    Route::get(
        '/member/progress/create',
        [ProgressController::class, 'create']
    )->name('member.progress.create');

    Route::post(
        '/member/progress',
        [ProgressController::class, 'store']
    )->name('member.progress.store');

    Route::get(
        '/member/transformations',
        [MemberTransformationController::class, 'index']
    )->middleware('auth')
        ->name('member.transformations');

    Route::get(
        '/member/progress-report',
        [ProgressReportController::class, 'download']
    )->middleware('auth')
        ->name('member.progress-report');

    Route::post(
        '/member/action-plan/{actionPlan}/complete',
        [ActionPlanController::class, 'complete']
    )
        ->middleware('auth')
        ->name('member.action-plan.complete');

    Route::get(
        '/member/notifications',
        [NotificationController::class, 'index']
    )->name('member.notifications');

    Route::post(
        '/member/notifications/{notification}/read',
        [NotificationController::class, 'markAsRead']
    )->name('member.notifications.read');

    Route::get(
        '/member/invoice/{order}',
        [InvoiceController::class, 'download']
    )->middleware('auth')
        ->name('invoice.download');

    Route::get(
        '/account/orders/{order}',
        [MyOrderController::class, 'show']
    )->middleware('auth')
        ->name('account.orders.show');

    /*
    |--------------------------------------------------------------------------
    | CHECKOUT
    |--------------------------------------------------------------------------
    */

    Route::get('/checkout/{id}', [PaymentController::class, 'checkout']);

    Route::post('/payment-success', [PaymentController::class, 'success']);

    Route::get('/payment-failed', [PaymentController::class, 'failed']);

});

Route::get(
    '/account/orders',
    [MyOrderController::class, 'index']
)
    ->middleware('auth')
    ->name('account.orders');

/*
|--------------------------------------------------------------------------
| PRODUCT CHECKOUT & PAYMENT (AUTH ONLY - NO PROFILE SURVEY REQUIRED)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get(
        '/checkout/product/{id}',
        [ProductPaymentController::class, 'checkout']
    )->name('product.checkout');

    Route::get(
        '/product-payment/{id}',
        [ProductPaymentController::class, 'checkout']
    )->name('product.payment');

    Route::post(
        '/product-payment-success',
        [ProductPaymentController::class, 'success']
    )->name('product.payment.success');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/media-library', MediaLibrary::class)->name('admin.media-library');
    Route::get('/media-test', MediaBrowser::class)->name('admin.media-test');
    Route::view('/grid-test', 'grid-test')->name('admin.grid-test');
});
