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



                    {!!Form::open(['route'=>['admin.categories.update',$id],'method'=>'post','id' => 'category_edit','class'=>"form-horizontal",'files' => true])!!}
                    <input type="hidden" name="id" id="id" value="{{$id}}">
                    <input name="_method" type="hidden" value="PUT">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="name" class="col-sm-3 col-lg-2 control-label">類別名稱</label>

                            <div class="col-sm-9 col-lg-4 ">

                                {!!Form::text('name',(Input::old('name')) ? Input::old('name') : $name,array('class' => 'form-control','id'=>'name','placeholder'=>'請輸入您的類別','required'))!!}
                            </div>
                        </div>


                        <div class="form-group sandbox-container">
                            <label for="publish_date" class="col-sm-3 col-lg-2 control-label">說明</label>

                            <div class="col-sm-9 col-lg-10">
                                {!!Form::text('description',(Input::old('description')) ? Input::old('description') : $description,array('class' => 'form-control','id'=>'description','placeholder'=>'請簡述類別用途說明'))!!}
                            </div>
                        </div>


                        <div class="box-footer">
                            <button type="reset" class="btn btn-default">Cancel</button>
                            <button type="submit" class="btn btn-success pull-right">修改類別</button>
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
    $("#category_edit").validate({
        rules: {

            name: {
                remote: {
                    url: myURL['base'] + "/admin/categories/check_repeat",
                    type: "post",
                    data: {
                        type: 'update',
                        name: function () {
                            return $("#name").val();
                        },

                        id: function () {
                            return $("#id").val();
                        }
                    }
                }
            }

        },
        messages: {
            name: {
                remote: "類別值已存在"
            }
        },
        submitHandler: function (form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            form.submit();
        }
    });

</script>
@endpush