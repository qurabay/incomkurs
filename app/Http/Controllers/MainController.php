<?php

namespace App\Http\Controllers;

use App\GroupLesson;
use App\Http\Resources\CourseResource;
use App\Http\Resources\LessonResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserLessonResource;
use App\Http\Resources\SecondLessonResource;
use App\Http\Resources\UserResource;
use App\Models\AdvertCat;
use App\Models\CarBodyIndex;
use App\Models\CarMark;
use App\Models\CarModel;
use App\Models\CarSeries;
use App\Models\City;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\GroupUser;
use App\Models\Lesson;
use App\Models\Payment;
use App\Models\Post;
use App\Models\PostCat;
use App\Models\QuestionAnswer;
use App\Models\User;
use App\Models\UserLesson;
use App\Models\UserCourse;
use App\Models\UserDeviceToken;
use App\Packages\SMS;
use Carbon\Carbon;
use Faker\Provider\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use phpDocumentor\Reflection\Types\True_;
use PhpParser\Node\Stmt\GroupUse;
use function PHPUnit\Framework\stringContains;

class MainController extends Controller
{

    function Courses(Request $request){
        $rules = [
            'page'=> 'required|numeric|min:1',
        ];
        $messages = [

        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),400);
        }
        if($request['free']) {
            if($request['free'] == 2) $request['free'] = 0;
            $courses = Course::where('free', $request['free'])->orderBy('created_at','desc');
        } else {
            $courses = Course::orderBy('created_at','desc');
        }

        if ($request['search']){
            $courses = $courses->where('title','LIKE',"%$request->search%");
        }
        $courses  = $this->paginate($courses,CourseResource::class,$request['page'],15);
        return response()->json($courses,200);
    }
    function Lessons(Request $request){
        $rules = [
            'course_id'=> 'required|exists:courses,id',
        ];
        $messages = [

        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }

        $lessons = Lesson::where('course_id',$request['course_id'])->where('hidden', 1)->orderBy('id');

        if ($request['search']){
            $lessons = $lessons->where('title','LIKE',"%$request->search%");
        }
        if ($lessons->count() > 0){
            return response()->json($lessons->get(['id','video_fon','title', 'show']),200);
        }else{
            return response()->json([],404);
        }
    }
    function CourseBuy(Request $request){
        $rules = [
//            'phone'=> 'required|digits:10',
            'phone'=> 'required',
            'course_id'=> 'required|numeric|exists:courses,id',
            'device_id'=> 'required',
            'email'=> 'email',
        ];
        $messages = [

        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }
        $course = Course::find($request['course_id']);
        $user = User::wherePhone($request['phone'])->first();

        if (!$user){
            $password = mt_rand(1000,9999);
            $user = new User();
            $user->phone = $request['phone'];
            $user->name = $request['name'];
            $user->password = bcrypt($password);
            $user->token = Str::random(30);
            $user->device_id = $request['device_id'];
            $user->email = $request['email'];
            $user->save();

            SMS::send('7'.$user->phone,'Ваш пароль: '.$password);

        }
        if (!$user->device_id){
            $user->device_id = $request['device_id'];
            $user->save();
        }
        if ($user->device_id != $request['device_id']){
            return response()->json('Бір құрылғыдан кіріңіз!',400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }

        if (UserCourse::whereCourseId($request['course_id'])->whereUserId($user->id)->exists()){
            return response()->json('Бұл сабақты сатып алғансыз!',400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }
        else{
            $course = Course::find($request['course_id']);

            $p = new Payment();
            $p->user_id = $user->id;
            $p->amount = $course->price ;
            $p->course_id = $course->id ;
            $p->save();

            $arrReq = [
                'pg_merchant_id' => env('pg_ID'),
                'pg_amount' =>$p->amount,
                'pg_salt' => mt_rand(100000,999999),
                'pg_order_id' => $p->id,
                'pg_description' => 'Запрос Оплата к систему',
                'pg_result_url' => route('PayboxResult'),
                'pg_success_url' => route('PayboxSuccess'),
                'pg_failure_url' => route('PayboxFail'),
                'pg_success_url_method' => 'GET',
                'pg_failure_url_method' => 'GET',
            ];
            ksort($arrReq);
            array_unshift($arrReq, 'payment.php');
            array_push($arrReq, env('pg_KEY'));


            $arrReq['pg_sig'] = md5(implode(';', $arrReq));
            unset($arrReq[0], $arrReq[1]);


            $url = 'https://api.paybox.money/payment.php?'. http_build_query($arrReq);

            $p->pg_salt = $arrReq['pg_salt'];
            $p->pg_sig = $arrReq['pg_sig'];
            $p->save();

        }

        return response()->json(['token'=>$user->token,'paybox_url'=>$url],200);
    }
    function Login(Request $request){
        $rules = [
            'phone'=> 'required|exists:users,email',
            'password'=> 'required',
            'device_id'=> 'required',
        ];
        $messages = [
            'phone.required'=>'Логин жазыңыз!',
            'password.required'=>'Құпиясөзді жазыңыз!',
            'phone.exists'=>'Құпиясөз немесе логин қате!',
        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }
        $user = User::whereEmail($request['phone'])->first();
        if ($request['password'] !== $user->password){
            return response()->json('Құпиясөз немесе номер қате!',400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }

        if (!$user->device_id || $user->id <= 1){
            $user->device_id = $request['device_id'];
            $user->last_login = Carbon::now();
            $user->save();
        }

        if ($user->device_id && strpos( $user->device_id , $request['device_id'] )===false && strpos($user->device_id,',')===false ){
            $user->device_id = $user->device_id .',' .  $request['device_id'];
            $user->save();


        }
        // dd($user->device_id);
        // dd($request['device_id']);
        // dd(strpos($user->device_id,$request['device_id']));
        if (strpos($user->device_id,$request['device_id'])=== false){
            return response()->json('Бір құрылғыдан кіріңіз!',400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }


        return response()->json($user,200);
    }
    function LoginByToken(Request $request){
        $rules = [
            'token'=> 'required|exists:users,token',
        ];
        $messages = [
            'token.exists'=>'Бір құрылғыдан кіріңіз!',
        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }
        return response()->json('success',200);
    }

    function MyCourses(Request $request){
        $rules = [
            'page'=> 'required|numeric|min:1',
        ];
        $messages = [

        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }

        $courses = Course::join('user_courses','user_courses.course_id','courses.id')
            ->where('user_courses.user_id', $request['user']->id)
            ->select('courses.*')
            ->orderBy('user_courses.id','desc');

        if ($request['search']){
            $courses = $courses->where('title','LIKE',"%$request->search%");
        }

        $courses  = $this->paginate($courses,CourseResource::class,$request['page'],15);

        return response()->json($courses,200);
    }

    function Feedback(Request $request){

//        return response()->json([
//            'phone'=>'87004448696',
//            'email'=>'test@test.com',
//        ],200);
    }

    function QuestionAnswers(Request $request){

        return response()->json(QuestionAnswer::all(),200);
    }

    function PostCats(){
        return response()->json(PostCat::all(),200);
    }

    function Posts(Request $request){
        $rules = [
            'page'=> 'required|numeric|min:1',
//            'cat_id'=> 'required|numeric|exists:post_cats,id',
        ];
        $messages = [

        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }
        $posts = Post::orderBy('id','desc');
        $posts  = $this->paginate($posts,PostResource::class,$request['page'],15);
        return response()->json($posts,200);
    }
    function Post(Request $request){
        $rules = [
            'post_id'=> 'required|numeric|exists:posts,id',
        ];
        $messages = [

        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }

        $post = Post::find($request['post_id']);
        $post->show = $post->show  + 1;
        $post->save();


        return response()->json(new PostResource($post),200);
    }

    public function Lesson(Request $request){
        $rules = [
            'lesson_id'=> 'required|numeric|exists:lessons,id',
        ];
        $messages = [

        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }

        $lesson = Lesson::find($request['lesson_id']);

//        if (!UserCourse::whereCourseId($lesson->course_id)->whereUserId($request['user']->id)->exists()){
//            return response()->json('У вас нет доступа',400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
//        }
        $lesson->show++;
        $lesson->save();

        $user = User::where('token', $request->header('token'))->first();
        if ($user) {
            $lessonSecond = UserLesson::where('user_id', $user['id'])->where('lesson_id', $request['lesson_id'])->first();
            if ($lessonSecond) {
                $lessonSecond->isOpened = true;
                $lessonSecond->opened = Carbon::now();

                $lessonSecond->save();
            }
            if ($lesson['description']) {
                if (str_contains($lesson['description'], '.mp4') && $lessonSecond != null) {
                    $lesson['description'] = str_replace('.mp4', '.mp4#t=' . (int)$lessonSecond['time'], $lesson['description']);
                }
                else if (str_contains($lesson['description'], '.mp3') && $lessonSecond != null) {
                    $lesson['description'] = str_replace('.mp3', 'mp3#t=' . (int)$lessonSecond['time'], $lesson['description']);
                }
                else if (str_contains($lesson['description'], '.m4v') && $lessonSecond != null) {
                    $lesson['description'] = str_replace('.m4v', 'm4v#t=' . (int)$lessonSecond['time'], $lesson['description']);
                }
                else if (str_contains($lesson['description'], '.MOV') && $lessonSecond != null) {
                    $lesson['description'] = str_replace('.MOV', 'MOV#t=' . (int)$lessonSecond['time'], $lesson['description']);
                }
                else if (str_contains($lesson['description'], '.mov') && $lessonSecond != null) {
                    $lesson['description'] = str_replace('.mov', 'mov#t=' . (int)$lessonSecond['time'], $lesson['description']);
                }
            }
            return response()->json(new LessonResource($lesson),200);
        }


        return response()->json(new LessonResource($lesson),200);
    }

    public function getLesson(Request $request)
    {
        $rules = [
            'lesson_id' =>  'required',
        ];
        $validator = $this->validator($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }
        $user = $request['user'];
//        $courses = UserCourse::where('user_id', $user['id'])->pluck('course_id')->toArray();
//        $lessonCourse = Lesson::where('id', $request['lesson_id'])->pluck('course_id')->toArray();
//        if (in_array($lessonCourse[0], $courses)) {
//            $lesson = DB::table('user_lessons')->;
//        }
//        $user->lesson_id = $request['lesson_id'];
//        $user->lesson_date = Carbon::now();

        $lesson = Lesson::where('id', $request['lesson_id'])->first();


        return response()->json([
            'statusCode'    =>  404,
            'data'       =>  $user,
        ],400);
    }

    public function getCourse(Request $request)
    {
        $rules = [
            'course_id'=> 'required|exists:courses,id',
        ];
        $messages = [

        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }
        $user = $request['user'];
        $userCourse = UserCourse::where('user_id', $user['id'])->where('course_id', $request['course_id'])->first();
        $lessons = Lesson::where('course_id',$request['course_id'])->orderBy('id');
        if ($request['search']){
            $lessons = $lessons->where('title','LIKE',"%$request->search%");
        }
        if ($lessons->count() > 0){
            $userCourse->opened = Carbon::now();
            $userCourse->save();
            return response()->json($lessons->get(['id','video_fon','title', 'show']),200);
        }else{
            return response()->json([],404);
        }
    }

    public function openLesson(Request $request)
    {
        $rules = [
            'lesson_id' =>  'required|exists:lessons,id'
        ];
        $messages = [];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }
        $user = $request['user'];
        $lesson = UserLesson::where('user_id', $user['id'])->where('lesson_id', $request['lesson_id'])->first();
        if ($lesson) {
            $lesson->isOpened = true;
            $lesson->opened = Carbon::now();

            $lesson->save();

            return response()->json([
                'statusCode'    =>  200,
                'data'          =>  $lesson
            ]);
        }
        return response()->json([
            'message'   =>  'Not found',
        ],400);
    }

    public function saveTime(Request $request)
    {
        $rules = [
            'lesson_id'     =>  'required',
            'time'          =>  'required',
        ];
        $messages = [];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }
        $user = $request['user'];
        $userLesson = UserLesson::where('user_id', $user['id'])->where('lesson_id', $request['lesson_id'])->first();
        if ($userLesson) {
            $userLesson->time += $request['time'];
            $userLesson->save();

            return response()->json([
                'statusCode'    =>  200,
                'data'          =>  $userLesson,
            ]);
        }
        return response()->json([
            'statusCode'    =>  400,
            'message'       =>  'Not found!',
        ], 400);
    }

    public function getCategory(Request $request)
    {
        $rules = [
            'course_id'     =>  'required|exists:courses,id',
        ];
        $messages = [];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }
        $user = $request['user'];
        $course = Course::where('id', $request['course_id'])->first();
        if ($course) {
//            $courseCategories = CourseCategory::where('course_id', $course['id'])->get();
            $courseCategories = CourseCategory::where('course_id', $course['id'])->orderBy('created_at','desc')->get();
            if (count($courseCategories) != 0 ){
                return response()->json([
                    'statusCode'    =>  200,
                    'data'          =>  $courseCategories,
                ]);
            }
        }
        return response()->json([
            'statusCode'    =>  400,
            'message'       =>  "Course not found!",
        ],400);
    }

    public function getLessonByCategory(Request $request)
    {
        $rules = [
            'course_category_id'     =>  'required|exists:course_categories,id',
        ];
        $messages = [];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }
        $courseCategory = CourseCategory::where('id', $request['course_category_id'])->first();
        $course = Course::where('id', $courseCategory['course_id'])->where('free', true)->first();
        if ($course) {
            $lessons = Lesson::where('course_category_id',  $request['course_category_id'])->get();
            if (count($lessons) != 0) {
                return response()->json([
                    'statusCode'    =>  200,
                    'data'          =>  LessonResource::collection($lessons),
                ]);
            }
            return response()->json([
                'statusCode'    =>  200,
                'message'       =>  "Lesson not found!",
            ]);
        }
        if ($courseCategory) {
            $lessons = Lesson::where('course_category_id', $request['course_category_id'])->where('hidden', 1)->where('comment', 1)->get();
            $lessonsArray = [];
            foreach ($lessons as $lesson) {
                $lessonsArray[] = $lesson['id'];
            }
            if (count($lessons) != 0 ){
                $comments = UserLesson::whereNotNull('comment')->where('accepted', true)->whereIn('lesson_id', $lessonsArray)->first();
                if ($comments) {
                    $allLessons = Lesson::where('course_category_id', $request['course_category_id'])->get();
                    return response()->json([
                        'statusCode'    =>  200,
                        'data'          =>  LessonResource::collection($allLessons),
                    ]);
                }
                return response()->json([
                    'statusCode'    =>  200,
                    'data'          =>  LessonResource::collection($lessons),
                ]);
            }
            return response()->json([
                'statusCode'    =>  400,
                'message'       =>  "lessons not found!",
            ],200);
        }
        return response()->json([
            'statusCode'    =>  400,
            'message'       =>  "category not found!",
        ],200);
    }

    public function getLessonByCat(Request $request)
    {
        $rules = [
            'course_category_id'     =>  'required|exists:course_categories,id',
        ];
        $messages = [];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }

        $courseCat = CourseCategory::where('id', $request['course_category_id'])->first();
        $lessons = Lesson::where('course_category_id', $request['course_category_id'])->where('hidden', true)->get();

