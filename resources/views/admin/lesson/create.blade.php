@extends('admin.layouts.app')

@section('content')
    <div class="body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs tab-nav-right" role="tablist">
            <li role="presentation" class="active"><a href="#lesson" data-toggle="tab" aria-expanded="true">Урок</a></li>
            <li role="presentation"><a href="#homework" data-toggle="tab" >Домашная задания</a></li>
        </ul>
        <br>
        <form action="{{route('admin.lesson.store',$course_category->id)}}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="tab-content">
                <div role="tabpanel" class="tab-pane fade active in" id="lesson">
                    <div class="form-group">
                        <label>Название</label>
                        <input type="text"  class="form-control" name="title" required>
                    </div>
{{--                    <div class="form-group">--}}
{{--                        <label>Видео ссылка</label>--}}
{{--                        <input type="text" placeholder="https://youtu.be/ar31DrNV_pM"   class="form-control" name="video_url" required>--}}
{{--                    </div>--}}
                    <div class="form-group">
                        <label>Видео фон</label>
                        <input type="file" name="video_fon">
                    </div>
                    <div class="form-group">
                        <label>PDF</label>
                        <input type="file" multiple  name="pdf[]" accept="application/pdf">
                    </div>
                    <div class="form-group">
                        <label>Аудиозаписи</label>
                        <input type="file"  name="audios[]" multiple>
                    </div>



                    <div class="form-group">
                        <label>Описания</label>
                        <textarea name="description"  style="height: 150px" class="form-control editor" placeholder="Описания"></textarea>
                    </div>

                </div>
                <div role="tabpanel" class="tab-pane fade" id="homework">
                    <div class="form-group">
                        <label>Аудиозаписи</label>
                        <input type="file"  name="homework_audios[]" multiple>
                    </div>
                    <div class="form-group">
                        <label>Домашная задания </label>
                        <textarea name="homework" style="height: 150px" class="form-control editor" placeholder="Домашная задания "></textarea>
                    </div>

                </div>
        </div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit">Добавить</button>
            </div>
        </form>

    </div>
@endsection


@push('js')

@endpush
