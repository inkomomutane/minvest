<?php

use App\Http\Controllers\Users\ListUsers;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard/list/users', ListUsers::class)->name('ListUsers');
});