//        if (count($lessons) != 0 ) {
//            return response()->json([
//                'statusCode'    =>  200,
//                'data'          =>  LessonResource::collection($lessons),
//            ]);
//        }

        if ($request->bearerToken()) {
            $user = User::where('token', $request->bearerToken())->first();
            $groupUser = GroupUser::where('user_id', $user['id'])->first();
            if ($groupUser) {
                if ($courseCat) {
                    $course = Course::where('id', $courseCat['course_id'])->first();
                    if ($course['free'] == true) {
                        $lessons = Lesson::where('course_id', $course['id'])->where('course_category_id', $request['course_category_id'])
                            ->get();
                        if (count($lessons) != 0) {
                            return response()->json([
                                'statusCode' => 200,
                                'data' => LessonResource::collection($lessons),
                            ]);
                        }
                        return response()->json([
                            'statusCode' => 400,
                            'message' => 'Lessons not found!'
                        ]);
                    } else {
                        $userLesson = UserLesson::where('course_category_id', $request['course_category_id'])->
                        whereNotNull('comment')->where('accepted', true)->first();
                        if ($userLesson) {
                            $lessons = Lesson::where('course_id', $userLesson['course_id'])->where('course_category_id', $request['course_category_id'])->get();
                            return response()->json([
                                'statusCode' => 200,
                                'data' => LessonResource::collection($lessons),
                            ]);
                        } else {
                            $userLesson = UserLesson::where('course_category_id', $request['course_category_id'])->first();
                            $lessons = Lesson::where('course_id', $userLesson['course_id'])->where('course_category_id', $request['course_category_id'])
                                ->where('comment', 1)->where('hidden', 1)->get();
                            if (count($lessons) != 0) {
                                return response()->json([
                                    'statusCode' => 200,
                                    'data' => LessonResource::collection($lessons),
                                ]);
                            }
                            return response()->json([
                                'statusCode' => 400,
                                'message' => 'Lessons with available comment not found!'
                            ]);
                        }
                    }
                }
            }
        }

        if ($courseCat) {
            $course = Course::where('id', $courseCat['course_id'])->first();
            if ($course['free'] == true) {
                $lessons = Lesson::where('course_id', $course['id'])->where('course_category_id', $request['course_category_id'])->get();
                if (count($lessons) != 0) {
                    return response()->json([
                        'statusCode'    =>  200,
                        'data'          =>  LessonResource::collection($lessons),
                    ]);
                }
                return response()->json([
                    'statusCode'    =>  400,
                    'message'          =>  'Lessons not found!'
                ]);
            }
            else {
                $lessons = Lesson::where('course_id', $course['id'])->where('course_category_id', $request['course_category_id'])
                    ->where('comment', 1)->where('hidden', 1)->get();
                if (count($lessons) != 0) {
                    return response()->json([
                        'statusCode'    =>  200,
                        'data'          =>  LessonResource::collection($lessons),
                    ]);
                }
                return response()->json([
                    'statusCode'    =>  400,
                    'message'          =>  'Lessons with available comment not found!'
                ]);
            }
        }

        return response()->json([
            'statusCode'    =>  400,
            'message'          =>  'Lessons not found!'
        ]);
    }

    public function byCategoryLesson(Request $request)
    {
        $request->validate([
            'course_category_id'     =>  'required|exists:course_categories,id',
        ]);
        $user = $request['user'];

        try {
            $courseCategory = CourseCategory::where('id', $request['course_category_id'])
            ->with([
                'userCourse' => function($query) use ($user) {
                    $query->where('user_id', $user['id']);
                },
                'userCourse.userLessons' => function($query) use($user, $request) {
                    $query->where([
                        'user_id' => $user['id'], 
                        'course_category_id' => $request['course_category_id'],
                        'hidden' => 1
                    ]);
                }])->first();
            if ($courseCategory->userCourse) {
                return response()->json([
                    'status_code' => 200,
                    'data' => count($courseCategory->userCourse->userLessons) > 0 
                                ?  UserLessonResource::collection($courseCategory->userCourse->userLessons) 
                                : '',
                ]);
            }
            $lessons = Lesson::where('course_category_id', $request['course_category_id'])
                        ->where('hidden', true)
                        ->orderBy('created_at', 'asc')
                        ->get();

            return response()->json([
                'statusCode'    =>  200,
                'data'       =>  SecondLessonResource::collection($lessons),
            ]);
        }catch(Exception $e) {
            return response()->json(['status_code' => 500,]);
        }
        
    }

    public function postComment(Request $request)
    {
        $rules = [
            'lesson_id'     =>  'required|exists:lessons,id',
            'comment'       =>  'required',
        ];
        $messages = [];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json($validator->errors()->first(),400,['charset'=>'utf-8'],JSON_UNESCAPED_UNICODE);
        }
        $user = $request['user'];
        $userLesson = UserLesson::where('user_id', $user['id'])->where('lesson_id', $request['lesson_id'])->first();
        if ($userLesson) {
            $userLesson->comment = $request['comment'];
            $userLesson->save();

            return response()->json([
                'data'          =>  $userLesson,
            ]);
        }
        return response()->json([
            "user lesson not found"
        ],400);
    }
}
