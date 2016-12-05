@extends('_layouts.admin')

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">


                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">動圖列表區</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="animations-table" class="table table-bordered table-striped" data-page-length="10"
                               data-order="[[ 0, &quot;asc&quot; ]]">
                            <thead>
                            <tr>
                                <th style="width:10px !important">#</th>
                                <th width="40%">標題</th>
                                <th>作者</th>
                                <th>修改者</th>
                                <th>更新日期</th>
                                <th style="width:150px">編輯</th>
                            </tr>
                            </thead>
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


        var oTable = $('#animations-table').DataTable({

            processing: true,
            serverSide: true,
            ajax:{
                url:'{!! route('datatables.animations.data') !!}',
                data: function (d) {
                    d.name = $('input[name=name]').val();
                    d.email = $('input[name=email]').val();
                },
                method:'POST'

            },
            columns: [
                {data: 'id', name: 'id', orderable: false, searchable: false},
                {data: 'title', name: 'title'},
                {data: 'author.name', name: 'author', searchable: false, orderable: false},
                {data: 'updater.name', name: 'updater', orderable: false, searchable: false},
                {data: 'updated_at', name: 'updated_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],

            initComplete: function () {
                this.api().columns().every(function () {
                    var column = this;
                    var input = document.createElement("input");
                    $(input).appendTo($(column.footer()).empty())
                            .on('change', function () {
                                column.search($(this).val(), false, false, true).draw();
                            });
                });
            },


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


</script>
@endpush