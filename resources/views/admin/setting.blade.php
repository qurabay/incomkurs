@extends('admin.layouts.app')

@section('content')
    <form action="{{route('admin.setting')}}" enctype="multipart/form-data" method="post">
        @csrf
        <div class="form-group">
            <label>Whatsapp номер</label>
            <input type="text"  class="form-control" name="whatsapp" value="{{$setting->whatsapp}}" required>
        </div>
        <div class="form-group">
            <label>Каспи перевод</label>
            <input type="text"  class="form-control" name="kaspi" value="{{$setting->kaspi}}" required>
        </div>
        <div class="form-group">
            <label>ИИН</label>
            <input type="text"  class="form-control" name="kaspi_description" value="{{$setting->kaspi_description}}" required>
        </div>
        <div class="form-group">
            <label>Qiwi кошелек</label>
            <input type="text"  class="form-control" name="qiwi" value="{{$setting->qiwi}}" required>
        </div>


        <div class="form-group">
            <label>Телефон номер Ватсап</label>
            <input type="text"  class="form-control" name="whatsapp_link" value="{{$setting->whatsapp_link}}" required>
        </div>
        <div class="form-group">
            <label>Телефон номер техподдержки</label>
            <input type="text"  class="form-control" name="phone" value="{{$setting->phone}}" required>
        </div>
        <div class="form-group">
            <label>Почта техподдержки</label>
            <input type="text"  class="form-control" name="email" value="{{$setting->email}}" required>
        </div>
        <div class="form-group">
            <label>Краткая информация</label>
            <input type="text"  class="form-control" name="info" value="{{$setting->info}}" required>
        </div>

{{--        <div class="form-group">--}}
{{--            <label>Договор публичной оферты</label>--}}
{{--            <textarea name="oferta" class="form-control">{{$setting->oferta}}</textarea>--}}
{{--        </div>--}}
        <div class="form-group">
            <button class="btn btn-primary" type="submit">Сохранить</button>
        </div>
    </form>
@endsection



@push('js')
    <script>
        $(function () {
            tinymce.init({
                selector: "textarea",
                theme: "modern",
                height: 300,
                plugins: [
                    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                    'searchreplace wordcount visualblocks visualchars code fullscreen',
                    'insertdatetime media nonbreaking save table contextmenu directionality',
                    'emoticons template paste textcolor colorpicker textpattern imagetools'
                ],
                toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                toolbar2: 'print preview media | forecolor backcolor emoticons',
                image_advtab: true
            });
            tinymce.suffix = ".min";
            tinyMCE.baseURL = '{{asset('admin-vendor/plugins/tinymce')}}';


            tinymce.init({
                selector: "textarea#editor2",
                theme: "modern",
                height: 300,
                plugins: [
                    'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                    'searchreplace wordcount visualblocks visualchars code fullscreen',
                    'insertdatetime media nonbreaking save table contextmenu directionality',
                    'emoticons template paste textcolor colorpicker textpattern imagetools'
                ],
                toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                toolbar2: 'print preview media | forecolor backcolor emoticons',
                image_advtab: true
            });
            tinymce.suffix = ".min";
            tinyMCE.baseURL = '{{asset('admin-vendor/plugins/tinymce')}}';
        })
    </script>
@endpush


