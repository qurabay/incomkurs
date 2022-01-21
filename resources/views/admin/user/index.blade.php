@extends('admin.layouts.app')

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header" style="padding-bottom: 40px">
                    <h2 >Пользователи</h2>
                    <form  action="{{route('admin.user.index')}}">
                        @csrf
                        <input type="search" name="search" value="{{$search}}" placeholder="текст поиска...">
                        <button>Поиск</button>

                    </form>
                   <div>
                       <a href="{{route('admin.user.deleteAll')}}" onclick="return confirm('Удалить всех пользователей?')" class="btn btn-danger btn-circle waves-effect waves-circle waves-float m-t--10 pull-right">
                           <i class="material-icons m-t-5">delete</i>
                       </a>
                       <a href="{{route('admin.user.create')}}" class="btn btn-success btn-circle waves-effect waves-circle waves-float m-t--10 pull-right">
                           <i class="material-icons m-t-5">add</i>
                       </a>
                        <a href="{{route('admin.group.create')}}" class="btn btn-primary btn-circle waves-effect waves-circle waves-float m-t--10 pull-right">
                            <i class="material-icons m-t-5">group add</i>
                        </a>
                   </div>
                </div>

                <div class="body">
                    <form action="{{route('admin.user.deleteCheckbox')}}">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>#</th>
{{--                                <th>Телефон номер</th>--}}
                                <th>Имя</th>
                                <th>Логин</th>
                                <th>Пароль</th>
                                <th>Курсы</th>
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
{{--                                    <td>{{$user->phone}}</td>--}}
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->password}}</td>
                                    <td>
                                        <ul>
                                       
                                            @foreach($user->userLessons as $lesson) 
                                                    @foreach($lesson->lessons as $item)
                                                        <li>{{ $item->title }}</li>
                                                    @endforeach
                                            @endforeach     
                                      
                                        </ul>
                                    </td>
                                    <td>{{$user->created_at}}</td>
                                    <td>{{$user->last_login}}</td>
                                    <td>
{{--                                        <a href="" class=" waves-effect btn btn-primary"><i class="material-icons">visibility</i></a>--}}
                                        <a href="{{route('admin.user.edit',$user->id)}}" class="waves-effect btn btn-success"><i class="material-icons">mode_edit</i></a>
                                        <a href="{{route('admin.user.destroy',$user->id)}}" class="waves-effect btn btn-danger"><i class="material-icons">delete</i></a>
                                        <a href="{{route('admin.user.process', $user->id)}}" class=" waves-effect btn btn-primary"><i class="material-icons">visibility</i></a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <button type="submit" onclick="return confirm('Удалить ?')" class="btn btn-danger"> удалить отмеченные</button>


                    </div>
                    </form>
                </div>
                {{$users->links()}}

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

@push('js')
        <script>
           
        </script>
@endpush

