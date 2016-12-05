@extends('_layouts/avbody')

@section('content')
    <div class="row profile">
        <div class="col-md-3 hidden-xs">
            @include('av_users/sidebar')
        </div>
        <div class="col-md-9">
            <div class="collect-content">
                <div class="box box-warning">
                    <div class="box-header">
                        <h3 class="box-title" style="min-width: 100px"><i class="fa fa-heart"></i> 我的收藏    <span id="page_text" class="small  text-warning"></span></h3>
                        <div class="box-tools">

                        </div>
                    </div><!-- /.box-header -->
                    <div class="form-group hidden" >
                        {!!Form::text('page',(Input::old('page')) ? Input::old('page') : 1,array('class' => 'form-control','id'=>'page','placeholder'=>'請輸入page'))!!}
                        {!!Form::text('sort_name',(Input::get('sort_name')) ? Input::get('sort_name') : '',array('class' => 'form-control','id'=>'sort_name','placeholder'=>'請輸入sort_name'))!!}
                        {!!Form::text('sort_order',(Input::get('sort_order')) ? Input::get('sort_order') : '',array('class' => 'form-control','id'=>'sort_order','placeholder'=>'請輸入sort_order'))!!}
                    </div>
                    
                    <div class="box-body no-padding">

                        {!!Form::open(['method'=>'post','id' => 'av_user_collect_search','class'=>"form-inline  text-right form-group", 'onsubmit'=>"return false",'files' => true])!!}

                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-book"></i></span>
                            {!!Form::text('title',(Input::get('title')) ? Input::get('title') : $db_title,array('class' => 'form-control input-sm','id'=>'title','placeholder'=>'漫畫名稱'))!!}
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-check-circle"></i></span>
                            {!! Form::select('score',  array('all'=>'ALL','1'=>'0-1顆星', '2' => '1-2顆星', '3' => '2-3顆星', '4' => '3-4顆星','5'=>'4-5顆星'), (Input::get('score')) ? Input::get('score') : $db_score,array('class' => 'form-control input-sm','id'=>'score','placeholder'=>'Score')) !!}
                        </div>

                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa  fa-calendar-plus-o"></i></span>
                            {!!Form::input('date','created_at',(Input::get('created_at')) ? Input::get('created_at') : $db_created_at,array('class' => 'form-control input-sm input-100','min'=>'2016-05-25','max'=>date("Y-m-d") ,'id'=>'created_at','placeholder'=>'收藏時間'))!!}

                        </div>
                        {!! Form::close() !!}

                        <table class="table table-hover  collect" style="width:100%;" id="av_user_table_list">
                            <thead class="text-black " style="background-color: rgba(255, 236, 126, 0.29)">
                            <tr>
                                <th width="5%">ID</th>
                                <th>圖片</th>
                                <th width="30%">文章主題<i  id="t_title" class="fa fa-sort-amount-desc text-warning" title="desc" data-name="title"></i></th>
                                <th class="hidden-xs">Score<i  id="t_score" class="fa fa-sort-amount-desc text-warning" title="desc" data-name="score"></i></th>
                                <th class="hidden-xs">收藏時間<i  id="t_created_at" class="fa fa-sort-amount-desc text-warning" title="desc" data-name="created_at"></i></th>
                                <th width="15%">刪除</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div><!-- /.box-body -->

                </div>

                <div class="text-center">
                    <ul id="pagination" class="pagination-lg btn-lg "></ul>
                </div>
            </div>
        </div>
    </div>
    <!-- /#wrapper -->
@endsection
@push('scripts')
<script>
     var height=$(window).height()-86;
    $('.collect-content').css("min-height",height+"px");
</script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.3.1/jquery.twbsPagination.min.js"></script>

