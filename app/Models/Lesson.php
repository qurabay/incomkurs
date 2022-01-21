<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $hidden = ['created_at','updated_at'];

    protected $casts = [
        'audios'=>'array',
        'homework_audios'=>'array'
    ];

    protected $fillable = [
        'hidden', 'comment',
    ];

}
