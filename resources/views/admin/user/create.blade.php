@extends('admin.layouts.app')

@section('content')
    <form action="{{route('admin.user.store')}}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="form-group">
            <label>Имя</label>
            <input type="text"  class="form-control" name="name"  required>
        </div>

        <div class="form-group">
            <label>Логин</label>
            <input type="text"  class="form-control" name="email"  >
        </div>

        <div class="form-group">
            <label>Пароль</label>
            <input type="text" maxlength="14" placeholder="например: 1234"  class="form-control" name="password"  required>
        </div>

        <p>Группы</p>
        @foreach($groups as $group)
            <div>
                <input id="group_{{$group->id}}" type="checkbox" name="user_groups[]" value="{{$group->id}}">
                <label for="group_{{$group->id}}">{{$group->title}}</label>
            </div>
        @endforeach


        <div class="form-group">
            <button class="btn btn-primary" type="submit">Сохранить</button>
        </div>
    </form>
@endsection

