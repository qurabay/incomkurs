@extends('admin.layouts.app-moderator')

@section('content')
    <form action="{{route('admin.user.update',$user->id)}}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="form-group">
            <label>Имя</label>
            <input type="text"  class="form-control" name="name" value="{{$user->name}}" required>
        </div>
        <div class="form-group">
            <label>Логин</label>
            <input type="hidden"  class="form-control" name="phone" value="-" >
            <input type="text"  class="form-control" name="email" value="{{$user->email}}" required>

        </div>

        <div class="form-group">
            <label>Пароль</label>
            <input type="text"  class="form-control" name="password" value="{{$user->password}}" >
            <!-- <input type="text"  class="form-control" name="email" value="222" > -->

        </div>

        @foreach($courses as $course)
            <div>
                @php
                    $c = \App\Models\UserCourse::where('user_id',$user->id)->where('course_id',$course->id)->exists();
                @endphp

                <input {{$c ? 'checked':''}} id="course_{{$course->id}}" type="checkbox" name="user_courses[]" value="{{$course->id}}">
                <label for="course_{{$course->id}}">{{$course->title}}</label>
            </div>
        @endforeach


        <div class="form-group">
            <button class="btn btn-primary" type="submit">Сохранить</button>
            <a  class="btn btn-danger" href="{{route('admin.user.updateDevice',$user->id)}}">Обновить девайс</a>
        </div>
    </form>
@endsection

@push('js')
    <script>
        // $(function () {
        //     CKEDITOR.replace('ckeditor');
        //     CKEDITOR.config.height = 300;
        // })
    </script>
@endpush
