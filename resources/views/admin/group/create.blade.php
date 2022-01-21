@extends('admin.layouts.app')

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header" style="padding-bottom: 40px">
                    <h2 >Пользователи</h2>
                </div>

                <div class="body">
                    <form action="{{route('admin.group.add-group')}}">
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

                                <div class="form-group">
                                    <label>Название</label>
                                    <input type="text"  class="form-control" name="title" >
                                </div>
                                <div class="form-group">
                                    <label>Курс</label>
                                    <select class="form-control" name="course">
                                        @foreach($courses as $course)
                                            <option value="{{$course->id}}">{{$course->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
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
                            <button type="submit" onclick="return confirm('Добавить?')" name="action" value="one" class="btn btn-primary">Добавить</button>
                            <button type="submit" name="action" value="all" class="btn btn-success">Добавить всех</button>
                        </div>
                    </form>
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

