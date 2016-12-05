<script>
    clipboardDemos=new Clipboard('.copy');
    clipboardDemos.on('success', function(e) {
        e.clearSelection();

        console.info('Action:', e.action);
        console.info('Text:', e.text);
        console.info('Trigger:', e.trigger);
        //  $(e.trigger).tooltip('show');
        // var tmp=e.trigger;
        //  console.log(tmp.indexOf("id="));
        $(e.trigger).attr('title', '已複製!!').tooltip('fixTitle').tooltip('show');
        $(e.trigger).attr('title', '點擊可複製').tooltip('fixTitle');
    });
</script>