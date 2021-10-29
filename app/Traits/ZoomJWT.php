<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait ZoomJWT
{
    public  $token = "";
    public function generateZoomToken($Zoomkey,$Zoomsecret)
    {
        $key = $Zoomkey;
        $secret = $Zoomsecret;

        $payload = [
            'iss' => $key,
            'exp' => strtotime('+1 minute'),
        ];
        // dd($key,$secret,$Zoomkey,$Zoomsecret);
        return \Firebase\JWT\JWT::encode($payload, $secret, 'HS256');
    }
    private function retrieveZoomUrl()
    {
        // dd(env('ZOOM_API_URL'),env('ZOOM_API_KEY'));
        return env('ZOOM_API_URL', '');
    }



    private function zoomRequest($Zoomkey,$Zoomsecret)
    {
        $jwt = $this->generateZoomToken($Zoomkey,$Zoomsecret);
        // dd($jwt);
        $this->token = $jwt;
        return \Illuminate\Support\Facades\Http::withHeaders([
            'authorization' => 'Bearer ' . $jwt,
            'content-type' => 'application/json',
        ]);

        // return []
    }

    public function zoomGet(string $path, array $query = [],$Zoomkey,$Zoomsecret)
    {
        $url = $this->retrieveZoomUrl();
        $request = $this->zoomRequest($Zoomkey,$Zoomsecret);

        return $request->get($url . $path, $query);
    }

    public function zoomPost(string $path, array $body = [],$Zoomkey,$Zoomsecret)
    {
        $url = $this->retrieveZoomUrl();
        $request = $this->zoomRequest($Zoomkey,$Zoomsecret);
        // dd($Zoomkey,$Zoomsecret);
        return $request->post($url . $path, $body);
    }

    public function zoomPatch(string $path, array $body = [],$Zoomkey,$Zoomsecret)
    {
        $url = $this->retrieveZoomUrl();
        $request = $this->zoomRequest($Zoomkey,$Zoomsecret);
        return $request->patch($url . $path, $body);
    }

    public function zoomDelete(string $path, array $body = [],$Zoomkey,$Zoomsecret)
    {
        $url = $this->retrieveZoomUrl();
        $request = $this->zoomRequest($Zoomkey,$Zoomsecret);
        return $request->delete($url . $path, $body);
    }
    public function toZoomTimeFormat(string $dateTime)
    {
        try {
            $date = new \DateTime($dateTime);
            return $date->format('Y-m-d\TH:i:s');
        } catch (\Exception $e) {
            Log::error('ZoomJWT->toZoomTimeFormat : ' . $e->getMessage());
            return '';
        }
    }

    public function toUnixTimeStamp(string $dateTime, string $timezone)
    {
        try {
            $date = new \DateTime($dateTime, new \DateTimeZone($timezone));
            return $date->getTimestamp();
        } catch (\Exception $e) {
            Log::error('ZoomJWT->toUnixTimeStamp : ' . $e->getMessage());
            return '';
        }
    }
}
