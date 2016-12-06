<script>
    clipboardDemos=new Clipboard('.copy');
    clipboardDemos.on('success', function(e) {
        e.clearSelection();
        console.log(e);
        console.log(e.id);
        console.info('Action:', e.action);
        console.info('Text:', e.text);
        console.info('Trigger:', e.trigger);
        //  $(e.trigger).tooltip('show');
        // var tmp=e.trigger;
        //  console.log(tmp.indexOf("id="));
    });

</script>