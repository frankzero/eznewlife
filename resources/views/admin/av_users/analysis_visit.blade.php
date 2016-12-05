@foreach ($visit_count as $k=>$visit)
    var ctx{!! $visit["name"] !!} = document.getElementById('{!! $visit["name"] !!}');
    var {!! $visit["name"] !!} = new Chart(ctx{!! $visit["name"] !!}, {
    type: 'bar',
    data: {
    labels: [{!!$visit['labels']!!}],
    datasets: [{
    label: '{!!$visit['label']!!}',
    data: [{!!$visit['data']!!}],
    backgroundColor: '{!!$visit['backgroundColor']!!}',
    borderColor: '{!!$visit['borderColor']!!}',
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