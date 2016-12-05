@extends('_layouts.admin')

@section('content')

 <style>

        ::-webkit-input-placeholder
        {
            color: rgba(139, 92, 168, 0.52);
            font-weight: normal;
        }
    </style>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">


                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">小編文章列表區</h3>
                    </div>


                    <div class="row">

                        <form id="filter_form" >
                           <div style="padding-left:30px;padding-right:15px;float:left;">
                               <div class="dataTables_length __web-inspector-hide-shortcut__" id="articles-table_length" style="padding: 0;margin: 0;">
                                     <label>Show: 
                                        <select name="length" class="form-control input-sm">
                                           <option value="10">10</option>
                                           <option value="25">25</option>
                                           <option value="50">50</option>
                                           <option value="100">100</option>
                                        </select>
                                     </label>
                               </div>
                           </div>
                            

                            <div style="padding-left:15px;padding-right:15px;float:left;">
                               <div class="dataTables_length" style="padding: 0;margin: 0;">
                                     <label>分類: 
                                        <select name="category_id" class="form-control input-sm" style="width:100px;">
                                            <option value="">所有類別</option>
                                            <option value="1">達人殿堂</option>
                                            <option value="2">酷索一下</option>
                                            <option value="3">新奇古怪</option>
                                            <option value="4">兩性與生活</option>
                                            <option value="5">APP市集</option>
                                            <option value="6">影劇新聞</option>
                                            <option value="7">正妹特搜</option>
                                            <option value="8">暗黑漫畫</option>
                                            <option value="9">H-Comic</option>
                                            <option value="10">神回覆</option>
                                        </select>
                                     </label>
                               </div>
                           </div>


                            <div style="padding-left:15px;padding-right:15px;float:left;">
                               <div class="dataTables_length" style="padding: 0;margin: 0;">
                                     <label>作者: 
                                        <select name="created_user" class="form-control input-sm" style="width:100px;">
                                           <option value="">ALL</option>
                                           <option value="1">nom</option>
                                           <option value="2">miu</option>
                                           <option value="3">pudding</option>
                                           <option value="4">celine</option>
                                           <option value="5">circle</option>
                                           <option value="6">cutey537</option>
                                           <option value="7">evelyn</option>
                                           <option value="8">jessy</option>
                                           <option value="9">candy</option>
                                           <option value="10">polly</option>
                                           <option value="11">irene</option>
                                            <option value="12"> Lily</option>
                                        </select>
                                     </label>
                               </div>
                           </div>


                             <div style="padding-left:15px;padding-right:15px;float:left;">
                               <div class="dataTables_length" style="padding: 0;margin: 0;">
                                     <label>狀態: 
                                        <select name="status" class="form-control input-sm">
                                           <option value="">ALL</option>
                                           <option value="0">草稿</option>
                                           <option value="1">上架中</option>
                                           <option value="2">未發佈</option>
                                        </select>
                                     </label>

                               </div>
                           </div>

                            
                            <div style="padding-left:15px;padding-right:15px;float:left;">
                               <div class="dataTables_length" style="padding: 0;margin: 0;">
                                     <label>刪除: 
                                        <select name="is_deleted" class="form-control input-sm">
                                           <option value="">ALL</option>
                                           <option value="0" selected>未刪除</option>
                                           <option value="1">已刪除</option>
                                        </select>
                                     </label>
                               </div>
                           </div>


                            


                           <div style="padding-left:15px;padding-right:15px;float:left;">
                               <div class="dataTables_length" style="padding: 0;margin: 0;">
                                     <label>情色: 
                                        <select name="flag" class="form-control input-sm">
                                           <option value="">ALL</option>
                                           <option value="G">一般</option>
                                           <option value="P">情色</option>
                                        </select>
                                     </label>
                               </div>
                           </div>
                            

                            <div style="padding-left:15px;padding-right:15px;float:left;">
                               <div class="dataTables_length" style="padding: 0;margin: 0;">
                                     <label>即時: 
                                        <select name="instant" class="form-control input-sm">
                                           <option value="">ALL</option>
                                           <option value="0">非即時</option>
                                           <option value="1">即時</option>
                                        </select>
                                     </label>
                               </div>
                           </div>


                           <div style="padding-left:15px;padding-right:15px;float:left;">
                              <div class="dataTables_filter">
                                  <label>Search:
                                    <input type="search" name="search" class="form-control input-sm" placeholder="" aria-controls="articles-table">
                                    
                                  </label>

                                  <label for="search_title">
                                      <input id="search_title" name="search_title" type="checkbox" checked>標題
                                  </label>

                                  <label for="search_content">
                                      <input id="search_content" name="search_content" type="checkbox" checked>內文
                                  </label>

                                  <label for="search_tag">
                                      <input id="search_tag" name="search_tag" type="checkbox" checked>tag
                                  </label>

                                  <label for="search_id">
                                      <input id="search_id" name="search_id" type="checkbox" checked>id
                                  </label>
                              </div>
                           </div>


                           <input type="hidden" name="page" value="1">
                           <input type="hidden" name="dir" value="DESC">

                        </form>

                    </div>


                    <!-- /.box-header -->
                    <div class="box-body">
