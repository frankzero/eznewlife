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
            <div class="col-xs-8">


                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">小編動圖列表區</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
<table id="animations-table" class="table table-condensed  table-hovered table-responsive mailbox-messages">
    <thead>
    <tr>
        <th>#</th>
        <th>縮圖</th>
        <th>標題</th>
        <th>作者</th>
        <th>修改者</th>
        <th>更新日期</th>
        <th >編輯</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th >#</th>
        <th>縮圖</th>
        <th width="30%">標題</th>
        <th>作者</th>
        <th>修改者</th>
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

    var oTable = $('#animations-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url:'{!! route('datatables.animations.data') !!}',
            method: 'POST'
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'thumbnail', name: 'title',orderable: false, searchable: false},
            {data: 'title', name: 'title'},
            {data: 'author', name: 'author'},
            {data: 'updater', name: 'updater'},
            {data: 'updated_at', name: 'animations.updated_at'},
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


               // a= $('#animations-table thead th'.text();
                //console.log(a);
             if ( i<9 && i!=1) {
                    if (i==2) input.size = 20;
                    if (i==0) input.size = 5;
                    var th = $('#animations-table tfoot th').eq(i);
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
            message: "HI,你確認要刪除此動圖嗎?",
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
                                url: myURL['base'] + "/admin/animations/" + id,
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
                                        message: '動圖#' + id + '已刪除',
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