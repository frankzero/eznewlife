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
                    <!-- /.box-header -->
                    <div class="box-body">
<table id="articles-table" class="table table-condensed  table-hovered table-responsive mailbox-messages">
    <thead>
    <tr>
        <th>#</th>
        <th>標題</th>
        <th>類別</th>
        <th>狀態</th>
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
        <th >#</th>
        <th width="30%">標題</th>
        <th>類別</th>
        <th>狀態</th>
        <th>作者</th>
        <th>修改者</th>
        <th>發佈日期</th>
        <th>更新日期</th>
        <th width="10%">tags</th>
        <th >編輯</th>
    </tr>
    </tfoot>
</table>
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
@push('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    var oTable = $('#articles-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url:'{!! route('datatables.articles.data') !!}',
            method: 'POST'
        },
        columns: [
            {data: 'id', name: 'unique_id'},
            {data: 'title', name: 'articles.title'},
            {data: 'categories_name', name: 'categories_name'},
            {data: 'status', name: 'articles.status'},
            {data: 'author', name: 'author'},
            {data: 'updater', name: 'updater'},
            {data: 'publish_at', name: 'articles.publish_at'},
            {data: 'updated_at', name: 'articles.updated_at'},
            {data: 'tags', name: 'tags',orderable: false, searchable: false},
            {data: 'action', name: 'action',orderable: false, searchable: false}

        ],
        order: [[0, 'desc']],

        //顯示圖片 bootstrap 特效
        "fnInfoCallback": function () {
            $('.popoverthis').popover({
                trigger: "hover",
                placement: "right",
                html: true
            });
        },
        initComplete: function () {

            var api = this.api();
            api.columns().indexes().flatten().each(function(i) {
                var column = api.column(i);
                var input = document.createElement("input");
                input.size = 10;


               // a= $('#articles-table thead th'.text();
                //console.log(a);
                if (i==2) {
                    var categories ={!!$categories!!};

                    var selectList = document.createElement("select");

                    var option = document.createElement("option");
                    option.value = "";
                    option.text = '所有類別';
                    selectList.appendChild(option);

                    for (var key in categories) {
                        //alert('key: ' + key + '\n' + 'value: ' + obj[key])
                        var option = document.createElement("option");
                        option.value = categories[key];
                        option.text = categories[key];
                        selectList.appendChild(option);
                    }

                    $(selectList).appendTo($(column.footer()).empty())
                            .on('change', function () {
                                column.search($(this).val()).draw();
                            });
                }else if (i==8) {
                        var tags={!!$tags!!};

                        var selectList = document.createElement("select");

                        var option = document.createElement("option");
                        option.value = "";
                        option.text =  'All';
                        selectList.appendChild(option);

                        for(var key in tags) {
                            //alert('key: ' + key + '\n' + 'value: ' + obj[key])
                            var option = document.createElement("option");
                            option.value = tags[key];
                            option.text =  tags[key];
                            selectList.appendChild(option);
                        }

                        $(selectList).appendTo($(column.footer()).empty())
                                .on('change', function () {
                                    column.search($(this).val()).draw();
                                });

                }else if (i==3){
                    var status={!!$status!!};
                    var selectStatus = document.createElement("select");
                    var option = document.createElement("option");
                    option.value = "";
                    option.text =  '所有狀態';
                    selectStatus.appendChild(option);
                    for(var key in status) {
                        //alert('key: ' + key + '\n' + 'value: ' + obj[key])
                        var option = document.createElement("option");
                        option.value = key;
                        option.text =  status[key];
                        selectStatus.appendChild(option);
                    }

                    $(selectStatus).appendTo($(column.footer()).empty())
                            .on('change', function () {
                                column.search($(this).val()).draw();
                            });
                }else if ( i<9) {
                    if (i==1) input.size = 20;
                    if (i==0) input.size = 5;
                    var th = $('#articles-table tfoot th').eq(i);
                    input.placeholder =th.text();
                    console.log(th.text());
                  // console.log(column);
                    $(input).appendTo($(column.footer()).empty())
                            .on('change', function () {
                                column.search($(this).val()).draw();
                            });
                }
            });
        }
    });

    //刪除確認
    function databases_delete_confirm(id,unique_id) {

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
                                    console.log(backdata);
                                    oTable.ajax.reload(null, false);
                                    bootbox.alert({
                                        role: 'alert',
                                        title: '提示訊息',
                                        animate: false,
                                        size: 'small',
                                        buttons: {
                                            'ok': {
                                                label: 'Close',
                                                className: 'btn btn-outline'
                                            }
                                        },
                                        message: '文章#' + unique_id + '已刪除',
                                        callback: function () { /* your callback code */
                                        }
                                    }).find('.modal-dialog').addClass('modal-success');

                                }, error: function (error) {
                                    console.log(error);
                                }
                            });
                        }
                    }
                }
            }
        }).find('.modal-dialog').addClass('modal-warning');


    }
    </script>
@endpush