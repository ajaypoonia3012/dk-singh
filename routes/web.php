<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\AboutController;
use App\Http\Controllers\Front\ServiceController;
use App\Http\Controllers\Front\BlogController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\ProductController;
use App\Http\Controllers\Front\PlanController;
use App\Http\Controllers\Front\PaymentController;
use App\Http\Controllers\Front\PurchaseController;
use App\Http\Controllers\Front\DashboardController;
use App\Http\Controllers\Front\TransformationController;
use App\Http\Controllers\Front\WorkoutPlanController;
use App\Http\Controllers\Front\DietPlanController;
use App\Http\Controllers\Front\ProgramController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Front\MyPlanController;
use App\Http\Controllers\Front\BillingController;
use App\Http\Controllers\Front\MemberProfileController;
use App\Http\Controllers\Front\ProgressController;
use App\Http\Controllers\Front\WorkoutCompletionController;
use App\Http\Controllers\Front\DietCompletionController;
use App\Http\Controllers\Front\MemberTransformationController;
use App\Http\Controllers\Front\WeeklyCheckInController;
use App\Http\Controllers\Front\ProgressReportController;
use App\Http\Controllers\Front\CoachNoteController;
use App\Http\Controllers\Front\ActionPlanController;
use App\Http\Controllers\Front\NotificationController;
use App\Http\Controllers\Front\InvoiceController;
use App\Http\Controllers\Front\MyOrderController;
use App\Http\Controllers\Front\FitnessHubController;
use App\Http\Controllers\Front\FitnessWorkoutController;
use App\Http\Controllers\Front\FitnessDietController;
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

Route::get('/blog/{slug}', [BlogController::class, 'show'])
    ->name('blog.show');

Route::get('/contact', [ContactController::class, 'index']);

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

Route::get(
    '/member/coach-notes',
    [CoachNoteController::class, 'index']
)->middleware('auth')
 ->name('member.coach-notes');

Route::get('/transformations', [TransformationController::class, 'index']);
Route::get('/transformations/{id}', [TransformationController::class, 'show']);

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

    Route::get(
        '/member/profile',
        [MemberProfileController::class, 'index']
    )->name('member.profile');

    Route::post(
        '/member/profile',
        [MemberProfileController::class, 'update']
    )->name('member.profile.update');

});

Route::middleware(['auth', 'profile.completed'])->group(function () {

Route::get(
    '/member/my-plan',
    [MyPlanController::class, 'index']
)->name('member.my-plan');

    Route::get('/member/dashboard', [DashboardController::class, 'index'])
        ->name('member.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


Route::get(
    '/member/billing',
    [BillingController::class, 'index']
)->name('member.billing');



Route::get(
    '/member/progress',
    [ProgressController::class,'index']
)->name('member.progress');

Route::get(
    '/member/progress/create',
    [ProgressController::class,'create']
)->name('member.progress.create');

Route::post(
    '/member/progress',
    [ProgressController::class,'store']
)->name('member.progress.store');


Route::get(
    '/member/transformations',
    [MemberTransformationController::class, 'index']
)->middleware('auth')
 ->name('member.transformations');



Route::get(
    '/member/action-plan',
    [ActionPlanController::class, 'index']
)->middleware('auth')
 ->name('member.action-plan');

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

Route::get(
    '/checkout/product/{id}',
    [PurchaseController::class, 'checkout']
)->name('product.checkout');

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
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::get(
    '/product-payment/{id}',
    [App\Http\Controllers\Front\ProductPaymentController::class, 'checkout']
)
->middleware('auth')
->name('product.payment');

Route::post(
    '/product-payment-success',
    [App\Http\Controllers\Front\ProductPaymentController::class, 'success']
)
->middleware('auth')
->name('product.payment.success');

require __DIR__.'/auth.php';