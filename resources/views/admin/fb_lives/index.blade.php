@extends('_layouts/admin')
@section('content')
    <section class="content">

        <div class="row">
              <a href="http://tesa.today/article/1422" target="_blank" class="btn-xs">網路上的直播教學</a></td>
              <a href="https://obsproject.com/" target="_blank" class="btn-xs">下載OBS軟體</a></td>

        </div>
        <div class="row ">
            @if(Session::has('alert_message'))
                <div class="alert alert-success  alert-dismissable col-lg-offset-2 col-lg-4" id="success-alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4>	<i class="icon fa fa-check"></i> Alert!</h4>
                    {{ Session::get('alert_message') }}

                </div>
            @endif
            <div class=" col-xs-12 col-sm-8">
                <div class="form-group hidden" >
                    {!!Form::text('page',(Input::old('page')) ? Input::old('page') : 1,array('class' => 'form-control','id'=>'page','placeholder'=>'請輸入page'))!!}
                    {!!Form::text('sort_name',(Input::get('sort_name')) ? Input::get('sort_name') : '',array('class' => 'form-control','id'=>'sort_name','placeholder'=>'請輸入sort_name'))!!}
                    {!!Form::text('sort_order',(Input::get('sort_order')) ? Input::get('sort_order') : '',array('class' => 'form-control','id'=>'sort_order','placeholder'=>'請輸入sort_order'))!!}
                </div>

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{$page['sub_title']}}</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">

                        {!!Form::open(['method'=>'post','id' => 'av_user_list','class'=>"form-inline  form-group",'files' => true])!!}
                        <div class="form-group input-group">
                            <span class="input-group-addon">#</span>
                            {!!Form::text('fb_live_id',(Input::get('fb_live_id')) ? Input::get('fb_live_id') : '',array('class' => 'form-control input-sm input-70','id'=>'fb_live_id','placeholder'=>'編號'))!!}
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-facebook"></i>#</span>
                            {!!Form::text('fb_video_d',(Input::get('fb_video_id')) ? Input::get('fb_video_id') : '',array('class' => 'form-control input-sm input-100' ,'id'=>'fb_video_id','placeholder'=>'FB Video ID'))!!}
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-question"></i></span>
                            {!!Form::text('title',(Input::get('title')) ? Input::get('title') : '',array('class' => 'form-control input-sm','id'=>'title','placeholder'=>'問項'))!!}
                        </div>


                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            {!!Form::select('author',$users,Input::get('author',''),['id' => 'author','class' => 'form-control input-sm input-100'])!!}
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa  fa-calendar-plus-o"></i></span>
                            {!!Form::text('created_at',(Input::get('created_at')) ? Input::get('created_at') : '',array('class' => 'form-control input-sm input-100' ,'id'=>'created_at','placeholder'=>'新增日期'))!!}
                            <span class="input-group-addon"><i class="fa  fa-calendar-check-o"></i></span>
                            {!!Form::text('updated_at',(Input::get('updated_at')) ? Input::get('updated_at') : '',array('class' => 'form-control input-sm input-100' ,'id'=>'updated_at','placeholder'=>'修改日期'))!!}
                        </div>
                        {!! Form::close() !!}

                        <table id="av_user_table_list" class="table table-bordered table-striped" data-page-length="10" data-order="[[ 0, &quot;asc&quot; ]]">
                            <thead>
                            <tr>
                                <th style="width:10px !important">#<i  id="t_id" class="t_id fa fa-sort-amount-desc text-gray" title="desc" data-name="id"></i></th>
                                <th width="10%" ><i class="fa fa-facebook"></i> Video ID<i  id="t_fb_video_id" class="t_fb_video_id fa fa-sort-amount-desc text-gray" title="desc" data-name="video_id"></i></th>
                                <th>問項<i  id="t_title" class="fa  t_title fa-sort-amount-desc   text-gray" title="desc" data-name="title"></i></th>
                                <th>答項<i  id="t_answer" class="fa  t_answer   text-gray" title="desc" data-name="answer"></i></th>
                                <th>作者<i  id="t_author" class="fa t_author  text-gray" title="desc" data-name="name"></i></th>
                                <th>新增日期<i  id="t_created_at" class="fa t_created_at fa-sort-amount-desc text-gray" title="desc" data-name="created_at"></i></th>
                                <th>更新日期<i  id="t_updated_at" class="fa t_updated_at fa-sort-amount-desc text-gray" title="desc" data-name="updated_at"></i></th>
                                <th>編輯</th>
                            </tr>
                            </thead>
                            <tbody>


                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="width:10px !important">#<i   class=" t_id fa fa-sort-amount-desc text-gray" title="desc" data-name="id"></i></th>
                                <th width="10%" ><i class="fa fa-facebook"></i> Video ID<i  class="fa t_fb_video_id fa-sort-amount-desc text-gray" title="desc" data-name="video_id"></i></th>
                                <th>問項<i   class="fa t_title fa-sort-amount-desc   text-gray" title="desc" data-name="title"></i></th>
                                <th>答項<i  class="fa  t_answer   text-gray" title="desc" data-name="answer"></i></th>
                                <th>作者<i  class="t_author  text-gray" title="desc" data-name="name"></i></th>
                                <th>新增日期<i   class="t_created_at fa fa-sort-amount-desc text-gray" title="desc" data-name="created_at"></i></th>
                                <th>更新日期<i   class="t_updated_at fa fa-sort-amount-desc text-gray" title="desc" data-name="updated_at"></i></th>
                                <th>編輯</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->

        </div><!-- /.row -->
        <div class="row">
            <div class="col-lg-2 " id="page_text" style="margin-top: 26px" ></div>
            <div class="col-lg-5">
                <ul id="pagination" class="pagination-sm"></ul>
            </div>
        </div>
    </section><!-- /.content -->



