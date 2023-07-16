<?php

use App\Http\Controllers\Admin\{
    PlanController,
};
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::resource("plans", PlanController::class);
});


Route::get('/', function () {
    return view('welcome');
});
