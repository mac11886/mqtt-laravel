<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    protected $table = 'team';
    public $timestamps = true;

    public function user(){
        return $this->hasMany(UserZoom::class,'team_id','id');
    }
}
