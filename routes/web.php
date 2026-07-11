<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Public\ProgramPublicController;
use Illuminate\Support\Facades\Route;

// 1. Central Welcome Page Routing (Only matches if tenant program context is not resolved)
Route::get('/', function () {
    if (app()->bound(\App\Models\Program::class)) {
        return app(ProgramPublicController::class)->home();
    }
    return redirect()->route('login');
})->middleware('tenant');

// 2. Auth and General Dashboard Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/admin/select-program', [DashboardController::class, 'selectProgram'])->name('admin.select-program');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 3. Super Admin CRUD Panel
Route::middleware(['auth', 'role:super-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('programs', \App\Http\Controllers\SuperAdmin\ProgramController::class);
});

// 4. Program Content Management System (CMS) Panel
Route::middleware(['auth', 'role:super-admin|program-admin|editor|contributor'])->prefix('cms')->name('cms.')->group(function () {
    Route::resource('users', \App\Http\Controllers\ProgramAdmin\UserController::class);
    Route::resource('contents', \App\Http\Controllers\ProgramAdmin\ContentController::class);
    Route::resource('categories', \App\Http\Controllers\ProgramAdmin\CategoryController::class);
    
    // Dynamic Content Managers
    Route::resource('periods', \App\Http\Controllers\ProgramAdmin\PeriodController::class);
    Route::resource('managements', \App\Http\Controllers\ProgramAdmin\ManagementController::class);
    Route::resource('albums', \App\Http\Controllers\ProgramAdmin\AlbumController::class);
    Route::post('albums/{album}/upload-photo', [\App\Http\Controllers\ProgramAdmin\AlbumController::class, 'uploadPhoto'])->name('albums.upload-photo');
    Route::delete('albums/photos/{photo}', [\App\Http\Controllers\ProgramAdmin\AlbumController::class, 'deletePhoto'])->name('albums.delete-photo');
    Route::resource('documents', \App\Http\Controllers\ProgramAdmin\DocumentController::class);
    
    // Theme Customizer
    Route::get('/theme', [\App\Http\Controllers\ProgramAdmin\ThemeController::class, 'edit'])->name('theme.edit');
    Route::patch('/theme', [\App\Http\Controllers\ProgramAdmin\ThemeController::class, 'update'])->name('theme.update');
});

// 5. Public API Endpoint (Open API)
Route::get('/api/articles', [\App\Http\Controllers\Api\ArticleApiController::class, 'index'])->name('api.articles.index');

// 6. Public Subsite Nested Routes
$defineProgramRoutes = function () {
    Route::get('/', [ProgramPublicController::class, 'home'])->name('program.home');
    Route::get('/news', [ProgramPublicController::class, 'news'])->name('program.news');
    Route::get('/management', [ProgramPublicController::class, 'management'])->name('program.management');
    Route::get('/gallery', [ProgramPublicController::class, 'gallery'])->name('program.gallery');
    Route::get('/documents', [ProgramPublicController::class, 'documents'])->name('program.documents');
    
    // Details
    Route::get('/page/{slug}', [ProgramPublicController::class, 'page'])->name('program.page');
    Route::get('/post/{slug}', [ProgramPublicController::class, 'post'])->name('program.post');
    Route::get('/announcement/{slug}', [ProgramPublicController::class, 'announcement'])->name('program.announcement');
    Route::get('/event/{slug}', [ProgramPublicController::class, 'event'])->name('program.event');
    Route::get('/category/{slug}', [ProgramPublicController::class, 'category'])->name('program.category');
};

// Check if accessing subsite subdomain or custom domain
$host = request()->getHost();
$baseDomains = ['localhost', '127.0.0.1', 'domain.com', 'profil.instituthijauindonesia.or.id'];
$isSubsiteDomain = true;
foreach ($baseDomains as $baseDomain) {
    if ($host === $baseDomain || $host === 'www.' . $baseDomain) {
        $isSubsiteDomain = false;
    }
}

// Route group A: Domain-based / Subdomain-based routing for resolved sub-site contexts
if ($isSubsiteDomain && !app()->runningInConsole()) {
    Route::middleware(['tenant'])->group($defineProgramRoutes);
} else {
    // Route group B: Path-based fallback for local dev and direct access
    Route::middleware(['tenant'])->prefix('p/{program_slug}')->group($defineProgramRoutes);
}

require __DIR__.'/auth.php';
