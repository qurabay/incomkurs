@extends('admin.layouts.app')

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>Вопрос-Ответ</h2>
                </div>
                <div class="body">
                    <a href="{{route('admin.question-answer.create')}}" class="btn btn-success btn-circle waves-effect waves-circle waves-float m-t--10 pull-right">
                        <i class="material-icons m-t-5">add</i>
                    </a>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Вопрос</th>
                                <th>Ответ</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->question}}</td>
                                    <td>{{$item->answer}}</td>
                                    <td>
                                        <a href="{{route('admin.question-answer.edit',$item->id)}}" class="waves-effect btn btn-success"><i class="material-icons">mode_edit</i></a>
                                        <a href="{{route('admin.question-answer.destroy',$item->id)}}" class="waves-effect btn btn-danger"><i class="material-icons">delete</i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                {{$items->links()}}

            </div>
        </div>
    </div>

@endsection

