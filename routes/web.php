<?php

use App\Data\IconData;
use App\Http\Controllers\Icons\SearchIconControllerJson;
use App\Models\Icon;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard', [
        'icons' => IconData::collect(Icon::take(14)->get()),
    ])->name('dashboard');

    Route::get('icons/search', SearchIconControllerJson::class)->name('iconSearch');
});

require __DIR__.'/settings.php';
require __DIR__.'/system/users.php';
