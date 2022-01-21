@extends('admin.layouts.app')

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Посты</h2>
                </div>
                <div class="body">
                    <a href="{{route('admin.post.create')}}" class="btn btn-success btn-circle waves-effect waves-circle waves-float m-t--10 pull-right">
                        <i class="material-icons m-t-5">add</i>
                    </a>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Изображение</th>
                                <th>Категория</th>
                                <th>Заголовок</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($posts as $post)
                                <tr>
                                    <td>{{$post->id}}</td>
                                    <td><img width="200" src="{{asset($post->images[0])}}" alt=""></td>
                                    <td>{{$post->name}}</td>
                                    <td>{{$post->title}}</td>
                                    <td>
{{--                                        <a href="" class=" waves-effect btn btn-primary"><i class="material-icons">visibility</i></a>--}}
                                        <a href="{{route('admin.post.edit',$post->id)}}" class="waves-effect btn btn-success"><i class="material-icons">mode_edit</i></a>
                                        <a href="{{route('admin.post.destroy',$post->id)}}" class="waves-effect btn btn-danger"><i class="material-icons">delete</i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{$posts->links()}}

            </div>
        </div>
    </div>

@endsection

