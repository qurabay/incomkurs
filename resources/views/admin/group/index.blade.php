@extends('admin.layouts.app')

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Группы</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Название</th>
                                <th>Курс</th>
                                <th>Количество студентов</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($groups as $group)
                                <tr>
                                    <td>{{$group->id}}</td>
                                    <td>{{$group->title}}</td>
                                    <td>{{App\Models\Course::where('id',$group->course_id)->value('title')}}</td>
                                    <td>{{count(App\Models\UserCourse::where('group_id', $group->id)->select('user_id')->groupBy('user_id')->get())}}</td>
                                    <td>
                                        <a href="{{route('admin.group.students', $group->id)}}" class="waves-effect btn btn-primary">Пользователи</a>
                                        <a href="{{route('admin.group.categories', $group->id)}}" class=" waves-effect btn btn-success">Категории</a>
                                        <a href="{{route('admin.group.delete',$group->id)}}" onclick="return confirm('Удалить эту группу?')" class="waves-effect btn btn-danger"><i class="material-icons">delete</i></a>
                                    </td>
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
