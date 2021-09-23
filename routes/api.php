<?php

use App\Http\Controllers\DeviceController;
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
Route::post('saveDevice',[DeviceController::class,'saveDevice']);
Route::get('getDevice',[DeviceController::class,'getDevice']);

Route::get('date', function (Request $request) {

    $now_date = Carbon::now();

    // $date1 =  date(Carbon::createFromFormat('Y-m-d H:i:s', $now_date, '+7')->format('d-m-Y'));
    $date1 = date(Carbon::createFromFormat('Y-m-d H:i:s', $now_date, '+7')->format('Y-m-d'));
    $date1 .= "17:30";
    // $date2 = $date1 + $dateTime;

    return response()->json($date1);
});

Route::post('date', function (Request $request) {

    $data =  $request->input('date');
    $now_date = Carbon::now();
    $sendDate = Carbon::createFromFormat('Y-m-d H',$data);
    // $date1 =  date(Carbon::createFromFormat('Y-m-d H:i:s', $now_date, '+7')->format('d-m-Y'));

    return response()->json($sendDate);
});


Route::get('meetings', [HomeController::class,'list']);

// Create meeting room using topic, agenda, start_time.
Route::post('meetings', [HomeController::class,'create']);

// Get information of the meeting room by ID.
Route::get('meetings/{id}', [HomeController::class,'get'])->where('id', '[0-9]+');

Route::patch('meetings/{id}', [HomeController::class,'update'])->where('id', '[0-9]+');
Route::delete('meetings/{id}', [HomeController::class,'delete'])->where('id', '[0-9]+');
