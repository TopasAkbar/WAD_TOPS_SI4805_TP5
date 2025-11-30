<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BooksController;

/**
 * ==========1===========
 * Unprotected routes for user registration and login
 * These routes can be accessed without authentication
 */
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

/**
 * =========2===========
 * Protected routes, only accessible with a valid token
 * These routes are protected by Sanctum authentication
 */
Route::middleware('auth:sanctum')->group(function () {

    /**
     * =========3===========
     * User logout route
     * Logs the user out by invalidating the current token
     */
    Route::post('logout', [AuthController::class, 'logout']);

    /**
     * =========4===========
     * Books CRUD routes (Create, Read, Update, Delete)
     * Uses API resource controller for CRUD operations
     */
    Route::apiResource('books', BooksController::class);

    /**
     * =========5===========
     * Borrow or return books route
     * This route toggles the availability of a book (borrow/return)
     */
    Route::put('books/{id}/borrow-return', [BooksController::class, 'borrowReturn']);
});
