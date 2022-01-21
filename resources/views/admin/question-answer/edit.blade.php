@extends('admin.layouts.app')

@section('content')
    <form action="{{route('admin.question-answer.update',$item->id)}}" enctype="multipart/form-data" method="post">
        @csrf

        <div class="form-group">
            <label>Вопрос</label>
            <textarea name="question" style="height: 150px" class="form-control" placeholder="Вопрос">{{$item->question}}</textarea>
        </div>

        <div class="form-group">
            <label>Ответ</label>
            <textarea name="answer" style="height: 150px" class="form-control" placeholder="Ответ">{{$item->answer}}</textarea>
        </div>

        <div class="form-group">
            <button class="btn btn-primary" type="submit">Сохранить</button>
        </div>
    </form>
@endsection

