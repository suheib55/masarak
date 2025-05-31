<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\EventRegistrationController;
use App\Http\Controllers\RideController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\AnnouncedLinkController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\EmailVerificationController;
use Inertia\Inertia;

Route::get('/', function () {
    dd('Laravel شغال تمام ✅');
});

Route::get('/', function () {
    return Inertia::render('Home', [
       
    ]);
});

Route::get('/user-guide', function () {
    return Inertia::render('UserGuide');
});

Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');

Route::get('/contact', function () {
    return Inertia::render('Contact');
})->name('contact');



Route::get('/links', function () {
    return Inertia::render('Links');
})->name('links');

Route::get('/gpa', function () {
    return Inertia::render('GPA');
})->name('gpa');



Route::get('/map', function () {
    return Inertia::render('Map');
})->name('Map');



Route::get('/map/locations', function () {
    return Inertia::render('Map/Locations');
})->name('Map/Locations');


Route::get('/rideshare', [RideController::class, 'index'])->name('rideshare');



Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth'])->group(function () {
    Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::post('/materials', [MaterialController::class, 'store'])->name('materials.store');
    Route::delete('/materials/{id}', [MaterialController::class, 'destroy'])->name('materials.destroy');
});



Route::middleware(['auth'])->group(function () {
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::put('/announcements/{id}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

   
    Route::post('/announce-link', [AnnouncementController::class, 'storeLink'])->name('announcements.storeLink');
});



Route::middleware(['auth'])->group(function () {
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts/{post}/comment', [PostController::class, 'addComment'])->name('posts.comment');
    Route::post('/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::post('/events/register', [EventController::class, 'register'])->name('events.register');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/rides', [RideController::class, 'index'])->name('rides.index');
    Route::post('/rides', [RideController::class, 'store'])->name('rides.store');
    Route::post('/rides/join', [RideController::class, 'join'])->name('rides.join'); 
    Route::delete('/rides/{id}/passenger', [RideController::class, 'removePassenger'])->name('rides.passenger.remove');
     Route::delete('/rides/{id}', [RideController::class, 'deleteRide'])->name('rides.delete');

    Route::post('/announce-link', [AnnouncedLinkController::class, 'store'])->name('announce-link.store');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});






require __DIR__.'/auth.php';