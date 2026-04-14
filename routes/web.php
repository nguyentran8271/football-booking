<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\FieldController as AdminFieldController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\FieldController as OwnerFieldController;
use App\Http\Controllers\Owner\BookingController as OwnerBookingController;
use App\Http\Controllers\Owner\TournamentController as OwnerTournamentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Trang công khai
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tin-tuc/load-more', [HomeController::class, 'loadMorePosts'])->name('posts.load-more');
Route::get('/tin-tuc/{id}', [\App\Http\Controllers\PostController::class, 'show'])->name('posts.show');
Route::get('/gioi-thieu', [HomeController::class, 'about'])->name('about');
Route::get('/chinh-sach', [HomeController::class, 'policy'])->name('policy');
Route::get('/danh-cho-chu-san', [HomeController::class, 'forOwners'])->name('for-owners');

// Danh sách sân
Route::get('/san-bong', [FieldController::class, 'index'])->name('fields.index');
Route::get('/san-bong/{id}', [FieldController::class, 'show'])->name('fields.show');

// Giải đấu
Route::get('/giai-dau', [TournamentController::class, 'index'])->name('tournaments.index');
Route::get('/giai-dau/{id}', [TournamentController::class, 'show'])->name('tournaments.show');

// Đánh giá
Route::get('/danh-gia', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews/{id}/helpful', [ReviewController::class, 'markHelpful'])->name('reviews.helpful');

// Routes yêu cầu đăng nhập
Route::middleware(['auth'])->group(function () {
    // Đặt sân
    Route::get('/dat-san/{field}', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/dat-san', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/lich-su-dat-san', [BookingController::class, 'history'])->name('bookings.history');
    Route::post('/huy-dat-san/{id}', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Gửi đánh giá
    Route::post('/danh-gia', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/danh-gia/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/danh-gia/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Đăng ký làm chủ sân
    Route::post('/dang-ky-chu-san', [\App\Http\Controllers\OwnerRequestController::class, 'store'])->name('owner-request.store');

    // Đăng ký giải đấu
    Route::get('/giai-dau/{id}/dang-ky', [TournamentController::class, 'register'])->name('tournaments.register');
    Route::post('/giai-dau/{id}/dang-ky', [TournamentController::class, 'storeRegistration'])->name('tournaments.register.store');
});

// Routes cho Owner
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

    // Quản lý sân
    Route::resource('fields', OwnerFieldController::class);

    // Quản lý booking
    Route::get('/bookings', [OwnerBookingController::class, 'index'])->name('bookings.index');
    Route::patch('/bookings/{id}/confirm', [OwnerBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::patch('/bookings/{id}/cancel', [OwnerBookingController::class, 'cancel'])->name('bookings.cancel');

    // Quản lý giải đấu
    Route::resource('tournaments', OwnerTournamentController::class);
    Route::post('/tournaments/{tournament}/teams/{team}/approve', [OwnerTournamentController::class, 'approveTeam'])->name('tournaments.teams.approve');
    Route::post('/tournaments/{tournament}/teams/{team}/reject', [OwnerTournamentController::class, 'rejectTeam'])->name('tournaments.teams.reject');
});

// Routes cho Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/reviews/mark-read', function() {
        \App\Models\Review::whereNull('field_id')->where('is_read', false)->update(['is_read' => true]);
        return back();
    })->name('admin.reviews.mark-read');

    // Quản lý users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('/users/{id}/convert-to-owner', [AdminUserController::class, 'convertToOwner'])->name('users.convert-to-owner');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Quản lý owners
    Route::get('/owners', [AdminUserController::class, 'owners'])->name('owners.index');
    Route::get('/owners/{id}', [AdminUserController::class, 'ownerShow'])->name('owners.show');
    Route::post('/owners/{id}/approve', [AdminUserController::class, 'approveOwner'])->name('owners.approve');
    Route::post('/owners/{id}/reject', [AdminUserController::class, 'rejectOwner'])->name('owners.reject');

    // Quản lý sân
    Route::get('/fields', [AdminFieldController::class, 'index'])->name('fields.index');
    Route::delete('/fields/{id}', [AdminFieldController::class, 'destroy'])->name('fields.destroy');

    // Quản lý booking
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::delete('/bookings/{id}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');

    // Quản lý bài viết
    Route::post('/posts/upload-image', [AdminPostController::class, 'uploadImage'])->name('posts.upload-image');
    Route::resource('posts', AdminPostController::class);

    // Quản lý đánh giá
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Cài đặt website
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    // Quản lý nội dung trang chủ
    Route::get('/home-content', [App\Http\Controllers\Admin\HomeContentController::class, 'index'])->name('home-content.index');
    Route::post('/home-content/cards', [App\Http\Controllers\Admin\HomeContentController::class, 'storeCard'])->name('home-content.cards.store');
    Route::put('/home-content/cards/{id}', [App\Http\Controllers\Admin\HomeContentController::class, 'updateCard'])->name('home-content.cards.update');
    Route::delete('/home-content/cards/{id}', [App\Http\Controllers\Admin\HomeContentController::class, 'deleteCard'])->name('home-content.cards.delete');
    Route::post('/home-content/stats', [App\Http\Controllers\Admin\HomeContentController::class, 'storeStat'])->name('home-content.stats.store');
    Route::put('/home-content/stats/{id}', [App\Http\Controllers\Admin\HomeContentController::class, 'updateStat'])->name('home-content.stats.update');
    Route::delete('/home-content/stats/{id}', [App\Http\Controllers\Admin\HomeContentController::class, 'deleteStat'])->name('home-content.stats.delete');
    Route::post('/home-content/fields', [App\Http\Controllers\Admin\HomeContentController::class, 'storeField'])->name('home-content.fields.store');
    Route::put('/home-content/fields/{id}', [App\Http\Controllers\Admin\HomeContentController::class, 'updateField'])->name('home-content.fields.update');
    Route::delete('/home-content/fields/{id}', [App\Http\Controllers\Admin\HomeContentController::class, 'deleteField'])->name('home-content.fields.delete');
    Route::post('/home-content/about-sections', [App\Http\Controllers\Admin\HomeContentController::class, 'storeAboutSection'])->name('home-content.about-sections.store');
    Route::put('/home-content/about-sections/{id}', [App\Http\Controllers\Admin\HomeContentController::class, 'updateAboutSection'])->name('home-content.about-sections.update');
    Route::delete('/home-content/about-sections/{id}', [App\Http\Controllers\Admin\HomeContentController::class, 'deleteAboutSection'])->name('home-content.about-sections.delete');
    Route::post('/home-content/about-stats', [App\Http\Controllers\Admin\HomeContentController::class, 'storeAboutStat'])->name('home-content.about-stats.store');
    Route::put('/home-content/about-stats/{id}', [App\Http\Controllers\Admin\HomeContentController::class, 'updateAboutStat'])->name('home-content.about-stats.update');
    Route::delete('/home-content/about-stats/{id}', [App\Http\Controllers\Admin\HomeContentController::class, 'deleteAboutStat'])->name('home-content.about-stats.delete');

    // Cài đặt website
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');

    // Owner Page trong Settings
    Route::post('/settings/owner-stats', [AdminSettingController::class, 'storeOwnerStat'])->name('settings.owner-stats.store');
    Route::post('/settings/owner-stats/{id}', [AdminSettingController::class, 'updateOwnerStat'])->name('settings.owner-stats.update');
    Route::delete('/settings/owner-stats/{id}', [AdminSettingController::class, 'deleteOwnerStat'])->name('settings.owner-stats.delete');
    Route::post('/settings/owner-benefits', [AdminSettingController::class, 'storeOwnerBenefit'])->name('settings.owner-benefits.store');
    Route::post('/settings/owner-benefits/{id}', [AdminSettingController::class, 'updateOwnerBenefit'])->name('settings.owner-benefits.update');
    Route::delete('/settings/owner-benefits/{id}', [AdminSettingController::class, 'deleteOwnerBenefit'])->name('settings.owner-benefits.delete');
    Route::post('/settings/owner-steps', [AdminSettingController::class, 'storeOwnerStep'])->name('settings.owner-steps.store');
    Route::post('/settings/owner-steps/{id}', [AdminSettingController::class, 'updateOwnerStep'])->name('settings.owner-steps.update');
    Route::delete('/settings/owner-steps/{id}', [AdminSettingController::class, 'deleteOwnerStep'])->name('settings.owner-steps.delete');
    Route::post('/settings/owner-sections', [AdminSettingController::class, 'storeOwnerSection'])->name('settings.owner-sections.store');
    Route::post('/settings/owner-sections/{id}', [AdminSettingController::class, 'updateOwnerSection'])->name('settings.owner-sections.update');
    Route::delete('/settings/owner-sections/{id}', [AdminSettingController::class, 'deleteOwnerSection'])->name('settings.owner-sections.delete');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () { Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit'); Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update'); });