@endsection
@push('scripts')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.3.1/jquery.twbsPagination.min.js"></script>

<script type="text/javascript">
    var url = "<?php echo route('admin.fb_lives.index')?>";
</script>
<script type="text/javascript">
    function sort(){

    }
    $("#av_user_table_list th i").click(function() {
        var tmp_id=this.id;
        var tmp_name = $(this).attr("data-name");
        if (this.title=="desc"){
            console.log('d');
            $("."+tmp_id).attr({
                "title" : "asc",
                "class":"fa fa-sort-amount-asc  text-gray"
            });

        } else if (this.title=="asc"){
            $("."+tmp_id).attr({
                "title" : "desc",
                "class":"fa fa-sort-amount-desc  text-gray"
            });
        }
        $("#sort_name").val(tmp_name);
        $("#sort_order").val(this.title);

        manageData();

        //console.log($("#"+tmp_id).title);
        //$('.thePrices').find('.addcartforlower').length;
        //this.removeClass( "fa-sort-alpha-desc").addClass("fa-sort-alpha-asc");
    });
    var mypage=null;
    var page = 1;
    var current_page = 10;
    var total_page = 0;
    var is_ajax_fire = 0;
    $('#av_user_list input,#av_user_list select').on('change', function (e,s) {
        //if (e.which == 13) {
        //saveToDB(this);
        page=s;
        // is_ajax_fire=0;
        $( "#page" ).val(1);
        // $('#pagination').twbsPagination('destroy');
        manageData();

        //}

    });


    manageData();

    /* manage data list */
    function manageData() {
       // alert($( "#id" ).val());
        $.ajax({
            dataType: 'json',
            url: url,
            data: {page:page,
                fb_live_id: $( "#fb_live_id" ).val(),
                title: $( "#title" ).val(),

                fb_video_id: $( "#fb_video_id" ).val(),
                created_at: $( "#created_at" ).val(),
                updated_at: $( "#updated_at" ).val(),
                name: $( "#author" ).val(),
                sort_name: $( "#sort_name" ).val(),
                sort_order: $( "#sort_order" ).val()

            }
        }).done(function(data){

            total_page = data.last_page;
            newTotalPages=data.last_page;
            current_page = 10;//data.current_page;
            console.log(data);
            if (total_page>0){
                if (mypage!=null)$('#pagination').twbsPagination('destroy');
                // console.log(mypage);
                mypage= $('#pagination').twbsPagination({
                    totalPages: total_page,
                    visiblePages: current_page,
                    startPage: 1,

                    //  currentPage:data.current_page,
                    onPageClick: function (event, pageL) {
                        //   pageL=page;

                        if(is_ajax_fire != 0){
                            console.log(pageL);
                            $( "#page" ).val(pageL);
                            getPageData();
                            $("#page_text").html("共 <span class='text-bold'>"+data.total+"</span> 篇直播, 目前在第"+pageL+"頁");

                        }
                    }
                });


                manageRow(data.data);
                $("#page_text").html("共 <span class='text-bold'>"+data.total+"</span> 個會員, 目前在第"+data.current_page+"頁");
                is_ajax_fire = 1;
            } else {
                $("tbody").html("<tr><td colspan=\"12\"> 查無資料 </td></tr>");
                $("#page_text").html("共 <span class='text-bold'>"+data.total+"</span> 個會員, 目前在第"+data.current_page+"頁");

            }
        });
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /* Get Page Data*/
    function getPageData() {
        // console.log("getr"+page);
        //alert($( "#id" ).val());
        $.ajax({
            dataType: 'json',
            url: url,
            data: {
                page:$( "#page" ).val(),
                fb_live_id: $( "#fb_live_id" ).val(),
                title: $( "#title" ).val(),

                fb_video_id: $( "#fb_video_id" ).val(),
                name: $( "#author" ).val(),
                created_at: $( "#created_at" ).val(),
                updated_at: $( "#updated_at" ).val(),
                sort_name: $( "#sort_name" ).val(),
                sort_order: $( "#sort_order" ).val()

            }
        }).done(function(data){


            // console.log($( "#title" ).val());
            manageRow(data.data);
        });
    }

    /* Add new Item table row */
    function manageRow(data) {
        var	rows = '';
        console.log(['bb',data]);
        $.each( data, function( key, value ) {
            rows = rows + '<tr>';
            rows = rows + '<td><a href="/admin/fb_lives/'+value.id+'/edit">'+value.id+'</a></td>';
            rows = rows + '<td>'+value.fb_video_id+'</td>';
            rows = rows + '<td>'+value.title+'</td>';
            rows = rows + '<td>';
            var txt='';
            var answers = value.answers;

            if (answers){
                var emoji = answers.split(",");
                var arrayLength = emoji.length;
                for (var i = 0; i < arrayLength; i++) {
                    rows = rows + '<img src="/images/fbemojis/'+emoji[i]+'.gif"' +' class="img-circle" style="width:30px"> ';
                    //Do something
                }
            }


            rows = rows + '</td>';


           rows = rows + '<td>'+value.author.name+'</td>';

         //   rows = rows + '<td>'+value.udpateder.name+'</td>';
          // rows = rows + '<td>'+value.updated_user+'</td>';
            rows = rows + '<td>'+value.created_at+'</td>';
            rows = rows + '<td>'+value.updated_at+'</td>';
            rows = rows + '<td><a href="/fb_lives/'+value.id+'"  target="_blank" class="btn btn-default btn-xs small-btn" rel="nofollow" data-toggle="tooltip" data-placement="bottom" data-original-title="查看">'+'<i class="fa fa-search"></i>'+'</a> &nbsp;&nbsp;';

            rows = rows + '<a href="/admin/fb_lives/'+value.id+'/edit"  class="btn btn-default btn-xs small-btn" rel="nofollow" data-toggle="tooltip" data-placement="bottom" data-original-title="修改">'+'<i class="fa fa-edit"></i>'+'</a> &nbsp;&nbsp;';
            rows = rows + '<form  method="POST"  action="/admin/fb_lives/'+value.id+'" accept-charset="UTF-8" style="display:inline-block"><input name="_method" type="hidden" value="DELETE">'
                    +'<input name="_token" type="hidden" value="'+'{{csrf_token()}}'+'">'
                         +'<button class="btn btn-default btn-xs small-btn" type="submit" rel="nofollow" data-toggle="tooltip" data-placement="bottom" data-original-title="刪除"><i class="fa fa-trash-o"></i></button>'+'</form></td>';
            rows = rows + '</tr>';
        });


        $("tbody").html(rows);


    }


    @if(Session::has('alert_message'))

        $("#success-alert").alert();
        $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
            $("#success-alert").slideUp(500);
        });
    @endif


</script>

@endpush