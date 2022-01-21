<?php

namespace App\Http\Resources;

use App\CourseCategory;
use App\Group;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\UserLesson;
use App\Http\Resources\LessonResource;
use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
//            'id'    =>  $this->id,
//            'user'  =>  User::find($this->user_id),
//            'group' =>  Group::find($this->group_id) ?? null,
//            'course'=>  Course::find($this->course_id),
//            'course_category'   =>  CourseCategory::find($this->course_category_id),
            'lesson'   =>  Lesson::find($this->lesson_id),
            'hidden'    =>  $this->hidden,
//            'isOpened'  =>  $this->isOpened,
//            'opened'    =>  $this->opened   ,
//            'time'  =>  $this->time,
//            'comment'   =>  $this->comment,
//            'accepted'  =>  $this->accepted,
        ];
    }
}
