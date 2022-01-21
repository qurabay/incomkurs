<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneratorDeviceId extends Model
{
    protected $table = 'generator_device_id';
    protected $hidden = ['created_at','updated_at'];
}
