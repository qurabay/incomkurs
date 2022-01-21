<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonPdf extends Model
{
    protected $table = 'lesson_pdf';
   protected $hidden = ['created_at','updated_at'];
}
