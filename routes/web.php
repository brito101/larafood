<?php

use App\Http\Controllers\Admin\{
    PlanController,
    PlanDetailController,
};
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {

    Route::resource("plans/{id}/details", PlanDetailController::class);

    Route::resource("plans", PlanController::class);

});


Route::get('/', function () {
    return view('welcome');
});
