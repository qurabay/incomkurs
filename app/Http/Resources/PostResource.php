<?php

namespace App\Http\Resources;

use App\Models\Lesson;
use App\Models\User;
use App\Models\UserCourse;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);

    }

}