<table id="articles-table" class="table table-condensed  table-hovered table-responsive mailbox-messages dataTable">
    <thead>
    <tr>
        <th data-role="sorting" data-dir="ASC" class="sorting" tabindex="0" aria-controls="articles-table" rowspan="1" colspan="1" style="width: 33px;" aria-sort="descending" aria-label="#: activate to sort column ascending">#</th>
        <th>標題</th>
        <th>類別</th>
        <th>狀態</th>
        <th>情色</th>
        <th>即時</th>
        <th>作者</th>

        <th>修改者</th>
        <th>發佈日期</th>
        <th>更新日期</th>
        <th>tags</th>
        <th >編輯</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th data-role="sorting" data-dir="DESC" class="sorting" tabindex="0" aria-controls="articles-table" rowspan="1" colspan="1" style="width: 33px;" aria-sort="descending" aria-label="#: activate to sort column ascending">#</th>
        <th width="30%">標題</th>
        <th>類別</th>
        <th>狀態</th>
        <th>情色</th>
        <th>即時</th>
        <th>作者</th>
        <th>修改者</th>
        <th>發佈日期</th>
        <th>更新日期</th>
        <th width="10%">tags</th>
        <th >編輯</th>
    </tr>
    </tfoot>

    <tbody></tbody>
</table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <div class="dataTables_paginate paging_simple_numbers" id="at_paged" style="text-align:center;">
                
            </div>
        <!-- /.row -->
    </section><!-- /.content -->
    @endsection

@push('scripts')

<script src="/js/admin.grid.js?v=2"></script>
<script src="/js/__form.js"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});






