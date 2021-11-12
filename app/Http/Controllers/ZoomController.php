<?php

namespace App\Http\Controllers;

use App\Models\UserZoom;
use Illuminate\Http\Request;
use App\Models\ZoomList;
use Carbon\Carbon;
use App\Models\Device;
use App\Models\DownloadPath;
use App\Models\Meeting;
use App\Models\MinuteOfMeeting;
use App\Models\Team;
use App\Models\ZoomHost;
use PhpMqtt\Client\Facades\MQTT;
use App\Traits\ZoomJWT;
use Exception;
use Illuminate\Support\Facades\Response;
use Repsonse;
use Illuminate\Support\Facades\Storage;

class ZoomController extends Controller
{
    use ZoomJWT;

    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    function getUser()
    {
        $data = UserZoom::all();
        // $data =UserZoom::where('device_id',2)->get();
        return response()->json($data);
    }

    function getTeam()
    {
        $team = Team::with("user")->get();
        return response($team);
    }
    function postMeeting(Request $request)
    {
        $zoom = new ZoomList();
        $now_date = Carbon::now()->addHour(7);
        $time_start = date(Carbon::createFromFormat('Y-m-d H:i:s', $now_date)->format('H:i'));
        $time_end = date(Carbon::createFromFormat('Y-m-d H:i:s', $now_date)->addMinutes(30)->format('H:i'));
        $date =  date(Carbon::createFromFormat('Y-m-d H:i:s', $now_date, '+7')->format('d-m-Y'));
        $zoom->device_id = $request->input('device_id');
        $zoom->topic = $request->input('topic');
        $zoom->agenda = "AGENDA";
        $zoom->url = $request->input('url');
        $zoom->meeting_id = $request->input('meeting_id');
        $zoom->password = $request->input('meeting_password');
        $zoom->start_time = $time_start;
        $zoom->end_time = $time_end;
        $zoom->date = $date;

        $user_id = $request->input('user_id');

        try {
            $zoom->save();
            $zoomHost = new ZoomHost();
            $zoomHost->user_id = $user_id;
            $zoomHost->meeting_id = $zoom->id;
            $zoomHost->save();
            MQTT::publish('roundbot/connect', 'restart');
            return response()->json($zoom, 200);
        } catch (Exception $e) {
            return response()->json($e, 400);
        }
    }
    public function sendEndMeetingAndroid()
    {
        MQTT::publish('roundbot/connect', 'endMeeting');

        return response()->json("success", 200);
    }
    public function uploadFile(Request $request)
    {
        // $request->validate([
        //     'file' => 'required|mimes:pdf,xlx,csv|max:2048',
        // ]);
        $fileName = $request->input("meeting_id");

        // $request->file->move(public_path('uploads'), $fileName);
        $request->file('file')->storeAs('uploads', $fileName . ".csv", 'public');
        return response()->json("success", 200);
    }
    public function getMeetingRecent()
    {
        $zoomList = ZoomHost::with('user', 'zoom_list')->orderByDesc('id')->limit(10)->get();
        // dd($zoomList);

        return response($zoomList);
    }

    function createMeeting(Request $request)
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
                return response()->json(['url'=>$url,'meeting_id'=> $meetingId,'meeting_passcode'=> $passcode], 200);
            } catch (Exception $e) {
                return json_encode($e);
            }
        } else {
            return response()->json("can't create meeting");
        }
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

    public function downloadLink($fileName)
    {
        // $file = Storage::disk('public/uploads/')->get($fileName . ".csv");
        $file = Storage::disk('public')->path("uploads/" . $fileName . ".csv");
        // $content = file_get_contents($file);
        // $download_link = link_to_asset($file);
        // $filepath ='public/uploads/' . $fileName . ".csv";
        $path = new  DownloadPath();
        $path->url =  "https://zoom.ksta.co/api/download/" . $fileName;
        try {
            $path->save();
            return Response::download($file, $fileName . ".csv", ['Content-Length:' . filesize($file)]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }

    function saveMeeting(Request $request)
    {
        $meeting = new Meeting();

        $meeting->meeting_id = $request->input('meeting_id');
        $meeting->passcode = $request->input('passcode');
        $meeting->link = $request->input('link');
        $user_id = $request->input("user_id");
        try {
            $meeting->save();
            $zoomHost = new ZoomHost();
            $zoomHost->user_id = $user_id;
            $zoomHost->meeting_id = $meeting->id;
            $zoomHost->save();
            return response()->json("save  instant meeting", 200);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
    function saveMinuteOfMeeting(Request $request)
    {
        $minuteOfMeeting = new MinuteOfMeeting();
        $minuteOfMeeting->meeting_id = $request->input('meeting_id');
        $start = $request->input('time_start');
        $startH = explode(":", $start);
        $end = $request->input('time_stop');
        $time_zone = "Asia/Bangkok";
        $startTime = Carbon::createFromTime($startH[0], $startH[1], 0, $time_zone);
        $endH = explode(":", $end);
        // dd($endH[0]);
        $finishTime = Carbon::createFromTime($endH[0], $endH[1], 0, $time_zone);
        $total = $finishTime->diffInMinutes($startTime);
        $minuteOfMeeting->minute = $total;
        try {
            $minuteOfMeeting->save();
            return response("save Minute");
        } catch (Exception $e) {
            return response("can't save right now: " . $e);
        }
    }

    function saveUser(Request $request)
    {
        $user = new UserZoom();
        $user->name =  $request->input('name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->token_line = $request->input('token_line');
        try {
            $user->save();
            return response()->json("save user zoom ", 200);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }
}
