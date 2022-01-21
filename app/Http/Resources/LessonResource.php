<?php

namespace App\Http\Resources;

use App\Models\Lesson;
use App\Models\LessonPdf;
use App\Models\LessonVideo;
use App\Models\UserCourse;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
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
            'id'        => $this->id,
            'course_id'             => $this->course_id,
            'course_category_id'    =>  $this->course_category_id,
            'title'         => $this->title,
            'description'   => $this->description,
            'homework'  => $this->homework,
            'video_url' => $this->video_url,
            'video_fon' => $this->video_fon,
            'pdf'       => LessonPdf::where('lesson_id',$this->id)->pluck('path'),   // null
            'audios'    => $this->audios,
            'homework_audios' => $this->homework_audios,
            'show'      =>  $this->show,
            'comment'   =>  $this->comment ? true : false,
            'hidden'    =>  $this->hidden,
        ];
    }


}
