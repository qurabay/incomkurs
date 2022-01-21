@extends('admin.layouts.app-moderator')

@section('content')
    <form action="{{route('admin.post.store')}}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="form-group">
            <label>Категория</label>
            <select name="cat_id" class="form-control" >
                @foreach($cats as $cat)
                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Заголовок</label>
            <input type="text"  class="form-control" name="title" required>
        </div>


        <div class="form-group">
            <label>Описание</label>
            <textarea id="editor" name="description" style="height: 150px" class="form-control editor" placeholder="Описания"></textarea>
        </div>

        <div class="form-group">
            <label>Фото</label>
            <input type="file" multiple required name="images[]">
        </div>

        <div class="form-group">
            <button class="btn btn-primary" type="submit">Создать Пост</button>
        </div>
    </form>
@endsection

@push('js')

@endpush

