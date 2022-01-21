<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\CourseResource;
use App\Http\Resources\LessonResource;
use App\Http\Resources\PostResource;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Post;
use App\Models\PostCat;
use App\Models\QuestionAnswer;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserCourse;
use App\Models\UserLesson;
use App\Models\UserDeviceToken;
use Carbon\Carbon;
use Faker\Provider\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;


class MainController extends Controller
{
   function login(Request $request){
        if (session()->has('admin')){
            return redirect()->route('admin.main');
        }
        if ($request->getMethod() == 'GET'){
            return view('admin.login');
        }
        else{
            if ($request['username'] == 'INCON' and $request['password'] == 'ACADEMY'){
                session()->put('admin',1);
                session()->save();
                return redirect()->route('admin.main');
            }
            if ($request['username'] == 'moderator' && $request['password'] == 'moderator') {
                session()->put('moderator', 1);
                session()->save();
                return redirect()->route('admin.main');
            }
            else{
                return back()->withErrors('Логин или пароль не верно');
            }
        }
    }

   function main(){
       $data['countUsers'] = User::count();
       $data['countCourses'] = Course::count();
       $data['countLessons'] = Lesson::count();
       $data['countPosts'] = Post::count();
       if (session()->has('admin')) {
           session()->remove('moderator');
           return view('admin.main',$data);
       }
       if (session()->has('moderator')) {
           session()->remove('admin');
           return view('admin.main-moderator',$data);
       }

   }

   function out(){
        session()->forget('admin');
        return redirect()->route('admin.login');
    }

   function setting(Request $request){
       if ($request->getMethod() == 'GET'){
           return view('admin.setting',['setting'=> Setting::first()]);
       }

       $s = Setting::first();
       $s->whatsapp = $request['whatsapp'];
       $s->kaspi = $request['kaspi'];
       $s->qiwi = $request['qiwi'];
       $s->info = $request['info'];
       $s->whatsapp_link = $request['whatsapp_link'];
       $s->kaspi_description = $request['kaspi_description'];

       $s->phone = $request['phone'];
       $s->email = $request['email'];
       $s->oferta = $request['oferta'];
       $s->save();

       return redirect()->back();
   }

   public function getComments(Request $request)
   {
       $data['userLessons'] = UserLesson::whereNotNull('comment')->get();
       if (session()->has('admin')) {
           return view('admin.comments', $data);
       }
       else if(session()->has('moderator')) {
           return view('admin.comments-moderator', $data);
       }
   }

   public function acceptComment($id)
   {
       $userLesson = UserLesson::findOrFail($id);
       $userLesson->accepted = true;
       $userLesson->save();

       return redirect()->back();
   }

}
