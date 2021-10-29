<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $fillable = ['name','device_id' ,'location', 'zoom_email','zoom_api_key','zoom_api_secret'];
    protected $table = 'device';
    public $timestamps = true;
}
