<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserZoom extends Model
{
    
    use HasFactory;
    protected $fillable = ['name','email' ,'password', 'token_line'];
    protected $table = 'user';
    public $timestamps = true;
}
