<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\ZoomList;
use App\Models\Device;
use DB;
use PhpMqtt\Client\Facades\MQTT;

use App\Traits\ZoomJWT;
use Exception;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    use ZoomJWT;

    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    function getList()
    {
        return response()->json(ZoomList::all());
    }

    function index()
    {
        $now_date = Carbon::now();
        $date =  date(Carbon::createFromFormat('Y-m-d H:i:s', $now_date, '+7')->format('d-m-Y'));
        $data = ZoomList::where('date', $date)->get();
        $device = Device::all();
        return view('home', ['data' => $data], ['date' => $date],['device'=>$device]);
    }




    function getDeviceId($id)
    {
        $now_date = Carbon::now();
        $time =  date(Carbon::createFromFormat('Y-m-d H:i:s', $now_date, '+7')->addHour(7)->format('H:i'));
        $date  = date(Carbon::createFromFormat('Y-m-d H:i:s', $now_date, '+7')->format('d-m-Y'));
        $device =  ZoomList::where('device_id', $id)->where('date',"=",$date)->where('start_time', ">=", $time)->orderBy('start_time')->get();

        return $device;
    }

    function saveData(Request $request)
    {
        $zoom = new ZoomList();
        $now_date = Carbon::now();
        $zoom->device_id = $request->input('device_id');
        $zoom->end_time = $request->input('end_time');
        $date =  date(Carbon::createFromFormat('Y-m-d H:i:s', $now_date, '+7')->format('d-m-Y'));
        $dateZoom = date(Carbon::createFromFormat('Y-m-d H:i:s', $now_date, '+7')->format('Y-m-d'));
        $zoom->topic = $request->input('topic');
        $zoom->agenda = $request->input('agenda');
        $zoom->start_time = $request->input('start_time');
        $dateZoom .= $zoom->start_time;
        $zoom->date = $date;

        $dataDevice = Device::where('device_id', $zoom->device_id)->first();
        // dd($dataDevice);
        if ($zoom->device_id == $dataDevice['device_id']) {
            $apiKey = $dataDevice['zoom_api_key'];
            $apiSecret = $dataDevice['zoom_api_secret'];

            $responseZoom = $this->create($zoom->topic, $zoom->agenda, $dateZoom, $apiKey, $apiSecret);
            // dd($responseZoom);
            $url = $responseZoom['data']['join_url'];
            $meetingId = $responseZoom['data']['id'];
            $passcode = "";
            try {
                $passcode = $responseZoom['data']['password'];
            } catch (Exception $e) {
                $passcode = "-";
            }
            $zoom->meeting_id = $meetingId;
            $zoom->password = $passcode;
            $zoom->url = $url;
            try {
                $zoom->save();
                MQTT::publish('roundbot/connect', 'restart');
                return redirect('/')->with('success', 'Item created successfully!');
            } catch (Exception $e) {
                return json_encode($e);
            }
        } else {
            return response()->json("can't create meeting");
        }
    }



    public function list(Request $request)
    {
        $path = 'users/me/meetings';
        $response = $this->zoomGet($path, [], "", "");

        $data = json_decode($response->body(), true);
        // dd($response->body());
        $data['meetings'] = array_map(function (&$m) {
            $m['start_at'] = $this->toUnixTimeStamp($m['start_time'], $m['timezone']);
            return $m;
        }, $data['meetings']);
        return [
            'success' => $response->ok(),
            'data' => $data,
        ];
    }
    function create($topic, $agenda, $start_time, $apiKey, $apiSecret)
    {
        // $validator = Validator::make($request->all(), [
        //     'topic' => 'required|string',
        //     'start_time' => 'required|date',
        //     'agenda' => 'string|nullable',
        // ]);

        // if ($validator->fails()) {
        //     return [
        //         'success' => false,
        //         'data' => $validator->errors(),
        //     ];
        // }
        // $data = $validator->validated();

        $path = 'users/me/meetings';
        $response = $this->zoomPost($path, [
            'topic' => $topic,
            'type' => self::MEETING_TYPE_SCHEDULE,
            'start_time' => $this->toZoomTimeFormat($start_time),
            'duration' => 30,
            'agenda' => $agenda,
            'settings' => [
                'host_video' => true,
                'participant_video' => false,
                'waiting_room' => true,
                'pre_schedule' => true,
                'join_before_host' => true,
                'jbh_time' => 0,
                'auto_recording' => 'cloud',

            ]
        ], $apiKey, $apiSecret);


        return [
            'success' => $response->status() === 201,
            'data' => json_decode($response->body(), true),
        ];
    }
    public function getMeeting($meetingId)
    {
        $apiKey = 'VuANSSOGRqyo-qxmwZ3ARg';
        $apiSecret = '1shorqaOOwUVpA5ijRW2r9JGwgBiUqOshq0v';
        $id = $meetingId;
        $path = 'meetings/' . $id . '/recordings';
        $response = $this->zoomGet($path, [], $apiKey, $apiSecret);

        $token = $this->token;
        // dd($response);
        // dd($response->transferStats);
        // $head = json_decode($response->get('transferStats'));
        // dd($head);
        $data = json_decode($response->body(), true);
        // if ($response->ok()) {
        //     $data['start_at'] = $this->toUnixTimeStamp($data['start_time'], $data['timezone']);
        // }
        $downloadMp4Link = $data['recording_files'][0]['download_url'];

        return [
            'response' => $response,
            'success' => $response->ok(),
            'download' => $downloadMp4Link,
            'data' => $data,
            'token' => $token

        ];
    }
    public function get(Request $request, string $id)
    {
        $apiKey = 'En87hUU1Rn-B0in6-9x7SA';
        $apiSecret = 'nRjasgBIoA75inRlYqvo4mESB4PEoShfp46Y';
        $path = 'meetings/' . $id;
        $response = $this->zoomGet($path, [],  $apiKey, $apiSecret);

        $data = json_decode($response->body(), true);
        if ($response->ok()) {
            $data['start_at'] = $this->toUnixTimeStamp($data['start_time'], $data['timezone']);
        }

        return [
            'success' => $response->ok(),
            'data' => $data,
        ];
    }
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|string',
            'start_time' => 'required|date',
            'agenda' => 'string|nullable',
        ]);

        if ($validator->fails()) {
            return [
                'success' => false,
                'data' => $validator->errors(),
            ];
        }
        $data = $validator->validated();

        $path = 'meetings/' . $id;
        $response = $this->zoomPatch($path, [
            'topic' => $data['topic'],
            'type' => self::MEETING_TYPE_SCHEDULE,
            'start_time' => (new \DateTime($data['start_time']))->format('Y-m-d\TH:i:s'),
            'duration' => 30,
            'agenda' => $data['agenda'],
            'settings' => [
                'host_video' => false,
                'participant_video' => false,
                'waiting_room' => true,

            ]
        ], "", "");

        return [
            'success' => $response->status() === 204,
            'data' => json_decode($response->body(), true),
        ];
    }
    public function delete(Request $request, string $id)
    {
        $path = 'meetings/' . $id;
        $response = $this->zoomDelete($path, [], "", '');

        return [
            'success' => $response->status() === 204,
            'data' => json_decode($response->body(), true),
        ];
    }
}
