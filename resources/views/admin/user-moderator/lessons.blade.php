@extends('admin.layouts.app-moderator')

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">Уроки</div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Название</th>
                                <th>Описание</th>
                                <th>Когда открыл</th>
                                <th>Был открыт</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($lessons as $course)
                                <tr>
                                    <td>{{$course->id}}</td>
                                    <td>{{$course->title}}</td>
                                    <td>{{$course->description}}</td>
                                    <td>{{$course->opened}}</td>
                                    @if($course->isOpened == true)
                                        <td>Да</td>
                                    @else
                                        <td>Нет</td>
                                    @endif

                                </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
