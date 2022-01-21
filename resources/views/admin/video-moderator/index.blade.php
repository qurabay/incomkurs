@extends('admin.layouts.app-moderator')

@section('content')
{{--    <div class="card">--}}
{{--        <div class="body">--}}
{{--            <form action="{{route('admin.video.store')}}" method="POST" enctype="multipart/form-data">--}}
{{--                @csrf--}}
{{--                <h4>загрузить видео</h4>--}}
{{--                <div class="form-group">--}}
{{--                    <input type="file"  name="video" required accept="video/*">--}}
{{--                </div>--}}
{{--                <button class="btn btn-primary" type="submit">загрузить</button>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="body">

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Ссылка</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($videos as $video)
                                <tr>
                                    <td>
                                        <video src="{{asset($video)}}" width="300" controls></video>
                                    </td>
                                    <td>
                                        <a href="{{asset($video)}}">{{asset($video)}}</a>
                                    </td>
                                    <td>
                                        <form method="post" action="{{route('admin.video.destroy')}}">
                                            @csrf
                                            <input type="hidden" name="path" value="{{$video}}">
                                            <button type="submit"><i class="material-icons">delete</i></button>
                                        </form>
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

