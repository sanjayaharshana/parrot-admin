<?php

use Illuminate\Support\Facades\Route;
use Modules\Products\Http\Controllers\ProductsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {

});
