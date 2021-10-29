<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomHost extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','meeting_id'];
    protected $table = 'zoom_host';
    public $timestamps = true;
}
