<?php

namespace App\Http\Model;

use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    protected $table = 'people';
    protected $primaryKey = 'id';
    public $timestamps = false;
}
