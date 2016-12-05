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
    show_now_date
</script>