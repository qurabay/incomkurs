<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\CourseCategory;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\UserLesson;
use function React\Promise\all;

class CourseCategoryController extends Controller
{
    public function index($id)
    {
        $data['categories'] = CourseCategory::where('course_id', $id)->orderBy('created_at','desc')->paginate(15);
        $data['course'] = Course::findOrFail($id);
        if (session()->has('admin')) {
            return view('admin.course-category.index', $data);
        }
//        if (session()->has('moderator')) {
//            return view('admin.lesson-moderator.index', $courseCategories);
//        }
    }

    public function create($id)
    {
        $data['course'] = Course::findOrFail($id);
        if (session()->has('admin')) {
            return view('admin.course-category.create', $data);
        }
    }

    public function store(Request $request, $id)
    {
        $courseCate = new CourseCategory();
        $courseCate->course_id = $id;
        $courseCate->title  = $request['title'];
        $courseCate->description = $request['description'];
        if ($request['image']) {
            $courseCate->image = $this->uploadFile($request['image']);
        }
        $courseCate->save();

        return redirect()->route('admin.category_course.index',$id);
    }

    public function edit($id)
    {
        $data['category'] = CourseCategory::findOrFail($id);
        if (session()->has('admin')) {
            return view('admin.course-category.edit', $data);
        }
    }

    public function update(Request $request, $id)
    {
        $courseCate = CourseCategory::where('id', $id)->first();
//        $courseCate->course_id = $id;
        $courseCate->title  = $request['title'];
//        $courseCate->description = $request['description'];
        if ($request['image']) {
            $courseCate->image = $this->uploadFile($request['image']);
        }
        $courseCate->save();

        return redirect()->route('admin.category_course.index', $courseCate->id);
    }

    public function destroy($id)
    {
        $Lessons = Lesson::where('course_category_id', $id)->get();
        if (count($Lessons) != 0) {
            foreach ($Lessons as $Lesson) {
                UserLesson::where('lesson_id', $Lesson['id'])->delete();
                $Lesson->delete();
            }
        }
        $category = CourseCategory::find($id);
        $category->delete();

        return redirect()->back();
    }

    public function beFirst($id)
    {
        $courseCategory = CourseCategory::find($id);
        $courseCategory->created_at = Carbon::now();
        $courseCategory->updated_at = Carbon::now();
        $courseCategory->save();
        
        return redirect()->route('admin.category_course.index', $courseCategory->course_id);
    }
}
