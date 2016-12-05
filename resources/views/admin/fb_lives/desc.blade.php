@extends('_layouts.admin')

@section('content')
    <section class="content">
        <div class="row">
            <a href="http://tesa.today/article/1422" target="_blank" class="btn-xs">網路上的直播教學</a></td>
            <a href="https://obsproject.com/" target="_blank" class="btn-xs">下載OBS軟體</a></td>

        </div>
        <div class="row">
            <div class="col-xs-12">


                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{$page['sub_title']}}</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div>

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active"><a href="#one" aria-controls="home" role="tab" data-toggle="tab">1</a></li>
                                <li role="presentation"><a href="#two" aria-controls="profile" role="tab" data-toggle="tab">2</a></li>
                                <li role="presentation"><a href="#three" aria-controls="messages" role="tab" data-toggle="tab">3</a></li>
                                <li role="presentation"><a href="#four" aria-controls="settings" role="tab" data-toggle="tab">4</a></li>
                                <li role="presentation"><a href="#five" aria-controls="settings" role="tab" data-toggle="tab">5</a></li>
                                <li role="presentation"><a href="#six" aria-controls="settings" role="tab" data-toggle="tab">6</a></li>
                                <li role="presentation"><a href="#seven" aria-controls="settings" role="tab" data-toggle="tab">7</a></li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="one"><p><img src="/images/f1.png" class="img img-thumbnail"></div>
                                <div role="tabpanel" class="tab-pane" id="two"><p><img src="/images/f2.png"  class="img img-thumbnail"></div>
                                <div role="tabpanel" class="tab-pane" id="three"><p><img src="/images/f3.png" class="img img-thumbnail"></div>
                                <div role="tabpanel" class="tab-pane" id="four"><p><img src="/images/f4.png" class="img img-thumbnail"></div>
                                <div role="tabpanel" class="tab-pane" id="five"><p><img src="/images/f5.png" class="img img-thumbnail"></div>
                                <div role="tabpanel" class="tab-pane" id="six"><p><img src="/images/f6.png" class="img img-thumbnail"></div>
                                <div role="tabpanel" class="tab-pane" id="seven"><p><img src="/images/f7.png" class="img img-thumbnail"></div>
                            </div>

                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section><!-- /.content -->

@endsection


@push('srcipts')

@endpush