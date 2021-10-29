<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinuteOfMeeting extends Model
{
    use HasFactory;
    protected $fillable = ['meeting_id','minute'];
    protected $table = 'minute_of_meeting';
    public $timestamps = true;
}
