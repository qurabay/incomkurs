<?php

namespace App\Http\Controllers\Admin;

use App\Group;
use App\GroupLesson;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\GroupUser;
use App\Models\Lesson;
use App\Models\UserCourse;
use App\Models\UserLesson;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::all();
        if (session()->has('admin')) {
            return view('admin.group.index', ['groups' => $groups]);
        }
        if (session()->has('moderator')) {
            return view('admin.group-moderator.index', ['groups' => $groups]);
        }
    }

    public function create()
    {
        $users = User::all();
        $courses = Course::all();
        if (session()->has('admin')) {
            return view('admin.group.create', ['users' => $users, 'courses' => $courses]);
        }
        if (session()->has('moderator')) {
            return view('admin.group-moderator.create', ['users' => $users, 'courses' => $courses]);
        }
    }

    public function addGroup(Request $request)
    {
        if ($request->input('action') == 'all' && $request['course'] && $request['title']) {
            $course = Course::findOrFail($request['course']);
            $lessons = Lesson::where('course_id', $course->id)->get();
            $group = Group::create([
                'title'     =>  $request['title'],
                'course_id' =>  $course->id,
            ]);
            $group->save();
            $users = User::all();
            foreach ($users as $user) {
                $userCourse = UserCourse::create([
                    'course_id' =>  $course->id,
                    'user_id'   =>  $user['id'],
                    'group_id'  =>  $group->id,
                ]);
                $userCourse->save();
                foreach ($lessons as $lesson) {
                    $userLesson = UserLesson::create([
                        'user_id'   =>  $user['id'],
                        'group_id'  =>  $group->id,
                        'course_id' =>  $course->id,
                        'course_category_id'    =>  $lesson->course_category_id,
                        'lesson_id' =>  $lesson->id,
                        'isOpened'  =>  false,
                        'opened'    =>  null,
                        'time'  =>  0,
                    ]);
                    $userLesson->save();
                }
            }
        }
        if ($request->input('action') == 'one' && $request['users'] && $request['course'] && $request['title']) {
            $course = Course::findOrFail($request['course']);
            $lessons = Lesson::where('course_id', $course->id)->get();
            $group = Group::create([
                'title'     =>  $request['title'],
                'course_id' =>  $course->id,
            ]);
            $group->save();
            foreach ($request['users'] as $user) {
                $userCourse = UserCourse::create([
                    'course_id' =>  $course->id,
                    'user_id'   =>  $user,
                    'group_id'  =>  $group->id,
                ]);
                $userCourse->save();
                foreach ($lessons as $lesson) {
                    $userLesson = UserLesson::create([
                        'user_id'   =>  $user,
                        'group_id'  =>  $group->id,
                        'course_id' =>  $course->id,
                        'course_category_id'    =>  $lesson->course_category_id,
                        'lesson_id' =>  $lesson->id,
                        'isOpened'  =>  false,
                        'opened'    =>  null,
                        'time'  =>  0,
                    ]);
                    $userLesson->save();
                }
            }
        }

        return redirect()->back()->with('success','Added!');
    }

    public function getCategory($id)
    {
        $group = Group::find($id);
        $courseCategories = CourseCategory::where('course_id', $group->course_id)->orderBy('created_at','desc')->get();
        if (session()->has('admin')) {
            return view('admin.group.categories', ['courseCategories' => $courseCategories, 'group' => $group]);
        }
        if (session()->has('moderator')) {
            return view('admin.group-moderator.categories', ['courseCategories' => $courseCategories, 'group' => $group]);
        }
    }

    public function getLesson($category, $group)
    {
        $group = Group::find($group);
        $course = Course::find($group['course_id']);
        $lessons =UserLesson::where('group_id', $group['id'])->where('course_id', $course['id'])
            ->where('course_category_id', $category)
            ->select('lesson_id')
            ->groupBy('lesson_id')
            ->get();


        if (session()->has('admin')) {
            return view('admin.group.lessons', ['course' => $course, 'lessons' => $lessons, 'group' => $group]);
        }
        if (session()->has('moderator')) {
            return view('admin.group-moderator.lessons', ['course' => $course, 'lessons' => $lessons, 'group' => $group]);
        }
    }

    public function getStudent($id)
    {
        $users = UserCourse::where('group_id', $id)->select('user_id')->groupBy('user_id')->get();
        $group = Group::find($id);

        if (session()->has('admin')) {
            return view('admin.group.students', ['users' => $users, 'group' => $group]);
        }
        if (session()->has('moderator')) {
            return view('admin.group-moderator.students', ['users' => $users, 'group' => $group]);
        }
    }

    public function hidden($id, $group)
    {
        $lessons = UserLesson::where('lesson_id', $id)->where('group_id', $group)->get();
        foreach ($lessons as $lesson) {
            $lesson->hidden = !$lesson->hidden;
            $lesson->save();
        }

        return redirect()->back()->with('success','Edited!');
    }

    public function comment($id, $group)
    {
        $lessons = UserLesson::where('id', $id)->where('group_id', $group)->get();
        foreach ($lessons as $lesson) {
            $lesson->comment = !$lesson->comment;
            $lesson->save();
        }

        return redirect()->back()->with('success','Edited!');
    }

    public function delete($id)
    {
        $group = Group::find($id);
        UserCourse::where('group_id', $group['id'])->delete();
        UserLesson::where('group_id', $group['id'])->delete();
        $group->delete();

        return redirect()->back()->with('success','Deleted!');
    }

    public function deleteUser(Request $request, $group_id)
    {
        if (isset($request['users'])) {
            foreach ($request['users'] as $user) {
                UserCourse::where('user_id', $user)->where('group_id', $group_id)->delete();
                UserLesson::where('user_id', $user)->where('group_id', $group_id)->delete();
            }
        }

        return redirect()->back()->with('success','Deleted!');
    }

    public function addUser($group_id)
    {
        $group = Group::find($group_id);
        $users = User::all();
        if (session()->has('admin')) {
            return view('admin.group.add-user', ['group' => $group, 'users' => $users]);
        }
        if (session()->has('moderator')) {
            return view('admin.group-moderator.add-user', ['group' => $group, 'users' => $users]);
        }
    }

    public function loadUser($group_id, Request $request)
    {
        
        $group = Group::find($group_id);
        foreach ($request['users'] as $user) {
            $userCourse = UserCourse::create([
                'course_id' => $group['course_id'],
                'user_id' => $user,
                'group_id' => $group['id'],
            ]);
            $userCourse->save();
        }
        $lessons = Lesson::where('course_id', $group['course_id'])->get();
        foreach ($lessons as $lesson) {
            foreach ($request['users'] as $user) {
                $userLesson = UserLesson::create([
                    'user_id' => $user,
                    'group_id' => $group['id'],
                    'course_id' => $group['course_id'],
                    'course_category_id' => $lesson['course_category_id'],
                    'lesson_id' => $lesson['id'],
                ]);
                $userLesson->save();
            }
        }


        $users = UserCourse::where('group_id', $group_id)->select('user_id')->groupBy('user_id')->get();
        $group = Group::find($group_id);

        if (session()->has('admin')) {
            return view('admin.group.students', ['users' => $users, 'group' => $group]);
        }
        if (session()->has('moderator')) {
            return view('admin.group-moderator.students', ['users' => $users, 'group' => $group]);
        }
    }
}
