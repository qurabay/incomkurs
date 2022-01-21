@extends('admin.layouts.app')

@section('content')
    <form action="{{route('admin.category_course.update', $category->id)}}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade active in" id="lesson">
                <div class="form-group">
                    <label>Название</label>
                    <input type="text"  class="form-control" name="title" value="{{$category->title}}" required>
                </div>

{{--                <div class="form-group">--}}
{{--                    <label>Описание</label>--}}
{{--                    <input type="text"  class="form-control" name="description" value="{{$category->description}}" required>--}}
{{--                </div>--}}
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Добавить</button>
        </div>
    </form>

@endsection
