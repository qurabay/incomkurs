<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\User;
use App\Models\Lesson;
use App\Models\UserCourse;
use App\Models\UserLesson;
use App\Packages\Firebase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CourseUsersImport;
use App\Imports\UsersImport;
class CourseController extends Controller
{

    public function index(Request $request)
    {
        $courses = Course::orderBy('created_at','desc')->paginate(15);
        if (session()->has('admin')) {
            return view('admin.course.index', compact('courses'));
        }
        if (session()->has('moderator')) {
            return view('admin.course-moderator.index', compact('courses'));
        }
    }
    public function create(Request $request)
    {
        if (session()->has('admin')) {
            return view('admin.course.create');
        }
        if (session()->has('moderator')) {
            return view('admin.course-moderator.create');
        }
    }
    public function store(Request $request)
    {
        $rules = [
            'title'=> 'required',
            'author'=> 'required',
            'image'=> 'required|image',
            'price'=> 'required|numeric|min:0',
//            'deadline'=> 'timestamp',
        ];
        $messages = [

        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $c = new Course();
        $c->title = $request['title'];
        $c->author = $request['author'];
        $c->price = $request['price'];
        $c->free = $request['free'];
        $c->description = str_replace('<iframe src="//www','<iframe src="https://www',$request['description']);

        $c->deadline = $request['deadline'];
        $c->image = $this->uploadFile($request['image']);
        $c->save();

        Firebase::send('newCourse',[
            'title' =>  $c->title ,
            'body' =>  $c->author = $request['author'],
            'sound'=>'default',
            'type'=> 'course',
            'id' => $c->id
        ]);
        return redirect()->route('admin.course.index');
    }

    public function show($id)
    {
        if (session()->has('admin')) {
            return view('admin.course.show', ['course' => Course::findOrFail($id)]);
        }
        if (session()->has('moderator')) {
            return view('admin.course-moderator.show', ['course' => Course::findOrFail($id)]);
        }
    }
    public function edit($id, Request $request)
    {
        if (session()->has('admin')) {
            return view('admin.course.edit', ['course' => Course::findOrFail($id)]);
        }
        if (session()->has('moderator')) {
            return view('admin.course-moderator.edit', ['course' => Course::findOrFail($id)]);
        }
    }
    public function update($id,Request $request)
    {
        $rules = [
            'title'=> 'required',
            'author'=> 'required',
            'image'=> 'image',
            'price'=> 'required|numeric|min:0',
        ];
        $messages = [

        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $c = Course::findOrfail($id);
        $c->title = $request['title'];
        $c->author = $request['author'];
        $c->free = $request['free'];
        $c->price = $request['price'];
        $c->description = str_replace('<iframe src="//www','<iframe src="https://www',$request['description']);

        $c->deadline = $request['deadline'];
        if ($request['image']){
            $c->image = $this->uploadFile($request['image']);
        }
        $c->save();
        return redirect()->route('admin.course.index');
    }
    public function destroy($id)
    {
        $c = Course::findOrFail($id);
        $lessons = Lesson::where('course_id', $id)->get();
        foreach ($lessons as $lesson) {
            $userLessons = UserLesson::where('lesson_id', $lesson->id)->get();
            foreach ($userLessons as $userLesson) {
                $userLesson->delete();
            }
            $lesson->delete();
        }
        $this->deleteFile($c->image);
        $c->delete();

        return redirect()->back();
    }

    public function users($courseId){
        $data['users'] = UserCourse::join('users','users.id','user_courses.user_id')
            ->select('user_courses.*','users.name','users.phone','users.email')
            ->whereCourseId($courseId)
            ->orderBy('created_at','desc')
            ->paginate(50);
        if (session()->has('admin')) {
            return view('admin.course.users', $data);
        }
        if (session()->has('moderator')) {
            return view('admin.course-moderator.users', $data);
        }
    }

    function usersAdd($courseId){
        foreach (User::all() as $user) {
            $uc = UserCourse::where('user_id',$user->id)->where('course_id',$courseId)->exists();

            if (!$uc){
                UserCourse::insert(['user_id'=>$user->id,'course_id'=>$courseId]);
            }
        }


        return redirect()->back()->with('success','Добавлен');
    }
    function usersDelete($courseId){
        UserCourse::where('course_id',$courseId)->delete();
        return redirect()->back()->with('success','Удален');
    }

    function usersImport(Request $request){

        if ($request->getMethod() == 'GET'){
            return view('admin.course.import',['course_id'=>$request['course_id']]);
        }
        else {
            $rules = [
                'course_id'=> 'required',
                'file' => 'required|mimes:xlsx,csv,xls',
            ];
            $messages = [

            ];
            $validator = $this->validator($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return back()->withErrors($validator->errors());
            }
            Excel::import(new CourseUsersImport($request['course_id']), $request['file']);

            return redirect()->back()->with('success', 'Импортирован!');
        }
    }
}
