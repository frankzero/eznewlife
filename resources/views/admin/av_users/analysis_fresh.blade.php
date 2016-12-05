@foreach ($fresh_count as $k=>$fresh)
    var ctx{!! $fresh["name"] !!} = document.getElementById('{!! $fresh["name"] !!}');
    var {!! $fresh["name"] !!} = new Chart(ctx{!! $fresh["name"] !!}, {
    type: 'bar',
    data: {
    labels: [{!!$fresh['labels']!!}],
    datasets: [{
    label: '{!!$fresh['label']!!}',
    data: [{!!$fresh['data']!!}],
    backgroundColor: '{!!$fresh['backgroundColor']!!}',
    borderColor: '{!!$fresh['borderColor']!!}',
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
    responsive: true,
    maintainAspectRatio: false
    }
    });
@endforeach