<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomList extends Model
{
    use HasFactory;
    protected $fillable = ['url','device_id' ,'start_time', 'end_time','date'];
    protected $table = 'zoom_list';
    public $timestamps = false;
}
