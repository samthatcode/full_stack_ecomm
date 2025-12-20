<?php

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\DashboardController;

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::middleware(['role:super_admin'])->prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index']);

        Route::prefix('products')->group(function () {
            Route::post('/create_products', [ProductController::class, 'createProduct']);
            Route::post('/{produt_id}/update_product', [ProductController::class, 'updateProduct']);
            Route::post('/{product_id}/delete_product', [ProductController::class, 'deleteProduct']);
        });
        Route::prefix('category')->group(function () {
            Route::post('/create_product_category', [CategoryController::class, 'createProductCategory']);
            Route::post('/{category_id}/get_single_category', [CategoryController::class, 'getSingleCategory']);
            Route::post('/{category_id}/update_category', [CategoryController::class, 'updateCategory']);
            Route::post('/{category_id}/delete_category', [CategoryController::class, 'deleteCategory']);
        });

        Route::prefix('orders')->group(function () {
            Route::post('/get_customer_orders', [OrderController::class, 'getCustomerOrders']);
            Route::get('/{order_id}/get_single_order', [OrderController::class, 'getSingleOrder']);
            Route::put('/{order_id}/update_order_status', [OrderController::class, 'updateOrderStatus']);
        });

        Route::prefix('users')->group(function () {
            Route::get('/get_all_users', [UserRoleController::class, 'getAllUsers']);
            Route::get('/{user_id}/get_single_user', [UserRoleController::class, 'getSingleUser']);
            Route::post('/{user_id}/assign_roles', [UserRoleController::class, 'assignRolesToUser']);
            Route::post('/{user_id}/assign_permissions', [UserRoleController::class, 'assignPermissionsToUser']);
            Route::delete('/{user_id}/remove_role', [UserRoleController::class, 'removeRoleFromUser']);
            Route::delete('/{user_id}/remove_permission', [UserRoleController::class, 'removePermissionFromUser']);
        });

        // Roles and Permissions Routes
        Route::get('/roles', [UserRoleController::class, 'getAllRoles']);
        Route::get('/permissions', [UserRoleController::class, 'getAllPermissions']);
    });
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/forgot_password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset_password', [AuthController::class, 'resetPassword']);
    Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
});


// Public Routes
Route::prefix('products')->group(function () {
    Route::get('/get_all_products', [ProductController::class, 'getAllProducts']);
    Route::get('/{product_id}/get_single_product', [ProductController::class, 'getSingleProduct']);
});
Route::prefix('category')->group(function () {
    Route::get('/get_all_categories', [CategoryController::class, 'getProductCategory']);
});
