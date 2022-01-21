@extends('admin.layouts.app-moderator')

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Пользователи группы - {{$group->title}}</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <form action="{{route('admin.group.student-delete', $group->id)}}">
                            @csrf
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Имя</th>
                                    <th>Логин</th>
                                    <th>Номер</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="users[]" value="{{$user->user_id}}">
                                        </td>
                                        <td>{{$user->user_id}}</td>
                                        <td>{{App\Models\User::where('id', $user->user_id)->value('name')}}</td>
                                        <td>{{App\Models\User::where('id', $user->user_id)->value('email')}}</td>
                                        <td>{{App\Models\User::where('id', $user->user_id)->value('phone')}}</td>
                                        {{--                                        <td>--}}
                                        {{--                                            <a href="{{route('admin.group.student-delete', ['user_id' => $user->user_id, 'group_id' => $group->id])}}" onclick="return confirm('Удалить пользователя?')" class="waves-effect btn btn-danger"><i class="material-icons">delete</i></a>--}}
                                        {{--                                        </td>--}}
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-danger">Удалить пользователей</button>
                            <a href="{{route('admin.group.student-add', ['group_id' => $group->id])}}" class="btn btn-success">Добавить пользователя</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .header {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
        }

        .header a {
            grid-column: end;
        }

        .header form input{
            border:1px solid #efefef;
            height: 30px;
            padding: 0px 5px;
        }
        .header form button{
            border:1px solid #efefef;
            height: 30px;
            padding: 0px 5px;
        }

        [type="checkbox"]:not(:checked), [type="checkbox"]:checked{
            position: static;
            opacity: 1;
        }
    </style>
@endsection

