@extends('admin.layouts.app')

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Добавить пользователя в группу - {{$group->title}}</h2>
                </div>
                <div class="body">
                    <form action="{{route('admin.group.load-user', $group->id)}}" method="post">
                        @csrf
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Имя</th>
                                    <th>Логин</th>
                                    <th>Пароль</th>
                                    <th>Регистрация</th>
                                    <th>Был онлайн</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $k=>$user)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="users[]" value="{{$user->id}}">
                                        </td>
                                        <td>{{$user->id}}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->email}}</td>
                                        <td>{{$user->password}}</td>
                                        <td>{{$user->created_at}}</td>
                                        <td>{{$user->last_login}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <button type="submit" onclick="return confirm('Добавить?')" class="btn btn-primary">Добавить</button>
                        </div>
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

