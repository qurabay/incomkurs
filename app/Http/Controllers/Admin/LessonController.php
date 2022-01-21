<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Lesson;
use App\Models\UserCourse;
use App\Models\UserLesson;
use App\Models\LessonPdf;
use App\Models\PostCat;
use App\Packages\Firebase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use File;
use Illuminate\Support\Str;
class LessonController extends Controller
{
    public function index($id)
    {
        $data['lessons'] = Lesson::where('course_category_id',$id)->orderBy('created_at','asc')->paginate(15);
        $data['course_category'] = CourseCategory::find($id);
        $data['course'] = Course::find($data['course_category']->course_id);
        if (session()->has('admin')) {
            return view('admin.lesson.index', $data);
        }
        if (session()->has('moderator')) {
            return view('admin.lesson-moderator.index', $data);
        }
    }
    public function create($id)
    {
        $data['course_category'] = CourseCategory::find($id);
        $data['course'] = Course::find($data['course_category']->course_id);
        if (session()->has('admin')) {
            return view('admin.lesson.create',$data);
        }
        if (session()->has('moderator')) {
            return view('admin.lesson-moderator.create', $data);
        }
    }
    public function store($id, Request $request)
    {
        $rules = [
            'title'=> 'required',
        ];
        $messages = [

        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }
        $c = new Lesson();

        $text = str_replace('<iframe src="//www','<iframe src="https://www',$request['description']);
        $text = str_replace('../../../','http://185.100.67.139/',$text);
        $text = str_replace('<video controls="controls" width="300" height="150">','<video controls="controls" width="300" height="150" controlsList="nodownload">',$text);

        $courseCat = CourseCategory::find($id);
        $course = Course::find($courseCat['course_id']);
        $c->title = $request['title'];
        $c->course_id = $course['id'];
        $c->course_category_id = $id;
        $c->description =$text;
        $text = str_replace('<iframe src="//www','<iframe src="https://www',$request['homework']);
        $text = str_replace('../../../','http://185.100.67.139',$text);
        $text = str_replace('<video controls="controls" width="300" height="150">','<video controls="controls" width="300" height="150" controlsList="nodownload">',$text);

        $c->homework = $text;

        $c->video_url = $request['video_url'];
        $c->video_fon = $this->uploadFile($request['video_fon']);
        $c->save();

        if ($request['pdf']){
            foreach ($request['pdf'] as $item) {
                $pdf = new LessonPdf();
                $pdf->path = $this->uploadFile($item);
                $pdf->lesson_id = $c->id;
                $pdf->save();
            }
        }

        if ($request['audios']){
            $audios = [];
            foreach ($request['audios'] as $audio){
                $audios[] = $this->uploadFile($audio);
            }
            $c->audios = $audios;
        }

        if ($request['homework_audios']){
            $audios = [];
            foreach ($request['homework_audios'] as $audio){
                $audios[] = $this->uploadFile($audio);
            }
            $c->homework_audios = $audios;
        }

        $c->save();

        $users = UserCourse::where('course_id', $courseCat['course_id'])->get();
        foreach ($users as $user) {
            UserLesson::insert([
                'user_id'   =>  $user['user_id'],
                'group_id'  =>  $user['group_id'],
                'course_id' =>  $courseCat['course_id'],
                'course_category_id'    =>  $id,
                'lesson_id' =>  $c->id,
            ]);
        }

        return redirect()->route('admin.lesson.index', $id);
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
            return view('admin.lesson.edit',['lesson'=>Lesson::findOrFail($id)]);
        }
        if (session()->has('moderator')) {
            return view('admin.lesson-moderator.edit',['lesson'=>Lesson::findOrFail($id)]);
        }
    }

    public function update($id,Request $request)
    {
        $rules = [
            'title'=> 'required',
            'video_fon'=> 'image',
//            'pdf'=> 'array',
        ];
        $messages = [

        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $c = Lesson::findOrFail($id);

        $text = str_replace('<iframe src="//www','<iframe src="https://www',$request['description']);
        $text = str_replace('../../../','http://185.100.67.139/',$text);
        $text = str_replace('<video controls="controls" width="300" height="150">','<video controlsList="nodownload" controls="controls" width="300" height="150">',$text);

        $c->description = $text;
        $c->title = $request['title'];


        $text = str_replace('<iframe src="//www','<iframe src="https://www',$request['homework']);
        $text = str_replace('../../../','http://185.100.67.139/',$text);
        $text = str_replace('<video controls="controls" width="300" height="150">','<video controlsList="nodownload" controls="controls" width="300" height="150" >',$text);

        $c->homework = $text;
        $c->video_url = $request['video_url'];

        if ($request['video_fon']){
            $c->video_fon = $this->uploadFile($request['video_fon']);
        }
//        if ($request['video_fon']){
//            $c->pdf = $this->uploadFile($request['pdf']);
//        }

        if ($request['audios']){
//            if ($c->audios){
//                foreach ($c->audios as $audio) {
//                    $this->deleteFile($audio);
//                }
//            }
            $audios = [];
            foreach ($request['audios'] as $audio){
                $audios[] = $this->uploadFile($audio);
            }
            $c->audios = $audios;
        }
        if ($request['homework_audios']){
            if ($c->homework_audios){
                foreach ($c->homework_audios as $audio) {
                    $this->deleteFile($audio);
                }
            }
            $audios = [];
            foreach ($request['homework_audios'] as $audio){
                $audios[] = $this->uploadFile($audio);
            }
            $c->homework_audios = $audios;
        }
        if ($request['date']) {
            $time = Carbon::createFromFormat('Y-m-d\TH:i:s', $request['date']);
            $c->created_at = $time;
            $c->updated_at = $time;
        }

        if ($request['pdf']){
            LessonPdf::where('lesson_id',$c->id)->delete();

            foreach ($request['pdf'] as $item) {
                $pdf = new LessonPdf();
                $pdf->path = $this->uploadFile($item);
                $pdf->lesson_id = $c->id;
                $pdf->save();
            }
        }
//        if ($request['pdf']){
//            foreach ($request['pdf'] as $item) {
//                $c->pdf = $this->uploadFile($item);
//            }
//        }
        $c->save();

        return redirect()->route('admin.lesson.index',$c->course_category_id);
    }
    public function destroy($id)
    {
        $l = Lesson::findOrFail($id);
        if ($l->video_fon) {
            $this->deleteFile($l->video_fon);
        }
        $userLessons = UserLesson::where('lesson_id', $id)->get();
        if (count($userLessons) != 0) {
            foreach ($userLessons as $userLesson) {
                $userLesson->delete();
            }
        }
        $l->delete();
        return redirect()->back();
    }

    public function pdfDelete($id)
    {
        $l = LessonPdf::findOrFail($id);
        $l->delete();
        return redirect()->back();
    }

    function hidden($id)
    {
        $ls = Lesson::find($id);
        $ls->hidden = !$ls->hidden;
        $ls->save();

        $lessons = UserLesson::where('lesson_id', $id)->get();
        foreach ($lessons as $lesson) {
            $lesson->hidden = !$lesson->hidden;
            $lesson->save();
        }

        return redirect()->back();
    }

    function comment($id)
    {
        $ls = Lesson::find($id);
        $ls->comment = !$ls->comment;
        $ls->save();

        $lessons = UserLesson::where('lesson_id', $id)->get();
        foreach ($lessons as $lesson) {
            $lesson->comment = !$lesson->comment;
            $lesson->save();
        }

        return redirect()->back();
    }

    public function beFirst($id)
    {
        $lesson = Lesson::find($id);
        $lesson->created_at = Carbon::now();
        $lesson->updated_at = Carbon::now();
        $lesson->save();

        return redirect()->back();
    }
}

