<?php

use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Frontend\BlogController as FrontendBlogController;
use App\Http\Controllers\Frontend\FavoriteController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('blog.index');
});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::middleware(IsAdmin::class)->group(function () {
        Route::resource('admin/blogs',BlogController::class);
        Route::get('blogs_trash',[BlogController::class,'trash'])->name('blogs_trash');
        Route::post('blogs_restore/{bolg}',[BlogController::class,'restore'])->name('blogs_restore');
        Route::delete('blogs_forceDelete/{bolg}',[BlogController::class,'forceDelete'])->name('blogs_forceDelete');
        
        Route::resource('categories',CategoryController::class);
    });
    
    Route::post('/favorites/{blog}/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::delete('/favorites/{blog}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
});

require __DIR__.'/auth.php';

Route::resource('blog',FrontendBlogController::class);