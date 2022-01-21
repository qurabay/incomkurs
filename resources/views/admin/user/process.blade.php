@extends('admin.layouts.app')

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">Купленные курсы</div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Фото</th>
                                    <th>Название</th>
                                    <th>Цена</th>
                                    <th>Дата окончания</th>
                                    <th>Когда был открыт</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach($courses as $course)
                                <tr>
                                    <td>{{$course->id}}</td>
                                    <td><img width="150px" src="{{asset($course->image)}}" alt=""></td>
                                    <td>{{$course->title}}</td>
                                    <td>{{$course->price}}</td>
                                    <td>{{$course->deadline}}</td>
                                    @if($course->opened != null)
                                        <td>{{$course->opened}}</td>
                                    @else
                                        <td>Еще не открыл!</td>
                                    @endif
                                    <td><a href="{{route('admin.user.userLessons', $course->user_course)}}" class=" waves-effect btn btn-primary"><i class="material-icons">visibility</i></a></td>
                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
