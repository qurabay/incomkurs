@extends('admin.layouts.app-moderator')

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Название</th>
                                <th>Видимость</th>
                                {{--                                <th>Комментарии</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lessons as $lesson)
                                <tr>
                                    <td>{{$lesson['lesson_id']}}</td>
                                    <td>{{\App\Models\Lesson::where('id', $lesson['lesson_id'])->value('title')}}</td>
                                    <td>
                                        @if(App\Models\UserLesson::where('id', $lesson['id'])->value('hidden') == true)
                                            <a href="{{route('admin.group.hidden', ['id' => $lesson->id, 'group' => $group['id']])}}" class="waves-effect btn btn-primary">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                        @else
                                            <a href="{{route('admin.group.hidden', ['id' => $lesson->id, 'group' => $group['id']])}}" class="waves-effect btn btn-danger">
                                                <i class="material-icons">visibility_off</i>
                                            </a>
                                        @endif
                                    </td>

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

