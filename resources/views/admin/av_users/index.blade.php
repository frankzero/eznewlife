@extends('_layouts/admin')
@section('content')
    <section class="content">
        <div class="row">
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
                            {!!Form::text('id',(Input::get('id')) ? Input::get('id') : $db_id,array('class' => 'form-control input-sm input-70','id'=>'id','placeholder'=>'編號'))!!}
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            {!!Form::text('nick_name',(Input::get('nick_name')) ? Input::get('nick_name') : $db_nick_name,array('class' => 'form-control input-sm','id'=>'nick_name','placeholder'=>'會員姓名'))!!}
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                            {!!Form::text('email',(Input::get('email')) ? Input::get('email') : $db_email,array('class' => 'form-control input-sm','id'=>'email','placeholder'=>'E-mail'))!!}
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-paw"></i></span>
                            {!!Form::text('login_counts',(Input::get('login_counts')) ? Input::get('login_counts') : $db_login_counts,array('class' => 'form-control input-sm input-70' ,'id'=>'login_counts','placeholder'=>'登入次數'))!!}
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-gift"></i></span>
                            {!!Form::text('collect_counts',(Input::get('collect_counts')) ? Input::get('collect_counts') : $db_collect_counts,array('class' => 'form-control input-sm input-70' ,'id'=>'collect_counts','placeholder'=>'收藏數'))!!}
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa  fa-calendar-plus-o"></i></span>
                            {!!Form::text('created_at',(Input::get('created_at')) ? Input::get('created_at') : $db_created_at,array('class' => 'form-control input-sm input-100' ,'id'=>'created_at','placeholder'=>'註冊時間'))!!}
                            <span class="input-group-addon"><i class="fa fa-calendar-minus-o"></i></span>
                            {!!Form::text('updated_at',(Input::get('updated_at')) ? Input::get('updated_at') : $db_updated_at,array('class' => 'form-control input-sm input-100' ,'id'=>'updated_at','placeholder'=>'最後登入時間'))!!}
                        </div>
                        {!! Form::close() !!}

                        <table id="av_user_table_list" class="table table-bordered table-striped" data-page-length="10" data-order="[[ 0, &quot;asc&quot; ]]">
                            <thead>
                            <tr>
                                <th style="width:10px !important">#<i  id="t_id" class="fa fa-sort-amount-desc text-gray" title="desc" data-name="id"></i></th>
                                <th width="10%" >姓名<i  id="t_name" class="fa fa-sort-amount-desc text-gray" title="desc" data-name="nick_name"></i></th>
                                <th>大頭貼<i  id="t_avatar" class="fa fa-sort-amount-desc text-gray" title="desc" data-name="avatar"></i></th>
                                <th width="15%">E-mail<i  id="t_email" class="fa fa-sort-amount-desc text-gray" title="desc" data-name="email"></i></th>
                                <th>登入次數<i  id="t_login_counts" class="fa fa-sort-amount-desc text-gray" title="desc" data-name="login_counts"></i></th>
                                <th>收藏數<i  id="t_collect_counts" class="fa fa-sort-amount-desc text-gray" title="desc" data-name="collect_counts"></i></th>
                                <th>新增日期<i  id="t_created_at" class="fa fa-sort-amount-desc text-gray" title="desc" data-name="created_at"></i></th>
                                <th>更新日期<i  id="t_updated_at" class="fa fa-sort-amount-desc text-gray" title="desc" data-name="updated_at"></i></th>
                            </tr>
                            </thead>
                            <tbody>


                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="width:10px !important">#</th>
                                <th width="10%">姓名</th>
                                <th>大頭貼</th>
                                <th width="15%">E-mail</th>
                                <th>登入次數</th>
                                <th>收藏數<i  id="t_collect_counts" class="fa fa-sort-amount-desc text-gray" title="desc" data-name="collect_counts"></i></th>

                                <th>新增日期</th>
                                <th>更新日期</th>
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
    var url = "<?php echo route('admin.av_users.index')?>";
</script>
<script type="text/javascript">
    function sort(){

    }
    $("#av_user_table_list th i").click(function() {
        var tmp_id=this.id;
        var tmp_name = $(this).attr("data-name");
        if (this.title=="desc"){
            console.log('d');
            $("#"+tmp_id).attr({
                "title" : "asc",
                "class":"fa fa-sort-amount-asc  text-gray"
            });

        } else if (this.title=="asc"){
            $("#"+tmp_id).attr({
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
    $('#av_user_list input').on('change', function (e,s) {
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
        $.ajax({
            dataType: 'json',
            url: url,
            data: {page:page,
                nick_name: $( "#nick_name" ).val(),
                email: $( "#email" ).val(),
                login_counts: $( "#login_counts" ).val(),
                created_at: $( "#created_at" ).val(),
                updated_at: $( "#updated_at" ).val(),
                collect_counts: $( "#collect_counts" ).val(),

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
                            $("#page_text").html("共 <span class='text-bold'>"+data.total+"</span> 個會員, 目前在第"+pageL+"頁");

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
        $.ajax({
            dataType: 'json',
            url: url,
            data: {
                page:$( "#page" ).val(),
                nick_name: $( "#nick_name" ).val(),
                email: $( "#email" ).val(),
                login_counts: $( "#login_counts" ).val(),
                collect_counts: $( "#collect_counts" ).val(),

                created_at: $( "#created_at" ).val(),
                updated_at: $( "#updated_at" ).val(),
                sort_name: $( "#sort_name" ).val(),
                sort_order: $( "#sort_order" ).val()

            }
        }).done(function(data){


           // console.log($( "#nick_name" ).val());
            manageRow(data.data);
        });
    }

    /* Add new Item table row */
    function manageRow(data) {
        var	rows = '';
        console.log(['bb',data]);
        $.each( data, function( key, value ) {
            rows = rows + '<tr>';
            rows = rows + '<td>'+value.id+'</td>';
            rows = rows + '<td>'+value.nick_name+'</td>';
            rows = rows + '<td><img src="'+value.avatar+'" class="img-circle" style="width:30px"> </td>';
            if (value.email==null) {
                rows = rows + '<td>'+''+'</td>';
            } else {
                rows = rows + '<td><a href="mailto:'+value.email+'">'+value.email+'</a></td>';
            }

            rows = rows + '<td>'+value.login_counts+'</td>';
            rows = rows + '<td>'+value.collect_counts+'</td>';

            rows = rows + '<td>'+value.created_at+'</td>';
            rows = rows + '<td>'+value.updated_at+'</td>';

            rows = rows + '</tr>';
        });

        $("tbody").html(rows);


    }






</script>

@endpush