
<script>

    @if (Session::has('message'))
    bootbox.dialog({
        title: "提示訊息",
        size: 'small',
        buttons: {
            'ok': {
                label: 'Close',
                className: 'btn btn-outline'
            }
        },
        message: '{!! htmlspecialchars_decode(Session::get('message'))!!}'
    }).find('.modal-dialog') @if (Session::has('style')).addClass('modal-{{Session::get('style')}}' )@else.addClass('modal-success' );@endif
  /*  bootbox.alert({
        title:'提示訊息',
        animate:false,
        size: 'small',
         buttons: {
            'ok': {
                label: 'Close',
                className: 'btn-success outline'
            }
        },
        message:'{{Session::get('message')}}',
        callback: function(){  }
    }).find('.modal-dialog').addClass('modal-success' );*/
    @endif

</script>