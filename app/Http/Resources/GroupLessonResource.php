<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;
use App\Models\Lesson;
use App\Group;

class GroupLessonResource extends JsonResource
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
            'id'    =>  $this->id ?? null,
            'group' =>  Group::find($this->group_id)?? null,
            'lesson' =>  Lesson::find($this->lesson_id),
            'hidden'    =>  $this->hidden ?? null,
            'comment'   =>  $this->comment ?? null,
        ];
    }
}
