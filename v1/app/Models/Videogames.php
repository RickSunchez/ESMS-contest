<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Videogames extends Model
{
    protected $fillable = ["title", "developer", "tags"];
    protected $table = 'videogames';
    public $timestamps = false;
}