<script type="text/javascript">
             // give $().bootstrapDP the bootstrap-datepicker functionality
             $("#av_user_collect_search").validate();
    var url = "<?php echo route('av.user.collect_articles')?>";
    $("#av_user_table_list th i").click(function() {
        var tmp_id=this.id;
        var tmp_name = $(this).attr("data-name");
        if (this.title=="desc"){
            console.log('d');
            $("#"+tmp_id).attr({
                "title" : "asc",
                "class":"fa fa-sort-amount-asc  text-warning"
            });

        } else if (this.title=="asc"){
            $("#"+tmp_id).attr({
                "title" : "desc",
                "class":"fa fa-sort-amount-desc  text-warning"
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
    var current_page = 5;
    var total_page = 0;
    var is_ajax_fire = 0;
    $('#av_user_collect_search input,#av_user_collect_search select').on('change', function (e,s) {
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
                title: $( "#title" ).val(),
                score: $( "#score" ).val(),
                created_at: $( "#created_at" ).val(),
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
                    prev:"<",
                    next:">",
                    first:"<<",
                    end:">>",
                    last:">>",
                    loop:true,
                    //  currentPage:data.current_page,
                    onPageClick: function (event, pageL) {
                        //   pageL=page;

                        if(is_ajax_fire != 0){
                            console.log(pageL);
                            $( "#page" ).val(pageL);
                            getPageData();
                            $("#page_text").html("共 <span class='text-bold'>"+data.total+"</span> 篇漫畫, 目前在第"+pageL+"頁");

                        }
                    }
                });


                manageRow(data.data,data.current_page);
                $("#page_text").html("共 <span class='text-bold'>"+data.total+"</span> 篇漫畫, 目前在第"+data.current_page+"頁");
                is_ajax_fire = 1;
            } else {
                $("tbody").html("<tr><td colspan=\"12\"> 查無收藏相關資料 </td></tr>");
                $("#page_text").html("共 <span class='text-bold'>"+data.total+"</span> 篇漫畫, 目前在第"+data.current_page+"頁");

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
                title: $( "#title" ).val(),
                score: $( "#score" ).val(),
                created_at: $( "#created_at" ).val(),

                sort_name: $( "#sort_name" ).val(),
                sort_order: $( "#sort_order" ).val()

            }
        }).done(function(data){


            // console.log($( "#title" ).val());
            manageRow(data.data,data.current_page);
        });
    }

    /* Add new Item table row */
    function manageRow(data,page) {
        var	rows = '';
        var i=0;
      //  var page=data.current_page;
       // console.log("aaaaaa",data);
        //console.log(['bb',data]);
        $.each( data, function( key, value ) {
            i++;
            rows = rows + '<tr>';
            rows = rows + '<td>'+i+'</td>';
            rows = rows + '<td><a href="/'+value.unique_id+'"><img src="/focus_photos/'+value.photo+'" class="img-collect img-thumbnail img-small" alt="'+value.title+'" style="width:30px"></a> </td>';
            rows = rows + '<td><a href="/'+value.unique_id+'">'+value.title+'</a></td>';

            if (value.score==null) {
                rows = rows + '<td>'+''+'</td>';
            } else {
                rows = rows + '<td class="hidden-xs">'+(value.score/20).toFixed(1)+'</td>';
            }

            rows = rows + '<td  class="hidden-xs">'+value.collected_date+'</td>';

            rows = rows +'<td> <form method="POST" action="'+'{{route('av.user.article.delete')}}'+'" accept-charset="UTF-8" class="form-horizontal">'
                   + '<input name="_token" type="hidden" value="'+'{{csrf_token()}}'+'">'
               +' <input name="article_id" type="hidden" value="'+value.id+'">'
                    +'<input name="_method" type="hidden" value="DELETE">'
                      +'<input name="page" type="hidden" value="'+page+'">'
                           +' <button type="submit" class="btn btn-xs glyphicon glyphicon-trash text-muted"></button>'
           +'</form>';

            rows = rows + '</tr>';
        });

        $("tbody").html(rows);


    }






</script>

@endpush