<?php

namespace App;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Model;

class GroupLesson extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'group_id', 'lesson_id', 'hidden', 'comment', 'course_category_id'
    ];
}
