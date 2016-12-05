@extends('_layouts/admin')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{$page['sub_title']}}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    @if (count($errors) > 0)
                        <div class="row">
                            <div class="alert alert-danger  alert-dismissable col-lg-offset-1 col-lg-8">
                                <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    {!!Form::open(['route'=>['admin.parameters.store'],'method'=>'post','id' => 'parameter_form','class'=>"form-horizontal",'files' => true])!!}
                    <input type="hidden" name="domain" id="domain" value="{{$domain}}">


                    <div class="box-body">
                        <div class="form-group">
                            <label for="name" class="col-sm-3 col-lg-2 control-label">參數名稱</label>

                            <div class="col-sm-9 col-lg-4 ">

                                {!!Form::text('name',(Input::old('name')) ? Input::old('name') : $name,array('class' => 'form-control','id'=>'name','placeholder'=>'請輸入您的參數','required'))!!}
                            </div>
                        </div>


                        <div class="form-group sandbox-container">
                            <label for="publish_date" class="col-sm-3 col-lg-2 control-label">參數值</label>

                            <div class="col-sm-9 col-lg-10">
                                {!!Form::text('data',(Input::old('data')) ? Input::old('data') : $data,array('class' => 'form-control','id'=>'data','placeholder'=>'請數入參數值，若為多值，請用「,」隔開','required'))!!}
                            </div>
                        </div>

                        <div class="form-group sandbox-container">
                            <label for="publish_date" class="col-sm-3 col-lg-2 control-label">說明</label>

                            <div class="col-sm-9 col-lg-10">
                                {!!Form::text('description',(Input::old('description')) ? Input::old('description') : $description,array('class' => 'form-control','id'=>'description','placeholder'=>'請簡述參數用途說明'))!!}
                            </div>
                        </div>


                        <div class="box-footer">
                            <button type="reset" class="btn btn-default">Cancel</button>
                            <button type="submit" class="btn btn-success pull-right">新增參數</button>
                        </div>
                        <!-- /.box-footer -->
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
    </section>

@endsection

@push('scripts')
<script>


    $("#parameter_form").validate({
        rules: {

            name: {
                parameter: true,
                remote: {
                    url: myURL['base'] + "/admin/parameters/check_repeat",
                    type: "post",
                    data: {
                        type: 'create',
                        name: function () {
                            return $("#name").val();
                        },
                        domain: function () {
                            return $("#domain").val();

                        }
                    }
                }
            }
        },
        messages: {
            name: {
                remote: "參數值已存在"
            }
        },
        submitHandler: function (form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            form.submit();
        }
    });

</script>
@endpush