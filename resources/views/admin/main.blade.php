@extends('admin.layouts.app')

@section('content')
    <div class="row clearfix">
        <div class="row clearfix">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="">
                    <div class="info-box bg-pink hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">playlist_add_check</i>
                        </div>
                        <div class="content">
                            <div class="text">Курсы</div>
                            <div class="number count-to" data-from="0" data-to="{{$countCourses}}" data-speed="15" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="">
                    <div class="info-box bg-cyan hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">bookmark_order</i>
                        </div>
                        <div class="content">
                            <div class="text">Уроки</div>
                            <div class="number count-to" data-from="0" data-to="{{$countLessons}}" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="">
                    <div class="info-box bg-light-green hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">person</i>
                        </div>
                        <div class="content">
                            <div class="text">ПОЛЬЗОВАТЕЛИ</div>
                            <div class="number count-to" data-from="0" data-to="{{$countUsers}}" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a href="">
                    <div class="info-box bg-orange hover-expand-effect">
                        <div class="icon">
                            <i class="material-icons">person_add</i>
                        </div>
                        <div class="content">
                            <div class="text">Новости</div>
                            <div class="number count-to" data-from="0" data-to="{{$countPosts}}" data-speed="1000" data-fresh-interval="20"></div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(function () {
            //Widgets count
            $('.count-to').countTo();
        });

    </script>
@endpush

