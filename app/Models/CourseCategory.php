<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CourseCategory extends Model
{
    protected $table = 'course_categories';
    protected $hidden = ['created_at','updated_at'];

    public function userCourse(): HasOne 
    {
        return $this->hasOne(UserCourse::class, 'course_id', 'course_id');
    }
}
