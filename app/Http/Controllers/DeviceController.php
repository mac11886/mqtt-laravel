<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Exception;
use Illuminate\Http\Request;

class DeviceController extends Controller
{

    function index()
    {
        $data =Device::all();
        return view('device', ['data' => $data]);
    }

    function getDevice(){
        // $data =Device::all();
        $dataDevice =Device::where('device_id',2)->get();
        return response()->json($dataDevice);
    }


    function saveDevice(Request $request){
        $device = new Device();

        $device->device_id = $request->input('device_id');
        $device->name = $request->input('name');
        $device->location= $request->input('location');
        $device->zoom_email= $request->input('zoom_email');
        $device->zoom_api_key =$request->input('zoom_api_key');
        $device->zoom_api_secret =$request->input('zoom_api_secret');


        try{
            $device->save();
            return redirect()->route('home')->with("success");
        }
        catch(Exception $e){
            return $e;
        }

    }
}
