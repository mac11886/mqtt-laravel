<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomHost extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'meeting_id'];
    protected $table = 'zoom_host';
    public $timestamps = true;

    function user()
    {
        return $this->hasOne(UserZoom::class, 'id', 'user_id');
    }
    function zoom_list()
    {
        return $this->hasOne(ZoomList::class, 'id', 'meeting_id');
    }
}
