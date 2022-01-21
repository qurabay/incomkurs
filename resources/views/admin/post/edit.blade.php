@extends('admin.layouts.app')

@section('content')
    <form action="{{route('admin.post.update',$post->id)}}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="form-group">
            <label>Категория</label>
            <select name="cat_id" class="form-control" >
                @foreach($cats as $cat)
                    <option {{$cat->id == $post->cat_id ? 'selected':''}} value="{{$cat->id}}">{{$cat->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Заголовок</label>
            <input type="text"  class="form-control" name="title" value="{{$post->title}}" required>
        </div>


        <div class="form-group">
            <label>Описание</label>
            <textarea id="editor" name="description" style="height: 150px" class="form-control editor" placeholder="Описания">{{$post->description}}</textarea>
        </div>

        <div>
            @foreach($post->images as $image)
                <img style="margin: 0 20px 20px 0;" width="200" src="{{asset($image)}}" alt="">
            @endforeach
        </div>

        <div class="form-group">
            <button class="btn btn-primary" type="submit">Сохранить</button>
        </div>
    </form>
@endsection

