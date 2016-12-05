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
                        <h3 class="box-title">{{$page['sub_table']}}</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
<table id="av_users-table" width="100%" class="table table-condensed  table-hovered table-responsive mailbox-messages">
    <thead>
    <tr>
        <th style="width:10px !important">#</th>
        <th width="10%">姓名</th>
        <th>大頭貼</th>
        <th width="15%">E-mail</th>
        <th>登入次數</th>
        <th>新增日期</th>
        <th>更新日期</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th style="width:10px !important">#</th>
        <th width="10%">姓名</th>
        <th>大頭貼</th>
        <th width="15%">E-mail</th>
        <th>登入次數</th>
        <th>新增日期</th>
        <th>更新日期</th>
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
    var oTable = $('#av_users-table').DataTable({
        processing: true,
        serverSide: true,
        aLengthMenu: [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ],
        iDisplayLength: 25,
        ajax: {
            url:'{!! route('datatables.av_users.data') !!}',
            method: 'POST'
        },

        columns: [
            { data: 'id', name: 'id' },
            {data: 'nick_name', name: 'nick_name'},
            { data: 'avatar', name: 'avatar' },
            { data: 'email', name: 'email' },
            {data: 'login_counts', name: 'login_counts'},
            { data: 'created_at', name: 'created_at' },
            { data: 'updated_at', name: 'updated_at' }
        ],

        order: [[0, 'desc']],

        initComplete: function () {

            var api = this.api();
            api.columns().indexes().flatten().each(function(i) {
                var column = api.column(i);
                var input = document.createElement("input");
                input.size = 5;


                // a= $('#av_users-table thead th'.text();
                //console.log(a);
                if ( i<7 && i!=2) {
                    if (i==0 || i==4) input.size = 5; else input.size = 15;
                    var th = $('#av_users-table tfoot th').eq(i);
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
    function databases_delete_confirm(id) {

        bootbox.dialog({
            size: 'small',
            message: "HI,你確認要刪除此電子報嗎?",
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
                                url: myURL['base'] + "/admin/av_users/" + id,
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
                                        message: '電子報#' + id + '已刪除',
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