@extends('admin.layouts.app-moderator')

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>{{$course->title}}</h2>
                </div>
                <div class="body">
{{--                    <a href="{{route('admin.lesson.create',$course->id)}}" class="btn btn-success btn-circle waves-effect waves-circle waves-float m-t--10 pull-right">--}}
{{--                        <i class="material-icons m-t-5">add</i>--}}
{{--                    </a>--}}
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Название</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lessons as $lesson)
                                <tr>
                                    <td>{{$lesson->id}}</td>
                                    <td>{{$lesson->title}}</td>
                                    <td>
{{--                                        <a href="" class=" waves-effect btn btn-primary"><i class="material-icons">visibility</i></a>--}}
                                        <a href="{{route('admin.lesson.edit',$lesson->id)}}" class="waves-effect btn btn-success"><i class="material-icons">mode_edit</i></a>
                                        <a href="{{route('admin.lesson.destroy',$lesson->id)}}" class="waves-effect btn btn-danger"><i class="material-icons">delete</i></a>
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

