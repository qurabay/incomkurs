<?php

namespace App\Http\Controllers\Admin;

use App\Group;
use App\Imports\UsersImport;
use App\Models\Course;
use App\Models\User;
use App\Models\Payment;
use App\Models\UserLesson;
use App\Models\UserCourse;
use App\Models\Lesson;
use App\Packages\Firebase;
use App\Packages\SMS;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PHPUnit\Framework\Constraint\LessThan;

class UserController extends Controller
{

    public function index(Request $request)
    {
        if (session()->has('admin')) {
            if ($request['search']) {
                $data['users'] = User::where(function ($query) use ($request) {
                    $query->where('phone', 'LIKE', "%$request->search%")
                        ->orWhere('name', 'LIKE', "%$request->search%")
                        ->orWhere('email', 'LIKE', "%$request->search%");
                })->where('id', '>', 0)->orderBy('created_at', 'desc')->paginate(50);

            } else {
                $data['users'] = User::where('id', '>', 0)->orderBy('created_at', 'desc')->paginate(50);
            }

            $data['search'] = $request['search'];

            return view('admin.user.index', $data);
        }
        if (session()->has('moderator')) {
            if ($request['search']) {
                $data['users'] = User::where(function ($query) use ($request) {
                    $query->where('phone', 'LIKE', "%$request->search%")
                        ->orWhere('name', 'LIKE', "%$request->search%")
                        ->orWhere('email', 'LIKE', "%$request->search%");
                })->where('id', '>', 0)->orderBy('created_at', 'desc')->paginate(50);

            } else {
                $data['users'] = User::where('id', '>', 0)->orderBy('created_at', 'desc')->paginate(50);
            }

            $data['search'] = $request['search'];

            return view('admin.user-moderator.index', $data);
        }
    }

