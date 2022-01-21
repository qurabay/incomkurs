<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserCourse extends Model
{
    protected $hidden = ['created_at','updated_at'];
    public $timestamps = false;
    protected $fillable = [
        'id', 'course_id', 'user_id', 'group_id', 'opened',
    ];

    public function userLessons(): HasMany
    {
        return $this->hasMany(UserLesson::class, 'group_id', 'group_id');
    }

    public function course(): BelongsTo 
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
