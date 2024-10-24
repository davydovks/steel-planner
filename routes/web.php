<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\StructureController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Users

Route::get('users', [UsersController::class, 'index'])
    ->name('users')
    ->middleware('auth');

Route::get('users/create', [UsersController::class, 'create'])
    ->name('users.create')
    ->middleware('auth');

Route::post('users', [UsersController::class, 'store'])
    ->name('users.store')
    ->middleware('auth');

Route::get('users/{user}/edit', [UsersController::class, 'edit'])
    ->name('users.edit')
    ->middleware('auth');

Route::put('users/{user}', [UsersController::class, 'update'])
    ->name('users.update')
    ->middleware('auth');

Route::delete('users/{user}', [UsersController::class, 'destroy'])
    ->name('users.destroy')
    ->middleware('auth');

Route::put('users/{user}/restore', [UsersController::class, 'restore'])
    ->name('users.restore')
    ->middleware('auth');

// Customers

Route::resource('customers', CustomerController::class)
    ->withTrashed()
    ->middleware('auth');

Route::put('customers/{customer}/restore', [CustomerController::class, 'restore'])
    ->withTrashed()
    ->name('customers.restore')
    ->middleware('auth');

// Orders

Route::resource('orders', OrderController::class)
    ->withTrashed()
    ->middleware('auth');

Route::get('orders/create/{customer_id?}', [OrderController::class, 'create'])
    ->name('orders.create')
    ->middleware('auth');

Route::put('orders/{order}/restore', [OrderController::class, 'restore'])
    ->withTrashed()
    ->name('orders.restore')
    ->middleware('auth');

// Structures

Route::resource('structures', StructureController::class)
    ->withTrashed()
    ->middleware('auth');

Route::get('structures/create/{order_id?}', [StructureController::class, 'create'])
    ->name('structures.create')
    ->middleware('auth');

Route::put('structures/{structure}/restore', [StructureController::class, 'restore'])
    ->withTrashed()
    ->name('structures.restore')
    ->middleware('auth');

// Parts

Route::resource('parts', PartController::class)
    ->withTrashed()
    ->middleware('auth');

Route::get('parts/create/{order_id?}', [PartController::class, 'create'])
    ->name('parts.create')
    ->middleware('auth');

Route::put('parts/{part}/restore', [PartController::class, 'restore'])
    ->withTrashed()
    ->name('parts.restore')
    ->middleware('auth');

// Production

Route::get('production', [ProductionController::class, 'index'])
    ->name('production')
    ->middleware('auth');
