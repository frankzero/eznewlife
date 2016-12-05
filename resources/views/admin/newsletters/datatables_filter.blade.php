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
                        <h3 class="box-title">電子報列表區</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
<table id="newsletters-table" class="table table-condensed  table-hovered table-responsive mailbox-messages">
    <thead>
    <tr>
        <th>#</th>
        <th>標題</th>
        <th>作者</th>
        <th>修改者</th>
        <th>寄送日期</th>
        <th>更新日期</th>
        <th >編輯</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th >#</th>
        <th width="30%">標題</th>
        <th>作者</th>
        <th>修改者</th>
        <th>寄送日期</th>
        <th>更新日期</th>
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

    var oTable = $('#newsletters-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url:'{!! route('datatables.newsletters.data') !!}',
            method: 'POST'
        },
        columns: [
            {data: 'id', name: 'newsletters.id'},
            {data: 'title', name: 'newsletters.title'},
            {data: 'author', name: 'author'},
            {data: 'updater', name: 'updater'},
            {data: 'send_at', name: 'newsletters.send_at'},
            {data: 'updated_at', name: 'newsletters.updated_at'},
            {data: 'action', name: 'action',orderable: false, searchable: false}

        ],

        order: [[5, 'desc']],

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
                input.size = 5;


               // a= $('#newsletters-table thead th'.text();
                //console.log(a);
            if ( i<6) {
                    if (i==1) input.size = 20;
                    if (i==0) input.size = 5;
                    var th = $('#newsletters-table tfoot th').eq(i);
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
                                url: myURL['base'] + "/admin/newsletters/" + id,
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