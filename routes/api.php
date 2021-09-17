<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PhpMqtt\Client\Facades\MQTT;
use App\Models\ZoomList;
use Carbon\Carbon;
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('mqtt', function (Request $request) {
    try {
        MQTT::publish('roundbot/connect', 'mac');
        return "ok";
    } catch (Exception $e) {
        return $e;
    }
});
Route::get('getDeviceId/{id}', [HomeController::class, 'getDeviceId']);
Route::post('saveUrl', [HomeController::class,'saveData']);
Route::get('listUrl', [HomeController::class,'getList']);

Route::get('date', function (Request $request) {

    $now_date = Carbon::now();

    $date1 =  date(Carbon::createFromFormat('Y-m-d H:i:s', $now_date, '+7')->format('d-m-Y'));

    return response()->json($date1);
});
