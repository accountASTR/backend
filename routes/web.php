<?php

use App\Http\Controllers\API\v1\Dashboard\Payment\StripeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('iyzico-3d',       [StripeController::class, 'iyzico3d']);
Route::any('payment-success', [StripeController::class, 'resultTransaction']);

Route::get('/', function () {
    return view('welcome');
});  // Added closing parenthesis here

Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return 'Database connection is working!';
    } catch (\Exception $e) {
        return 'Could not connect to the database. Error: ' . $e->getMessage();
    }
});