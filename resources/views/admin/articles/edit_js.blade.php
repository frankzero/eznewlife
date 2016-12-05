<script>
    var tags = [
        @foreach ($my_tags as $tag)
        {tag: "{{$tag}}" },
        @endforeach
        ];
    $( document ).ready(function() {

        $('#tags').selectize({
            delimiter: ',',
            persist: false,
            valueField: 'tag',
            labelField: 'tag',
            searchField: 'tag',
            options: tags, //記憶選單
            create: function(input) {
                return {
                    tag: input
                }
            }

        });
    });
    $( "#show_now_date" ).click(function() {
        now = new Date();
        year = "" + now.getFullYear();
        month = "" + (now.getMonth() + 1); if (month.length == 1) { month = "0" + month; }
        day = "" + now.getDate(); if (day.length == 1) { day = "0" + day; }
        hour = "" + now.getHours(); if (hour.length == 1) { hour = "0" + hour; }
        minute = "" + now.getMinutes(); if (minute.length == 1) { minute = "0" + minute; }
        $("#publish_at").val(year + "-" + month + "-" + day + " " + hour + ":" + minute );
        saveToDB();
    });
    clipboardDemos=new Clipboard('.copy');
    clipboardDemos.on('success', function(e) {
        e.clearSelection();
        console.log(e);
        console.log(e.id);
        console.info('Action:', e.action);
        console.info('Text:', e.text);
        console.info('Trigger:', e.trigger);
        $(e.trigger).attr('title', '已複製!!').tooltip('fixTitle').tooltip('show');
        $(e.trigger).attr('title', '點擊可複製').tooltip('fixTitle');
        //  $(e.trigger).tooltip('show');
        // var tmp=e.trigger;
        //  console.log(tmp.indexOf("id="));
    });
</script>