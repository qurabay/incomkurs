<?php

namespace App\Http\Controllers\Admin;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\QuestionAnswer;
use App\Packages\Firebase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QuestionAnswerController extends Controller
{

    public function index()
    {
        $data['items'] = QuestionAnswer::orderBy('created_at','desc')->paginate(15);
        return view('admin.question-answer.index', $data);
    }
    public function create()
    {
        return view('admin.question-answer.create');
    }
    public function store(Request $request)
    {
        $rules = [
            'question'=> 'required',
            'answer'=> 'required',
        ];
        $messages = [

        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $c = new QuestionAnswer();
        $c->question =  str_replace('<iframe src="//www','<iframe src="https://www',$request['question']);
        $c->answer =  str_replace('<iframe src="//www','<iframe src="https://www',$request['answer']);
        $c->save();



        Firebase::send('newQuestionAnswer',[
            'title' =>   $c->question,
            'body' =>  '',
            'sound'=>'default',
            'type'=> 'questionAnswer',
            'id' => $c->id
        ]);





        return redirect()->route('admin.question-answer.index');
    }

    public function show($id)
    {
        return view('admin.question-answer.show',['item'=>QuestionAnswer::findOrFail($id)]);
    }
    public function edit($id, Request $request)
    {
        return view('admin.question-answer.edit',['item'=>QuestionAnswer::findOrFail($id)]);

    }
    public function update($id,Request $request)
    {
        $rules = [
            'question'=> 'required',
            'answer'=> 'required',
        ];
        $messages = [

        ];
        $validator = $this->validator($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $c = QuestionAnswer::findOrFail($id);
        $c->question = str_replace('<iframe src="//www','<iframe src="https://www',$request['question']);
        $c->answer =str_replace('<iframe src="//www','<iframe src="https://www',$request['answer']);
        $c->save();

        return redirect()->route('admin.question-answer.index',$c->course_id);
    }
    public function destroy($id)
    {
        $l = QuestionAnswer::findOrFail($id);
        $l->delete();
        return redirect()->back();
    }
}
