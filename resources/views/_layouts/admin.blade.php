<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{{strtoupper(str_replace("http://","",explode(".",URL::to('/'))[0]))}} - 小編創作平台 </title>
<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
{{--樣式--}}
<!-- Bootstrap 3.3.5 -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="{{('/css/AdminLTE.min.css')}}">
<!-- AdminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="{{('/css/skins/_all-skins.min.css')}}">
<!--link rel="stylesheet" href="{{('/bower_components/summernote/dist/summernote.css')}}"-->

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/css/selectize.bootstrap3.min.css">

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.10/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="{{('/plugins/datatables/dataTables.bootstrap.css')}}">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/smalot-bootstrap-datetimepicker/2.3.4/css/bootstrap-datetimepicker.min.css">
<!--<link rel="stylesheet"
      href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.min.css">-->
<link rel="stylesheet" href="{{('/css/admin.css')}}">


<link rel="shortcut icon" href="{{('/favicon.ico')}}" type="image/vnd.microsoft.icon">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

<![endif]-->
<style>
.popover-title{
    overflow: hidden;
}
.popover-content{
    /*overflow: scroll;*/
    /*width:500px;*/
}
#editorstatus{
    height: 2px;
    background-color: green;
    position:absolute;
    right:0;
    left:0;
    top:0;
}

</style> 
{{---DIY---}}
<script >
    var myURL = {
        'base' : '{{ URL::to('/') }}',
        'current' : '{{ URL::current() }}',
        'full' : '{{ URL::full() }}'
    };

    </script>
<meta name="csrf-token" content="{{ csrf_token() }}" />

</head>
<body class=" skin-purple" id="body">
<!-- Site wrapper -->
<div class="wrapper">
@include('_layouts/admin_header')

    <!-- =============================================== -->
    {{--麵包屑--}}
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                {{----$title-----}}{!!$page['title']!!}
                <small></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="{{$page['url']}}">{{$page['title']}}</a></li>
                <li class="active">{{$page['sub_title']}}</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            @yield('content')


        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@include('_layouts/admin_footer')



</div><!-- ./wrapper -->


<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
{{---JQuery validate ---}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.13.1/additional-methods.min.js"></script>
<script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.13.1/localization/messages_zh_TW.js"></script>
{{-----}}

{{--列表---}}
<script src="//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.10/js/jquery.dataTables.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/datatables/1.10.10/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="{{('/plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->
<script src="{{('/plugins/fastclick/fastclick.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{('/js/app.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{('/js/demo.js')}}"></script>
{{--日期---}}

<script src="//cdnjs.cloudflare.com/ajax/libs/smalot-bootstrap-datetimepicker/2.3.4/js/bootstrap-datetimepicker.min.js"></script>
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>-->
{{--tags---}}
<script src="//cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.1/js/standalone/selectize.js"></script>
<!--script src="{{('/bower_components/summernote/dist/summernote.js')}}"></script-->
{{---laravel js curd ---}}
<script src="{{('/js/laravel.js')}}"></script>
{{--bootbox---}}
<script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
{{---DIY---}}
<script src="{{('/js/admin_main.js')}}?v=2"></script>
<script src="{{('/js/function.js')}}"></script>
<script src="{{('/js/EZ.2.js')}}"></script>
<script src="//cdn.jsdelivr.net/clipboard.js/1.5.5/clipboard.min.js"></script>
<script src="{{('/bower_components/tinymce_4.3.3/tinymce/js/tinymce/tinymce.min.js')}}"></script>
<!--script src='//cdn.tinymce.com/4/tinymce.min.js'></script-->
<script src="{{('/js/editor.js?tt=1')}}"></script>


<script>
    
</script>


@stack('scripts')
@include('_layouts.admin_js')
</body>
</html>
