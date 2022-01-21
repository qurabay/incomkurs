@extends('admin.layouts.app')

@section('content')
    <form action="{{route('admin.question-answer.store')}}" enctype="multipart/form-data" method="post">
        @csrf



        <div class="form-group">
            <label>Вопрос</label>
            <textarea name="question" style="height: 150px" class="form-control" placeholder="Вопрос"></textarea>
        </div>

        <div class="form-group">
            <label>Ответ</label>
            <textarea name="answer" style="height: 150px" class="form-control" placeholder="Ответ"></textarea>
        </div>


        <div class="form-group">
            <button class="btn btn-primary" type="submit">Добавить</button>
        </div>
    </form>
@endsection

