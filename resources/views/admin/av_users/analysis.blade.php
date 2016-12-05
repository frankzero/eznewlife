@extends('_layouts/admin')
@section('content')
<div class="row">
    <div class="col-lg-2 col-xs-6">

        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{$statistics['av_user_count']}}</h3>
                <p>會員人數</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-stalker"></i>
            </div>
            <a href="{{route('admin.av_users.ajax')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-2 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $statistics['visit_count']}}</h3>
                <p>本日來訪會員</p>
            </div>
            <div class="icon">
                <i class="ion ion-eye"></i>
            </div>
            <a href="{{route('admin.av_users.ajax')}}?updated_at={{date("Y-m-d")}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div><!-- ./col -->

    <div class="col-lg-2 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $statistics['fresh_count']}}</h3>
                <p>本日註冊人數</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="{{route('admin.av_users.ajax')}}?created_at={{date("Y-m-d")}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div><!-- ./col -->


    <div class="col-lg-2 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>{{$statistics['vote_count']}}<sup style="font-size: 20px"></sup></h3>
                <p>本日投票次數</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a  class="small-box-footer">&nbsp;</a>
           <!--- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
        </div>
    </div><!-- ./col -->

    <div class="col-lg-2 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{$statistics['collect_count']}}</h3>
                <p>本日收藏次數</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a  class="small-box-footer">&nbsp;</a>
         <!--   <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>-->
        </div>
    </div><!-- ./col -->
</div><!-- /.row -->

<div class="row">
    <!-- Left col -->


    <section class="col-lg-6 connectedSortable ui-sortable">
        <!-- Custom tabs (Charts with tabs)-->
        <div class="nav-tabs-custom" style="cursor: move;">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs pull-right ui-sortable-handle">
                <li class=""><a href="#month-fresh-chart" data-toggle="tab" aria-expanded="true">月</a></li>
                <li class=""><a href="#week-fresh-chart" data-toggle="tab" aria-expanded="false">週</a></li>
                <li class="active"><a href="#day-fresh-chart" data-toggle="tab" aria-expanded="false">日</a></li>
                <li class="pull-left header"><i class="fa fa-plus"></i> 新會員人數</li>
            </ul>
            <div class="tab-content no-padding">
                <!-- Morris chart - Sales -->
                <div class="chart tab-pane " id="month-fresh-chart"
                     style="position: relative; height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                    <canvas id="{!! $fresh_count['m']['name'] !!}" width="400" height="400"></canvas>
                </div>
                <div class="chart tab-pane " id="week-fresh-chart"
                     style="position: relative; height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                    <canvas id="{!! $fresh_count['w']['name'] !!}" width="400" height="400"></canvas>
                </div>
                <div class="chart tab-pane active" id="day-fresh-chart"
                     style="position: relative; height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                    <canvas id="{!! $fresh_count['d']['name'] !!}" width="400" height="400"></canvas>
                </div>
            </div>
        </div><!-- /.nav-tabs-custom -->
        <!-- BAR CHART -->
    </section><!-- /.Left col -->
    <!-- right col (We are only adding the ID to make the widgets sortable)-->
    <section class="col-lg-6 connectedSortable ui-sortable">
        <div class="nav-tabs-custom" style="cursor: move;">
            <ul class="nav nav-tabs pull-right ui-sortable-handle">
                <li class=""><a href="#frequent-doughnut" data-toggle="tab" aria-expanded="true">環狀圖</a></li>
                <li class=""><a href="#frequent-bar" data-toggle="tab" aria-expanded="true">堆疊圖</a></li>
                <li class="active"><a href="#frequent-table" data-toggle="tab" aria-expanded="false">Top</a></li>
                <li class="pull-left header"><i class="fa fa-inbox"></i> 會員登入次數</li>
            </ul>
            <div class="tab-content no-padding">
                <div class="chart tab-pane" id="frequent-doughnut"
                     style="position: relative; height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                    <canvas id="FrequentDoughnut" width="400" height="400"></canvas>
                </div>
                <div class="chart tab-pane " id="frequent-bar"
                     style="position: relative; height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                    <canvas id="FrequentBar" width="200" height="200"></canvas>
                </div>
                <div class="chart tab-pane active " id="frequent-table"
                     style="position: relative; height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                    <div class="table-responsive col-lg-12 center-block">
                        <table class="table center-block" width="95%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th width="180">姓名</th>
                                <th></th>
                                <th>E-mail</th>
                                <th><small>次數</small></th>
                                <th width="150">註冊時間</th>
                                <th width="150">上次登入</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($frequent_count["t"] as $k=>$av_user)
                                <tr>
                                    <td>{{$av_user["id"]}}</td>
                                    <td>{{$av_user["nick_name"]}}</td>
                                    <td><img src="{{$av_user["avatar"]}}" class="img-resposive img-circle" width="20px"></td>
                                    <td><a href="mailto:{{$av_user["email"]}}">{{$av_user["email"]}}</a></td>
                                    <td><span class="badge bg-black">{{$av_user["login_counts"]}}</span></td>
                                    <td><small>{{$av_user["created_at"]}}</small></td>
                                    <td><small>{{$av_user["updated_at"]}}</small></td>
                               </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="small-box-footer clearfix">
                            <a href="{{route('admin.av_users.ajax')}}?sort_name=login_counts&sort_order=desc" class="btn btn-xs btn-success bg-aqua-active btn-flat pull-left"> 查看更多 <i class="fa fa-arrow-circle-right"> </i></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <section class="col-lg-6 connectedSortable ui-sortable">
        <!-- Custom tabs (Charts with tabs)-->
        <div class="nav-tabs-custom" style="cursor: move;">
            <!-- Tabs within a box -->
            <ul class="nav nav-tabs pull-right ui-sortable-handle">
                <li class=""><a href="#month-visit-chart" data-toggle="tab" aria-expanded="true">月</a></li>
                <li class=""><a href="#week-visit-chart" data-toggle="tab" aria-expanded="false">週</a></li>
                <li class="active"><a href="#day-visit-chart" data-toggle="tab" aria-expanded="false">日</a></li>
                <li class="pull-left header"><i class="fa fa-eye"></i> 每日來訪會員</li>
            </ul>
            <div class="tab-content no-padding">
                <!-- Morris chart - Sales -->
                <div class="chart tab-pane " id="month-visit-chart"
                     style="position: relative; height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                    <canvas id="{!! $visit_count['m']['name'] !!}" width="400" height="400"></canvas>
                </div>
                <div class="chart tab-pane " id="week-visit-chart"
                     style="position: relative; height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                    <canvas id="{!! $visit_count['w']['name'] !!}" width="400" height="400"></canvas>
                </div>
                <div class="chart tab-pane active " id="day-visit-chart"
                     style="position: relative; height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                    <canvas id="{!! $visit_count['d']['name'] !!}" width="400" height="400"></canvas>
                </div>
            </div>
        </div><!-- /.nav-tabs-custom -->
        <!-- BAR CHART -->
    </section><!-- /.Left col -->
    <section class="col-lg-6 connectedSortable ui-sortable">
        <div class="nav-tabs-custom" style="cursor: move;">
            <ul class="nav nav-tabs pull-right ui-sortable-handle">
                @foreach ($refer_count as $k=>$refer)
                <li class="@if ($k=="today")active @endif"><a href="#{!! $refer["id"] !!}" data-toggle="tab" aria-expanded="true">{!! $refer["display"] !!}</a></li>
                @endforeach
                <li class="pull-left header"><i class="fa fa-map-marker"></i> 來源統計</li>
            </ul>
            <div class="tab-content no-padding">
                @foreach ($refer_count as $k=>$refer)
                <div class="chart tab-pane @if ($k=="today")active @endif" id="{!! $refer["id"] !!}"
                     style="position: relative; height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
                    <canvas id="{!! $refer["name"] !!}" width="400" height="400"></canvas>
                </div>
                @endforeach


             </div>
            </div>

        </div>
    </section>
