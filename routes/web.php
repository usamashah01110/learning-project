<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('payment');
});

Route::post('/create-payment', [PaymentController::class, 'createPayment']);


Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/posts',[PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create',[PostController::class, 'create'])->name('posts.create');
    Route::post('/posts/store',[PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/edit/{id}',[PostController::class, 'edit'])->name('posts.edit');
    Route::get('/posts/delete/{id}',[PostController::class, 'delete'])->name('posts.delete');
});

require __DIR__.'/auth.php';
