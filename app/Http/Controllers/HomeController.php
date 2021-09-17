<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\ZoomList;
use DB;
use PhpMqtt\Client\Facades\MQTT;

class HomeController extends Controller
{

    function getList()
    {
        return response()->json(ZoomList::all());
    }

    function index()
    {
        $data = DB::select('select * from zoom_list');
        return view('home', ['data' => $data]);
    }

    function getDeviceId($id)
    {
        $now_date = Carbon::now();
        $time =  date(Carbon::createFromFormat('Y-m-d H:i:s', $now_date, '+7')->addHour(7)->format('H:i'));
        $device =  ZoomList::where('device_id', $id)->where('start_time', ">=", $time)->get();
        // $data =
        // $date1 = $now_date->addHours(7);

        return $device;
    }

    function saveData(Request $request)
    {
        $zoom = new ZoomList();
        $now_date = Carbon::now();
        $date =  date(Carbon::createFromFormat('Y-m-d H:i:s', $now_date, '+7')->format('d-m-Y'));
        $zoom->device_id = $request->input('device_id');
        $zoom->url = $request->input('url');
        $zoom->start_time = $request->input('start_time');
        $zoom->end_time = $request->input('end_time');
        $zoom->date = $date;
        try {
            $zoom->save();
            MQTT::publish('roundbot/connect', 'restart');
            return redirect('/');
        } catch (Exception $e) {
            return json_encode($e);
        }
    }
}
