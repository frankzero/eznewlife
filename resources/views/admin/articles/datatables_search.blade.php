@extends('_layouts.admin')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">


                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">小編文章列表區</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="articles-table" class="table table-bordered table-striped" data-page-length="10"
                               data-order="[[ 0, &quot;asc&quot; ]]">
                            <thead>
                            <tr>
                                <th style="width:10px !important">#</th>
                                <th width="40%">標題</th>
                                <th>類別</th>
                                <th>作者</th>
                                <th>修改者</th>
                                <th>發佈日期</th>
                                <th>更新日期</th>
                                <th style="width:150px">編輯</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th style="width:10px !important">#</th>
                                <th width="40%">標題</th>
                                <th>類別</th>
                                <th>作者</th>
                                <th>修改者</th>
                                <th>發佈日期</th>
                                <th>更新日期</th>
                                <th style="width:150px">編輯</th>
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

    var oTable;
    $(function () {
        oTable = initTable();
    });
    function initTable() {

        var oTable = $('#articles-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! route('datatables.articles.data') !!}',

            columns: [
                {data: 'id', name: 'id', orderable: false, searchable: false},
                {data: 'title', name: 'title'},
                {data: 'category.name', name: 'category_id', orderable: false, searchable: false},
                {data: 'author.name', name: 'author', searchable: false, orderable: false},
                {data: 'updater.name', name: 'updater', orderable: false, searchable: false},
                {data: 'publish_at', name: 'publish_at'},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],




            order: [[0, 'desc']],
            //顯示圖片 bootstrap 特效
            "fnInfoCallback": function () {
                $('.popoverthis').popover({
                    trigger: "hover",
                    placement: "right",
                    html: true
                });
            }


        });

    }
    //刪除確認
    function databases_delete_confirm(id) {

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
                                            message: '文章#' + id + '已刪除',
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