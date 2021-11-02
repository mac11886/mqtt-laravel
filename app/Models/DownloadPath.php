<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadPath extends Model
{
    use HasFactory;
    protected $fillable = ['url'];
    protected $table = 'path';
    public $timestamps = true;
}
