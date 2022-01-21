@extends('admin.layouts.app')

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>{{$course->title}}</h2>
                </div>
                <div class="body">
                    <a href="{{route('admin.lesson.create',$course_category->id)}}" class="btn btn-success btn-circle waves-effect waves-circle waves-float m-t--10 pull-right">
                        <i class="material-icons m-t-5">add</i>
                    </a>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Название</th>
                                <th>Дата</th>
{{--                                <th>Видимость</th>--}}
{{--                                <th>Комментарии</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lessons as $lesson)
                                <tr>
                                    <td>{{$lesson->id}}</td>
                                    <td>{{$lesson->title}}</td>
                                    <td>
                                        {{$lesson->created_at}}
                                    </td>
{{--                                    <td>--}}
{{--                                        @if($lesson->hidden)--}}
{{--                                            <a href="{{route('admin.lesson.hidden',$lesson->id)}}" class=" waves-effect btn btn-primary">--}}
{{--                                                <i class="material-icons">visibility</i>--}}
{{--                                            </a>--}}
{{--                                        @else--}}
{{--                                            <a href="{{route('admin.lesson.hidden', $lesson->id)}}" class="waves-effect btn btn-danger">--}}
{{--                                                <i class="material-icons">visibility_off</i>--}}
{{--                                            </a>--}}
{{--                                        @endif--}}
{{--                                    </td>--}}
{{--                                    <td>--}}
{{--                                        @if($lesson->comment)--}}
{{--                                            <a href="{{route('admin.lesson.comment',$lesson->id)}}" class=" waves-effect btn btn-primary">--}}
{{--                                                <i class="material-icons">visibility</i>--}}
{{--                                            </a>--}}
{{--                                        @else--}}
{{--                                            <a href="{{route('admin.lesson.comment', $lesson->id)}}" class="waves-effect btn btn-danger">--}}
{{--                                                <i class="material-icons">visibility_off</i>--}}
{{--                                            </a>--}}
{{--                                        @endif--}}
{{--                                    </td>--}}

                                    <td>
                                        <a href="{{route('admin.lesson.edit',$lesson->id)}}" class="waves-effect btn btn-success"><i class="material-icons">mode_edit</i></a>
                                        <a href="{{route('admin.lesson.destroy',$lesson->id)}}" class="waves-effect btn btn-danger"><i class="material-icons">delete</i></a>
{{--                                        <a href="{{route('admin.lesson.beFirst', $lesson->id)}}" class="waves-effect btn btn-warning">Сделать первым</a>--}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{$lessons->links()}}

            </div>
        </div>
    </div>

@endsection

