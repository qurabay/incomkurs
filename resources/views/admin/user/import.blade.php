@extends('admin.layouts.app')

@section('content')
    <form action="{{route('admin.user.import')}}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="form-group">
            <label>Excel файл</label>
            <input type="file"  class="form-control" name="file" required accept=".xlsx,.csv,.xls">
        </div>


        <div class="form-group">
            <button class="btn btn-primary" type="submit">Импортировать</button>
        </div>
    </form>
@endsection