</div>

@endsection
@push('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.14.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.14.1/locale/zh-tw.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.5/moment-timezone.min.js"></script>
<script  src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.2.1/Chart.min.js"></script>
<script>


console.log(data);
    $("#button").click(function(){

        columns =new Array(1);
        columns[0] =new Array(5);
        columns[0]['name']='id';
        columns[0]['searchable']=true;
        columns[0]['orderable']=true;
        columns[0]['search']=new Array(2);
        columns[0]['search']['value']='';
        columns[0]['search']['regex']=false;
        data=new Array(18);
        data['draw']=3;
        data['columns']=columns;

        $.ajax({
            type:"POST",
            url: "{{route('datatables.av_users.data')}}",
            data: data,// serializes the form's elements.
            success:function(data)
            {
                alert(data);// show response from the php script.
            }
        });

    });
    @include('admin.av_users.analysis_fresh')
    @include('admin.av_users.analysis_visit')
    @include('admin.av_users.analysis_refer')
    <?php $frequent=$frequent_count["d"];?>
    var ctx{!! $frequent["name"] !!} = document.getElementById('{!! $frequent["name"] !!}');
    var {!! $frequent["name"] !!} = new Chart(ctx{!! $frequent["name"] !!}, {
                type: '{!! $frequent["type"] !!}',//'horizontalBar',
        data: {
            labels: [{!!$frequent['labels']!!}],
            datasets: [{
                label: '{!!$frequent['label']!!}',
                data: [{!!$frequent['data']!!}],
                backgroundColor: {!!$frequent['backgroundColor']!!},
                borderColor: {!!$frequent['borderColor']!!},
                borderWidth: 1
            }]
        },
        options: {

            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero:false
                    }
                }]
            },
            tooltips: {
                mode: 'label'
            },
            legend: {
                position: 'top',
            },
        animation: {
            animateScale: true,
            animateRotate: true
        },
                responsive: true,
                maintainAspectRatio: false
            }
    });
    <?php $frequent_bar=$frequent_count["b"];$s=0;?>
    var data = {
        labels: ['{!!$frequent_bar['labels'] !!}'],
        datasets: [

        @foreach($frequent_bar['data'] as $k=>$data)
            {

                label:'{{$k}}次',
                backgroundColor:'#{{$frequent_bar['backgroundColor'][($s)]}}',
                borderColor:'#{{$frequent_bar['borderColor'][($s)]}}',
                data: [{{$data}}]
                <?php $s++;?>
            } @if ($s<=count($frequent_bar['data'])),@endif
        @endforeach

        ]
    };

    var ctx = document.getElementById('{{$frequent_bar['name']}}').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
           /* title:{
                display:true,
                text:'{!!$frequent_bar['labels'] !!}'
            },*/
            tooltips: {
                mode: 'label'
            },
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    stacked: true,
                }],
                yAxes: [{
                    stacked: true
                }]
            }
        }

    });


</script>

@endpush