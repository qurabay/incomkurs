@extends('admin.layouts.app-moderator')

@section('content')
    <table class="table">
        <thead>
        <tr>
            <th>#</th>
            <th>Имя</th>
            <th>Логин</th>
            <th>Комментарии</th>
            <th>Когда</th>
            <th>Курс</th>
            <th>Урок</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($userLessons as $userLesson)
            <tr>
                <td>{{$userLesson->id}}</td>
                <td>{{App\Models\User::whereId($userLesson->user_id)->value('name')}}</td>
                <td>{{App\Models\User::whereId($userLesson->user_id)->value('email')}}</td>
                <td>{{$userLesson->comment}}</td>
                <td>{{$userLesson->opened}}</td>
                <td>{{App\Models\Course::where('id', $userLesson->course_id)->value('title')}}</td>
                <td>{{App\Models\Lesson::where('id', $userLesson->lesson_id)->value('title')}}</td>
                <td>
                    @if($userLesson->accepted == false)
                        <a href="{{route('admin.accept', $userLesson->id)}}" class="waves-effect btn btn-primary">
                            Принять
                        </a>
                    @else
                        <p>Принят</p>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection



