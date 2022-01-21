@extends('admin.layouts.app-moderator')

@section('content')
    <form action="{{route('admin.course.usersImport')}}" enctype="multipart/form-data" method="post">
        @csrf
        <input type="hidden" name="course_id" value="{{$course_id}}">
        <div class="form-group">
            <label>Excel файл</label>
            <input type="file"  class="form-control" name="file" required accept=".xlsx,.csv,.xls">
        </div>


        <div class="form-group">
            <button class="btn btn-primary" type="submit">Импортировать</button>
        </div>
    </form>
@endsection

