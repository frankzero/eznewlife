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
                        <h3 class="box-title">Log Report</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
<table id="logs-table" class="table table-condensed  table-hovered table-responsive mailbox-messages">
    <thead>
    <tr>
        <th>#</th>
        <th>值</th>
        <th>錯誤碼</th>
        <th>更新日期</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th >#</th>
        <th>值</th>
        <th>錯誤碼</th>
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

    var oTable = $('#logs-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url:'{!! route('datatables.logs.data') !!}?type={{$type}}',
            method: 'POST'
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'data', name: 'data'},
            {data: 'description', name: 'description'},
            {data: 'updated_at', name: 'updated_at'},

        ],

        order: [[3, 'desc']],

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


               // a= $('#logs-table thead th'.text();
                //console.log(a);
            if ( i<6) {
                    if (i==1) input.size = 20;
                    if (i==0) input.size = 5;
                    var th = $('#logs-table tfoot th').eq(i);
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


    </script>
@endpush