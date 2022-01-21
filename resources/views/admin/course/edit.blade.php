@extends('admin.layouts.app')

@section('content')
    <form action="{{route('admin.course.update',$course->id)}}" enctype="multipart/form-data" method="post" novalidate>
        @csrf
        <div class="form-group">
            <label>Заголовок</label>
            <input type="text"  class="form-control" name="title" value="{{$course->title}}" required>
        </div>
        <div class="form-group">
            <label>Статус</label>
            <input type="text"  class="form-control" name="author" value="{{$course->author}}" required>
        </div>
        <div class="form-group">
            <label>Тип</label>
            <select class="form-control" name="free" id="free">
                <option {{$course->free == 0 ? 'selected' : ''}} value="0">Ақылы</option>
                <option {{$course->free == 1 ? 'selected' : ''}} value="1">Тегін</option>
            </select>
        </div>
        <div class="form-group">
            <label>Цена (тенге)</label>
            <input type="number"  class="form-control" min="0" name="price"  value="{{$course->price}}" required>
        </div>

        <div class="form-group">
            <label>Дата окончания</label>
            <input type="datetime-local"  class="form-control"  name="deadline" value="{{ \Carbon\Carbon::parse($course->deadline)->format('Y-m-d\TH:i')}}" required>
        </div>

        <div class="form-group">
            <label>Описания</label>
            <textarea  name="description"  style="height: 150px" class="form-control editor" placeholder="Описания">{{$course->description}}</textarea>
        </div>

        <div class="form-group">
            <label>Фото</label>
            <img style="margin: 0 0 30px 30px;" width="300" src="{{asset($course->image)}}" alt="">
            <input type="file"  name="image">
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Сохранить</button>
        </div>
    </form>
@endsection

