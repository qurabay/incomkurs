<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserLesson extends Model
{

    public $timestamps = false;
    protected $fillable = [
        'user_id', 'group_id', 'course_id', 'course_category_id', 'lesson_id', 'isOpened', 'opened', 'time',
        'comment', 'accepted',
    ];

    public function lessons(): HasMany 
    {
        return $this->hasMany(Lesson::class, 'id', 'lesson_id');
    }
}
