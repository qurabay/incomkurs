@extends('admin.layouts.app')

@section('content')
    <ul class="nav nav-tabs tab-nav-right" role="tablist">
        <li role="presentation" class="active"><a href="#lesson" data-toggle="tab" aria-expanded="true">Урок</a></li>
{{--        <li role="presentation"><a href="#homework" data-toggle="tab" >Домашная задания</a></li>--}}
    </ul>

    <form action="{{route('admin.lesson.update',$lesson->id)}}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade active in" id="lesson">
                <div class="form-group">
                    <label>Название</label>
                    <input type="text"  class="form-control" name="title" value="{{$lesson->title}}">
                </div>
                <div class="form-group">
                    <label>Видео фон</label>
                    <input type="file"  name="video_fon">
                </div>
                <div class="form-group">
                    <label>Аудиозаписи</label>
                    <input type="file"  name="audios[]" multiple>
                    <br>
                    @if($lesson['audios'])
                        <p style="color: #20a0ff">{{$lesson->audios[0]}}</p>
                    @else
                        <p>No</p>
                    @endif
                </div>



                <div class="form-group">
                    <label>PDF</label>
                    <input type="file" multiple  name="pdf[]" accept="application/pdf">
                </div>

                <div class="form-group">
                    @foreach(\App\Models\LessonPdf::where('lesson_id',$lesson->id)->get() as $k => $l)
                        {{$k + 1}})
                        <a class="btn btn-primary" href="{{asset($l->path)}}">
                            {{$l->path}}
                        </a>
                        <a class="btn btn-danger" href="{{route('admin.lesson.pdfDelete',$l->id)}}">Удалить </a><br>
                    @endforeach
                </div>
                
                <div class="form-group">
                    <label>Описания</label>
                    <textarea name="description"  style="height: 150px" class="form-control editor" placeholder="Описания">{{$lesson->description}}</textarea>
                </div>

            </div>
            <div role="tabpanel" class="tab-pane fade" id="homework">
                <div class="form-group">
                    <label>Аудиозаписи</label>
                    <input type="file"  name="homework_audios[]" multiple>
                </div>
                <div class="form-group">
                    <label>Домашная задания </label>
                    <textarea name="homework" style="height: 150px" class="form-control editor" placeholder="Домашная задания ">{{$lesson->homework}}</textarea>
                </div>
            </div>
            <div class="form-group">
                 <label>Дата</label>
                <input type="datetime-local" name="date" step="1">
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Добавить</button>
        </div>
    </form>

@endsection
