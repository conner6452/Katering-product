<?php

use App\Enums\UserRole;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\PreOrderController;
use App\Http\Controllers\RuleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('auth')->group(function () {
    Route::post('login-admin', [AuthController::class, 'loginAdmin']);
    Route::post('login-mobile', [AuthController::class, 'loginMobile']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('update-forgot-password', [AuthController::class, 'updateForgotPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

//Role Admin
Route::middleware(['auth:sanctum', 'role:' . UserRole::ADMIN->value])->group(function () {
    //CRUD Ingredients
    Route::prefix('ingredients')->group(function () {
        Route::get('/slug/{slug}', [IngredientController::class, 'showBySlug']);

        Route::get('trash', [IngredientController::class, 'trash']);
        Route::post('{id}/restore', [IngredientController::class, 'restore']);
        Route::delete('{id}/force-delete', [IngredientController::class, 'forceDelete']);
    });
    Route::apiResource('ingredients', IngredientController::class);

    //rule (aturan)
    Route::prefix('rules')->group(function () {
        Route::get('trash', [RuleController::class, 'trash']);
        Route::post('{id}/restore', [RuleController::class, 'restore']);
        Route::delete('{id}/force-delete', [RuleController::class, 'forceDelete']);
    });
    Route::apiResource('rules', RuleController::class);

    //CRUD Suppliers
    Route::prefix('suppliers')->group(function () {
        Route::get('/slug/{slug}', [SupplierController::class, 'showBySlug']);

        Route::get('trash', [SupplierController::class, 'trash']);
        Route::post('{id}/restore', [SupplierController::class, 'restore']);
        Route::delete('{id}/force-delete', [SupplierController::class, 'forceDelete']);
    });
    Route::apiResource('suppliers', SupplierController::class);

    //CRUD Users
    Route::prefix('user-panels')->group(function () {
        Route::get('trash', [AuthController::class, 'trash']);
        Route::post('{id}/restore', [AuthController::class, 'restore']);
        Route::delete('{id}/force-delete', [AuthController::class, 'forceDelete']);
    });
    Route::apiResource('user-panels', AuthController::class);

    //CRUD driver
    Route::prefix('driver-panels')->group(function () {
        Route::get('trash', [AuthController::class, 'trash']);
        Route::post('{id}/restore', [AuthController::class, 'restore']);
        Route::delete('{id}/force-delete', [AuthController::class, 'forceDelete']);
    });
    Route::get('/driver-panels', [AuthController::class, 'indexDriver']);
    Route::post('/driver-panels', [AuthController::class, 'storeDriver']);
    Route::get('/driver-panels/{id}', [AuthController::class, 'showDriver']);
    Route::put('/driver-panels/{id}', [AuthController::class, 'updateDriver']);
    Route::delete('/driver-panels/{id}', [AuthController::class, 'destroyDriver']);

    //CRUD gudang
    Route::prefix('gudang-panels')->group(function () {
        Route::get('trash', [AuthController::class, 'trash']);
        Route::post('{id}/restore', [AuthController::class, 'restore']);
        Route::delete('{id}/force-delete', [AuthController::class, 'forceDelete']);
    });
    Route::get('/gudang-panels', [AuthController::class, 'indexGudang']);
    Route::post('/gudang-panels', [AuthController::class, 'storegudang']);
    Route::get('/gudang-panels/{id}', [AuthController::class, 'showgudang']);
    Route::put('/gudang-panels/{id}', [AuthController::class, 'updategudang']);
    Route::delete('/gudang-panels/{id}', [AuthController::class, 'destroygudang']);

    //CRUD Transaction (Admin, read and delete only)
    Route::prefix('transactions')->group(function () {
        Route::get('trash', [TransactionController::class, 'trash']);
        Route::post('{id}/restore', [TransactionController::class, 'restore']);
        Route::delete('{id}/force-delete', [TransactionController::class, 'forceDelete']);
    });
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::get('/transactions/{id}', [TransactionController::class, 'show']);
    Route::delete('/transactions/{id}', [TransactionController::class, 'delete']);
});

//Role Gudang
Route::middleware(['auth:sanctum', 'role:' . UserRole::GUDANG->value])->group(function () {
    //Pre-order
    Route::prefix('pre-orders')->group(function () {
        Route::get('trash', [PreOrderController::class, 'trash']);
        Route::post('{id}/restore', [PreOrderController::class, 'restore']);
        Route::delete('{id}/force-delete', [PreOrderController::class, 'forceDelete']);
    });
    Route::get('/pre-orders', [PreOrderController::class, 'index']);
    Route::post('/pre-orders', [PreOrderController::class, 'store']);
    Route::get('/pre-orders/{id}', [PreOrderController::class, 'show']);
    // Route::put('/pre-orders/{id}', [PreOrderController::class, 'update']);
    Route::delete('/pre-orders/{id}', [PreOrderController::class, 'destroy']);
});

Route::middleware(['auth:sanctum', 'role:' . UserRole::CUSTOMER->value])->group(function () {
    Route::apiResource('user/ingredients', IngredientController::class)->except(['store', 'update', 'destroy']);
});
