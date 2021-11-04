<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PhpMqtt\Client\Facades\MQTT;

use RealRashid\SweetAlert\Facades\Alert;

class MqttController extends Controller
{
    //
    function index()
    {
        // $data =Device::orderBy('device_id')->get();
        if(Session::get('success')){
            Alert::success("success",session('success'));
        }
        return view('mqtt');
    }

    function sendTopicAndMsg(Request $request)
    {
        $topic = $request->input("topic");
        $msg = $request->input("msg");
        MQTT::publish($topic, $msg);
        // Alert::alert('Title', 'Message', 'Type');
        // alert()->success('SuccessAlert','Lorem ipsum dolor sit amet.');
        // dd("111111");
        return redirect()->route('mqtt')->with('success','send mqtt success');
    }
}
