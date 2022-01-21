<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupUser extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'group_id', 'user_id',
    ];
}
