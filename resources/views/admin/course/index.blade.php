@extends('admin.layouts.app')

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Курсы</h2>
                </div>
                <div class="body">
                    <a href="{{route('admin.course.create')}}" class="btn btn-success btn-circle waves-effect waves-circle waves-float m-t--10 pull-right">
                        <i class="material-icons m-t-5">add</i>
                    </a>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Фото</th>
                                <th>Название</th>
                                <th>Цена</th>
                                <th>Участники</th>
                                <th>Тип</th>
                                <th>Дата окончания</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($courses as $course)
                                <tr>
                                    <td>{{$course->id}}</td>
                                    <td><img width="150px" src="{{asset($course->image)}}" alt=""></td>
                                    <td>{{$course->title}}</td>
                                    <td>{{$course->price}}</td>
                                    <td><a href="{{route('admin.course.users',$course->id)}}">{{\App\Models\UserCourse::whereCourseId($course->id)->count()}}</a></td>
                                    <td>{{$course->free == 1 ? 'Тегін' : 'Ақылы'}}</td>
                                    <td>{{$course->deadline}}</td>
                                    <td>
                                        <a href="{{route('admin.category_course.index',$course->id)}}" class="m-t--10 waves-effect btn btn-primary">Уроки</a>
                                        <a href="{{route('admin.course.edit',$course->id)}}" class="waves-effect btn btn-success"><i class="material-icons">mode_edit</i></a>
                                        <a href="{{route('admin.course.destroy',$course->id)}}" onclick="return confirm('Удалить ?')" class="waves-effect btn btn-danger"><i class="material-icons">delete</i></a>
                                        <a href="{{route('admin.course.usersAdd',$course->id)}}" onclick="return confirm('добавить  всех пользователей?')" class="waves-effect btn btn-warning"><i class="material-icons">add</i></a>
                                        <a href="{{route('admin.course.usersDelete',$course->id)}}" onclick="return confirm('Удалить  всех пользователей?')" class="waves-effect btn btn-warning"><i class="material-icons">delete</i></a>
                                        <a href="{{route('admin.course.usersImport',['course_id'=>$course->id])}}"  class="waves-effect btn btn-primary"><i class="material-icons">note_add</i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{$courses->links()}}

            </div>
        </div>
    </div>

@endsection