(function(){
    "use strict";

    function bind_delete(){
        $('#articles-table tbody a[data-role="deleteArticle"]').click(function(e){
            var id = this.id;
            
            bootbox.dialog({
                size: 'small',
                message: "HI,你確認要刪除此文章嗎?",
                title: "刪除確認",
                buttons: {
                    danger: {
                        label: "Cancel",
                        className: "btn btn-outline pull-left",
                        callback: function () {
                            //do something
                        }
                    },
                    main: {
                        className: "btn btn-outline ",
                        label: "確認刪除",
                        callback: function (result) {
                            if (result) {
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': "{{csrf_token()}}"
                                    },
                                    type: "POST",
                                    url: myURL['base'] + "/admin/articles/" + id,
                                    data: {
                                        _token: function () {
                                            return "{{ csrf_token() }}";
                                        },
                                        _method: function () {
                                            return "DELETE";
                                        }
                                    },

                                    success: function (backdata) {
                                        filter.reload();

                                    }, error: function (error) {
                                        console.log(error);
                                    }
                                });
                            }
                        }
                    }
                }
            }).find('.modal-dialog').addClass('modal-warning');


        });
    }



    function bind_instant(){
        

        $('#articles-table tbody a[data-role="instantArticle"]').click(function(e){
             
            var id=ff(this).attr('id');
            var title=ff(this).attr('data-title');
            var instant = ff(this).attr('instant');
            var isPorn = ff(this).attr('isPorn');
            var message;
            var new_feedback;

            if(instant=='1'){
                new_feedback='0';
            }else{
                new_feedback='1'; 
            }
            

            if(isPorn == '1'){
                //alert('這是色情 不給改');
                return;
            }



            if (new_feedback=="0") {
                message= "HI,你確認要標記此文章#"+ "<br>「"+title+"」<br>改為「非即時」嗎?";
            } else {
                message= "HI,你確認要將此文章#"+"<br>「"+title+"」<br>改為「即時」嗎?";
            }




            bootbox.dialog({
                size: 'small',
                message: message,
                title: "標記確認",
                buttons: {
                    danger: {
                        label: "Cancel",
                        className: "btn btn-outline pull-left",
                        callback: function () {
                            //do something
                        }
                    },
                    main: {
                        className: "btn btn-outline ",
                        label: "確認修改",
                        callback: function (result) {
                            if (result) {
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': "{{csrf_token()}}"
                                    },
                                    type: "POST",
                                    url: myURL['base'] + "/admin/articles/instant/" + id,
                                    data: {
                                        _token: function () {
                                            return "{{ csrf_token() }}";
                                        },
                                        _method: function () {
                                            return "PUT";
                                        },
                                        instant: function(){
                                            return new_feedback;
                                        }
                                    },

                                    success: function (backdata) {
                                        filter.reload();

                                    }, error: function (error) {
                                        console.log(error);
                                    }
                                });
                            }
                        }
                    }
                }
            }).find('.modal-dialog').addClass('modal-warning');

        });
    }


    function drawPageBar(data){
        // at_paged

        var h='', i, imax, d;

        h+='<input type="number" value="'+filter.page+'" id="page_text" style="width:60px;margin-right:10px;padding:3px">';

        h+='<ul class="pagination">';

        for (i=0,imax=data.length; i < imax; i++) { 
            d=data[i];
            var active = d.active;
            var number = d.number;
            var text = d.text;

            //console.log('active',active);
            if(active == '1') active='active';
            else active='';

            if(text===''){
                h+='<li class="paginate_button disabled"><a aria-controls="articles-table" data-dt-idx="">&nbsp;</a></li>';
            }else{
                h+='<li class="paginate_button '+active+'"><a data-role="paged" aria-controls="articles-table" data-dt-idx="'+number+'" style="float:none;">'+text+'</a></li>';
            }
            
        }

        h+='</ul>';
        

        ff('#at_paged').html(h);


        ff('#page_text').bind('keyup', function(e){
            var keycode = e.keyCode || e.which;
            


            if(keycode === 13){
                filter.page = this.value;
                filter.preload();
            }
        });

        ff('#at_paged a').click(function(e){
            e.preventDefault();
            var role = ff(this).attr('data-role');

            if(!role) return;

            var page = ff(this).attr('data-dt-idx');

            if(!page) return;

            filter.page=page;
            filter.preload();
       });

    }


    function clone(data){
        data = JSON.stringify(data);
        return JSON.parse(data);
    }


    

    function pushState(){
        var query = parseQuery(location.search);
        var queryString = filter.queryString();
        var pathname = location.pathname+queryString;
        window.history.pushState( {pathname:pathname}, document.title, pathname );
    }

    function parseQuery(qstr) {
        var query = {};
        var a = qstr.substr(1).split('&');
        for (var i = 0; i < a.length; i++) {
            var b = a[i].split('=');
            query[decodeURIComponent(b[0])] = decodeURIComponent(b[1] || '');
        }
        return query;
    }


    function funcRef(e){
        console.log(e.state);

        console.log(location.search);

        if(e.state===null){
            history.go(-1);
            return;
        }
        filter.loadSearch(location.search);
        filter.reload();
    }



    var grid = new __grid('#articles-table');
    var filter = new __form('filter_form');

    filter.cache('length');
    filter.cache('category_id');
    filter.cache('status');
    filter.cache('is_deleted');
    filter.cache('created_user');
    filter.cache('flag');
    filter.cache('instant');
    filter.cache('dir');

    console.log(filter._data);



    filter.queryString = function(){
        var string=[];

        var data = clone(this._data);


        if(data['search'] === ''){
            for(var prop in data){
                if(prop.indexOf('search_') !== -1) delete data[prop];
            }
        }

        for( var prop in data){
            var v = data[prop];
            if(v==='') continue;

            string.push(prop+'='+v);
        }

        return '?'+string.join('&');
    };


    filter.loadSearch = function(queryString){
        var data= parseQuery(queryString);

        for(var prop in data){
            
            var v = data[prop];

            if(v==='') continue;

            this[prop] = v;

        }
    };


    filter.reload = function(){

        var data = filter.getAll();

        //cookie.set('length', data['length']);

        //console.log('reload', data);

        $.ajax({
            
            type: "POST",
            
            url: myURL['base'] + "/admin/articles/listData",

            data:data,

            success: function (response) {
                
                //var data = JSON.parse(response);
                var data = response;

                var articles = data.articles;
                var pageBar = data.pageBar;

                //console.log(pageBar);

                grid.loadData(articles);

                drawPageBar(pageBar);

                bind_delete();
                
                bind_instant();

                ff('th[data-role="sorting"]').each(function(i, el){
                    var className = 'sorting_'+filter.dir.toLowerCase();
                    el.className=className;
                    el.setAttribute('data-dir', filter.dir);
                });


            }, error: function (error) {
                console.log(error);
            }
        });
    };



    filter.preload = function(){

        pushState();

        filter.reload();

    };
    

    window.onpopstate = funcRef;

    filter.binding();

    filter.onsubmit(function(e){
        console.log(this._data);
        filter.preload();
        e.preventDefault();
    });


    filter.onchange(function(e){
        //console.log(this);
        //console.log(filter.getAll());

        if(this.type.toLowerCase() === 'search') return;

        filter.preload();
    });

    ff('th[data-role="sorting"]').click(function(e){
        //var className='sorting_'+filter.dir.toLowerCase();
        //this.className=className;
        var dir = this.getAttribute('data-dir');

        if(dir==='ASC') dir = 'DESC';
        else dir = 'ASC';

        filter.dir=dir;
        filter.preload();

    });
 
    filter.loadSearch(location.search);
    window.grid=grid;
    window.filter=filter;

    filter.preload();

   



    
    
}());

// window.history.pushState( {pathname:pathname}, document.title, pathname );
// window.onpopstate = funcRef;    
</script>
@endpush