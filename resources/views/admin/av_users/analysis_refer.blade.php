@foreach ($refer_count as $k=>$refer)
    var ctx{!! $refer["name"] !!} = document.getElementById('{!! $refer["name"] !!}');
    var {!! $refer["name"] !!} = new Chart(ctx{!! $refer["name"] !!}, {
        type: '{{$refer['type']}}',
        data: {
            labels: [{!!$refer['labels']!!}],
            datasets: [{
                label: '{!!$refer['label']!!}',
                data: [{!!$refer['data']!!}],
                backgroundColor: {!!$refer['backgroundColor']!!},
                borderWidth: 1
            }]
        },
        options: {
    multiTooltipTemplate : "<%=datasetLabel%> : <%=value%>" ,
    scales: {
            yAxes: [{
                ticks: {
                beginAtZero:false
                }
            }]
        },
        responsive: true,
        maintainAspectRatio: false
    }
    });
@endforeach

