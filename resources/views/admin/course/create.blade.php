@extends('admin.layouts.app')

@section('content')
    <form action="{{route('admin.course.store')}}" enctype="multipart/form-data" method="post" novalidate>
        @csrf
        <div class="form-group">
            <label>Заголовок</label>
            <input type="text"  class="form-control" name="title" required>
        </div>
        <div class="form-group">
            <label>Статус</label>
            <input type="text"  class="form-control" name="author" required>
        </div>
        <div class="form-group">
            <label>Тип</label>
            <select class="form-control" name="free" id="free">
                <option value="0">Ақылы</option>
                <option value="1">Тегін</option>
            </select>
        </div>
        <div class="form-group">
            <label>Цена (тенге)</label>
            <input type="number"  class="form-control" min="0" name="price" required>
        </div>
        <div class="form-group">
            <label>Дата окончания</label>
            <input type="datetime-local"  class="form-control"  name="deadline" required>
        </div>
        <div class="form-group">
            <label>Описание</label>
            <textarea name="description"  style="height: 150px" class="form-control editor" placeholder="Описания"></textarea>
        </div>


        <div class="form-group">
            <label>Фото</label>
            <input type="file" required name="image">
        </div>
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Добавить</button>
        </div>
    </form>
@endsection

