<rss version="2.0"
     xmlns:content="http://purl.org/rss/1.0/modules/content/">
    <channel>
        <title></title>
        <link>{{route('articles.rss')}}</link>
        <description>
            GetEzInfo RSS
        </description>
        <language>zh-tw</language>
        <lastBuildDate>{{$rss->fb_updated_at}}</lastBuildDate>

        @foreach ($articles as $k=>$article )
             <?php  /*＊因為foreach　for 沒法使data ROW 讀ｎ次,所以就先改這樣*/
                    $url="http://getez.info"; ?>
            @include('articles.rss_block')
                 <?php  /*＊因為foreach　for 沒法使data ROW 讀ｎ次,所以就先改這樣*/
                 $url="https://getez.info";?>
                 @include('articles.rss_block')
        @endforeach

    </channel>
</rss>