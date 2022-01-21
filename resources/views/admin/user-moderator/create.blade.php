@extends('admin.layouts.app-moderator')

@section('content')
    <form action="{{route('admin.user.store')}}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="form-group">
            <label>Имя</label>
            <input type="text"  class="form-control" name="name"  required>
        </div>
        {{--        <div class="form-group">--}}
        {{--            <label>Телефон номер (Без +7 и 8)</label>--}}
        {{--            <input type="text"  class="form-control" name="phone"  required>--}}
        {{--        </div>--}}

        <div class="form-group">
            <label>Логин</label>
            <input type="text"  class="form-control" name="email"  >
        </div>

        <div class="form-group">
            <label>Пароль</label>
            <input type="text" maxlength="14" placeholder="например: 1234"  class="form-control" name="password"  required>
        </div>


        @foreach($courses as $course)
            <div>
                <input id="course_{{$course->id}}" type="checkbox" name="user_courses[]" value="{{$course->id}}">
                <label for="course_{{$course->id}}">{{$course->title}}</label>
            </div>
        @endforeach


        <div class="form-group">
            <button class="btn btn-primary" type="submit">Сохранить</button>
        </div>
    </form>
@endsection

