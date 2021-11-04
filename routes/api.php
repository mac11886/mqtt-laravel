<?php

use App\Http\Controllers\DeviceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PhpMqtt\Client\Facades\MQTT;
use App\Models\ZoomList;
use Carbon\Carbon;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ZoomController;
use App\Http\Controllers\MqttController;
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

Route::get('getDeviceId/{id}', [HomeController::class, 'getDeviceId']);
Route::post('saveUrl', [HomeController::class, 'saveData']);
Route::get('listUrl', [HomeController::class, 'getList']);
Route::post('saveDevice', [DeviceController::class, 'saveDevice']);
Route::get('getDevice', [DeviceController::class, 'getDevice']);
Route::post('endMeetingAndroid', [ZoomController::class, 'sendEndMeetingAndroid']);
Route::get('userHost', [ZoomController::class, 'getUser']);
Route::post('createInstant', [ZoomController::class, 'postMeeting']);
Route::post('saveUserHost', [ZoomController::class, 'saveUser']);
Route::post('saveMeeting', [ZoomController::class, 'saveMeeting']);
Route::post('file-upload', [ZoomController::class, 'uploadFile']);
Route::get('download/{filename}', [ZoomController::class, 'downloadLink']);
Route::get('getTeam', [ZoomController::class, 'getTeam']);
Route::post('saveMinute', [ZoomController::class, 'saveMinuteOfMeeting']);
Route::get('getMeetingRecently', [ZoomController::class, 'getMeetingRecent']);

Route::get('recording/{meetingId}', [HomeController::class, 'getMeeting']);

//MQTT
Route::post('mqtt', [MqttController::class, 'sendTopicAndMsg']);

Route::get('meetings', [HomeController::class, 'list']);
// Create meeting room using topic, agenda, start_time.
Route::post('meetings', [HomeController::class, 'create']);

// Get information of the meeting room by ID.
Route::get('meetings/{id}', [HomeController::class, 'get'])->where('id', '[0-9]+');

Route::patch('meetings/{id}', [HomeController::class, 'update'])->where('id', '[0-9]+');
Route::delete('meetings/{id}', [HomeController::class, 'delete'])->where('id', '[0-9]+');