    public function create()
    {
        if (session()->has('admin')) {
            $data['courses'] = Course::all();
            $data['groups'] = Group::all();

            return view('admin.user.create', $data);
        }
        if (session()->has('moderator')) {
            $data['courses'] = Course::all();
            $data['groups'] = Group::all();

            return view('admin.user-moderator.create', $data);
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'name'=> 'required',
            'email'=> 'required|unique:users,email',
            'password'=> 'required|min:4|max:15',
            'user_groups'   =>  'array',
        ];
        $messages = [
            'email.unique' => 'Логин занять',
            'password.min' => 'Пароль должен быть больше 4',
            'password.max' => 'Пароль не должен превышать 15',
        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $user = new User();
        $user->name = $request['name'];
        $user->password = $request['password'];
        $user->token = Str::random(30);
        $user->device_id = null;
        $user->email = $request['email'];
        $user->save();


        UserCourse::where('user_id',$user->id)->delete();
        UserLesson::where('user_id', $user->id)->delete();
//        if ($request['user_courses']){
//            foreach ($request['user_courses'] as $id){
//                UserCourse::insert(['user_id'=>$user->id,'course_id'=>$id]);
//
//                $lessons = Lesson::where('course_id', $id)->get()->toArray();
//                foreach ($lessons as $lesson) {
//                    UserLesson::insert([
//                        'user_id'   =>  $user->id,
//                        'course_id' =>  $id,
//                        'lesson_id' =>  $lesson['id'],
//                        'isOpened'  =>  false,
//                        'opened'    =>  null,
//                    ]);
//                }
//            }
//        }

        if ($request['user_groups']) {
            foreach ($request['user_groups'] as $group) {
                $g = Group::find($group);
                $userCourse = UserCourse::insert([
                    'user_id'   =>  $user->id,
                    'course_id' =>  $g->course_id,
                    'group_id'  =>  $group,
                ]);
                $lessons = Lesson::where('course_id', $g->course_id)->get();
                foreach ($lessons as $lesson) {
                    $userLesson = UserLesson::insert([
                        'user_id'   =>  $user->id,
                        'course_id' =>  $g->course_id,
                        'course_category_id' => $lesson->course_category_id,
                        'lesson_id' =>  $lesson->id,
                        'group_id'  =>  $group,
                    ]);
                }
            }
        }


        return redirect()->route('admin.user.index');
    }

    public function show($id)
    {
        if (session()->has('admin')) {
            return view('admin.lesson.show', ['lesson' => Lesson::findOrFail($id)]);
        }
        if (session()->has('moderator')) {
            return view('admin.lesson-moderator.show', ['lesson' => Lesson::findOrFail($id)]);
        }
    }

    public function edit($id, Request $request)
    {
        if (session()->has('admin')) {
            $data['user'] = User::findOrFail($id);
            $data['courses'] = Course::all();
            $data['groups'] = Group::all();

            return view('admin.user.edit', $data);
        }
        if (session()->has('moderator')) {
            $data['user'] = User::findOrFail($id);
            $data['courses'] = Course::all();
            $data['groups'] = Group::all();

            return view('admin.user-moderator.edit', $data);
        }
    }

    public function update($id, Request $request)
    {
        $rules = [
            'name'=> 'required',
            'phone'=> 'required',
//            'user_courses'=> 'array',
            'user_groups'   =>  'array',
        ];
        $messages = [

        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $u = User::findOrFail($id);
        $u->name = $request['name'];
        $u->phone = $request['phone'];
        $u->email = $request['email'];
        $u->password = $request['password'];

        $u->save();


        UserCourse::where('user_id',$u->id)->delete();
        UserLesson::where('user_id', $u->id)->delete();

//        if ($request['user_courses']){
//            foreach ($request['user_courses'] as $id){
//                UserCourse::insert(['user_id'=>$u->id,'course_id'=>$id]);
//                $lessons = Lesson::where('course_id', $id)->get();
//                foreach ($lessons as $lesson) {
//                    UserLesson::insert([
//                        'user_id' => $u->id,
//                        'course_id' => $id,
//                        'course_category_id'    =>  $lesson->course_category_id,
//                        'lesson_id'     =>  $lesson->id,
//                    ]);
//                }
//            }
//        }

        if ($request['user_groups']) {
            foreach ($request['user_groups'] as $group) {
                $g = Group::find($group);
                $userCourse = UserCourse::insert([
                    'user_id'   =>  $u->id,
                    'course_id' =>  $g->course_id,
                    'group_id'  =>  $group,
                ]);
                $lessons = Lesson::where('course_id', $g->course_id)->get();
                foreach ($lessons as $lesson) {
                    $userLesson = UserLesson::insert([
                        'user_id'   =>  $u->id,
                        'course_id' =>  $g->course_id,
                        'course_category_id' => $lesson->course_category_id,
                        'lesson_id' =>  $lesson->id,
                        'group_id'  =>  $group,
                    ]);
                }
            }
        }

        return redirect()->route('admin.user.index');
    }

    public function destroy($id)
    {
        $l = User::findOrFail($id);
        $courses = UserCourse::where('user_id', $id)->get();
        $userLessons = UserLesson::where('user_id', $id)->get();
        if (count($courses) != 0) {
            foreach ($courses as $course) {
                $course->delete();
            }
        }
        if (count($userLessons) != 0) {
            foreach ($userLessons as $lesson) {
                $lesson->delete();
            }
        }

        $l->delete();
        return redirect()->back();
    }

    public function updateDevice($id){
        $user = User::findOrFail($id);
        $user->device_id = null;
        $user->token = Str::random(30);
        $user->save();

        return redirect()->route('admin.user.index');
    }

    function import(Request $request){
        if ($request->getMethod() == 'GET' && session()->has('admin')){
            return view('admin.user.import');
        }
        if ($request->getMethod() == 'GET' && session()->has('moderator')) {
            return view('admin.user-moderator.import');
        }
        else{

            $rules = [
                'file'=> 'required|mimes:xlsx,csv,xls',
            ];
            $messages = [

            ];
            $validator = $this->validator($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return back()->withErrors($validator->errors());
            }


            Excel::import(new UsersImport(), $request['file']);

            return redirect()->back()->with('success', 'Импортирован!');
        }
    }

    function deleteAll(){
//        User::where('id','<>','1')->delete();
        UserLesson::truncate();
        UserCourse::truncate();
        Payment::truncate();
        User::truncate();
        return redirect()->back();
    }

    function deleteCheckbox(Request $request){
       if ($request['users']){
           foreach ($request['users'] as $id) {
               $l = User::findOrFail($id);
               $l->delete();
           }
       }

        return redirect()->back()->with('success','Удален!');

    }

    public function getProcess($id, Request $request)
    { 
        $user = User::findOrFail($id);
        $ans = DB::table('user_courses')
            ->join('courses', 'user_courses.course_id', 'courses.id')
            ->where('user_courses.user_id', $user['id'])
            ->select('user_courses.id as user_course', 'user_courses.opened', 'courses.title','courses.id','courses.price',
                'courses.image','courses.deadline', 'user_courses.user_id')
            ->get()->toArray();

        if (session()->has('admin')) {
            return view('admin.user.process', ['user' => $user, 'courses' => $ans]);
        }
        if (session()->has('moderator')) {
            return view('admin.user-moderator.process', ['user' => $user, 'courses' => $ans]);
        }
    }


    public function getLessons($id, Request $request)
    {
        $userCourse = UserCourse::where('id',$id)->first();
        $lessons = DB::table('user_lessons')->join('lessons', 'lessons.id', 'user_lessons.lesson_id')
                ->where('user_lessons.user_id', $userCourse['user_id'])
                ->where('user_lessons.course_id', $userCourse['course_id'])
                ->select('lessons.title', 'lessons.description', 'user_lessons.id', 'user_lessons.isOpened',
                'user_lessons.opened')
                ->get()->toArray();
        if (session()->has('admin')) {
            return view('admin.user.lessons', ['lessons' => $lessons]);
        }
        if (session()->has('moderator')) {
            return view('admin.user-moderator.lessons', ['lessons' => $lessons]);
        }
    }

}


