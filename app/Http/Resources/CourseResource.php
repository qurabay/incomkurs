<?php

namespace App\Http\Resources;

use App\Models\Lesson;
use App\Models\User;
use App\Models\UserCourse;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'price' => $this->price,
            'description' => $this->description,
            'image' => $this->image,
            'lesson_count' => Lesson::whereCourseId($this->id)->count(),
            'bought'=> $this->getBought($request,$this->id, $this->free)
//            'bought'=> true
        ];

    }

    function getBought($request,$course_id,$free){
        if ($free) return true;


        if ($request->header('token')){
            $user = User::whereToken($request->header('token'))->first();
            if (!$user){
                return false;
            }
//            if (UserCourse::whereUserId($user->id)->whereCourseId($course_id)->exists() || $free || $user->id <= 3){
            if (UserCourse::whereUserId($user->id)->whereCourseId($course_id)->exists() OR $free){
                return true;
            }
            return false;
        }

        else{
            return false;
        }
    }
}
