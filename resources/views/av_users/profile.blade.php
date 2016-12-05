@extends('_layouts/avbody')

@section('content')
    <div class="row profile">
        <div class="col-lg-3 hidden-xs">
            @include('av_users/sidebar')
        </div>
        <div class="col-lg-9 col-md-12">
            <div class="profile-content">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="min-width: 100px"><i class="fa fa-user"></i> 帳號設定</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    @if (count($errors) > 0)
                        <div class="row">
                            <div class="alert alert-danger  alert-dismissable col-lg-offset-2 col-lg-8">
                                <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    {!!Form::open(['route'=>['av.user.update'],'method'=>'post','id' => 'profile_form','class'=>"form-horizontal",'files' => true])!!}

                    <input name="_method" type="hidden" value="PUT">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="nick_name" class="col-sm-2 control-label">* 暱稱（公開）</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="nick_name" name="nick_name"  value="{{Auth::av_user()->get()->nick_name}}" required placeholder="暱稱（公開）">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">大頭貼</label>
                                <div class="col-sm-5 uploadWrapper">


                                    <input type="file" name="avatar" class="form-control" id="avatar" placeholder="大頭貼"   >
                                    <img id="target" src=@if(strpos(Auth::av_user()->get()->avatar,"facebook"))"{{Auth::av_user()->get()->avatar."&".rand()}}" @else "{{Auth::av_user()->get()->avatar}}" @endif alt="your image" style="width:120px;height:120px" class="img img-rounded img-thumnail"/>
                                    <h6 class="text-muted text-center mar-t-2">上傳大頭貼</h6>
                                </div>
                                <div class="col-sm-5 text-muted ">
                                  <span class="avatar-right"></span>
                                    大頭貼最多可上傳1MB的檔案。<br>
                                    僅支援JPG、JPEG及PNG格式的圖檔。<br>
                                    上傳圖片時請務必謹慎選擇，避免侵犯著作權或肖像權。<br>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email" class="col-sm-2 control-label">E-mail</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control"  name="email" value="{{Auth::av_user()->get()->email}}" id="email" placeholder="電子郵件" >
                                </div>
                            </div>

                        </div><!-- /.box-body -->
                        <div class="box-footer ">

                            <button type="submit" class="btn btn-warning col-lg-4 col-lg-offset-4 col-xs-8 col-xs-offset-2">設定</button>


                        </div><!-- /.box-footer -->


                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /#wrapper -->
@endsection
@push('scripts')

<script>


    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    var fileTypes = ['jpg', 'jpeg', 'png', 'gif'];  //acceptable file types

    function readURL(input) {
        if (input.files && input.files[0]) {
            var extension = input.files[0].name.split('.').pop().toLowerCase(),  //file extension from input file
                    isSuccess = fileTypes.indexOf(extension) > -1;  //is extension in acceptable types

            if (isSuccess) { //yes
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#target').attr('src', e.target.result);

                }

                reader.readAsDataURL(input.files[0]);
            }
            else { //no
                //warning
                $('#target').attr('src', 'data:image/gif;base64,' +
'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'
                );
            }
        }
    }

    $("#avatar").change(function () {
        readURL(this);
    });
    $(document).ready(function () {



    $.validator.addMethod('filesize', function(value, element, param) {
        //console.log('filesize');
        // param = size (in bytes)
        // element = element to validate (<input>)
        // value = value of the element (file name)
        return this.optional(element) || (element.files[0].size <= param)
    });
    $('#profile_form').validate({
        rules: {
            avatar: {
                filesize: 1048576,
                accept: "jpg|jpeg|png|gif"

            },
            email: {
                remote: {
                    url: "{{route('av.user.check_repeat')}}",
                    type: "post",
                    data: {
                        type: 'update_email',
                        _token:"{{csrf_token()}}",
                        email: function () {
                            return $("#email").val();
                        }
                    }

                }
            }
        },
        messages: {
            avatar: {
                accept:"僅支援JPG、JPEG及PNG格式的圖檔",
                filesize:"檔案必需小於 1MB"
            },
            email:{
                remote:"E-mail已存在"
            }
        },

        submitHandler: function (form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            form.submit();
        }
    });
    });
</script>
@endpush