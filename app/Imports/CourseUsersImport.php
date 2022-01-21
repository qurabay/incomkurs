<?php

namespace App\Imports;

use App\Models\User;
use App\Models\UserCourse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;

class CourseUsersImport implements ToModel
{
    public $courseId;

    public function __construct($courseId)
    {
        $this->courseId = $courseId;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        if (strlen($row[0]) == 10) {

            $user = User::whereEmail($row[0])->first();

            if ($user){
                $userCourse = new UserCourse();
                $userCourse->course_id = $this->courseId;
                $userCourse->user_id = $user->id;
                $userCourse->save();
            }
        }
    }


    public function chunkSize(): int
    {
        return 250;
    }

    public function startRow(): int
    {
        return 1;
    }
}
