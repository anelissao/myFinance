<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;

Route::get("/", function () {
    return view("home");
});

// Guest routes
Route::middleware("guest")->group(function () {
    Route::get("/register", [AuthController::class, "showRegister"])->name("register");
    Route::post("/register", [AuthController::class, "register"]);
    Route::get("/login", [AuthController::class, "showLogin"])->name("login");
    Route::post("/login", [AuthController::class, "login"]);
});

// Auth routes
Route::middleware("auth")->group(function () {
    Route::get("/dashboard", [AuthController::class, "dashboard"])->name("dashboard");
    Route::post("/logout", [AuthController::class, "logout"])->name("logout");
    
    // Transaction routes
    Route::get("/transactions", [TransactionController::class, "index"])->name("transactions.index");
    Route::get("/transactions/create", [TransactionController::class, "create"])->name("transactions.create");
    Route::post("/transactions", [TransactionController::class, "store"])->name("transactions.store");
});
