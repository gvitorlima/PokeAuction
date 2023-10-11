<?php

use App\Http\Controllers\AuctionController;
use Illuminate\Support\Facades\Route;

Route::get("/", [AuctionController::class, "getDailyAuction"])->name("getDailyAuction");
