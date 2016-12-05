<!doctype html>
<html lang="zh-TW" prefix="op: http://media.facebook.com/op#">
<head>
    <meta charset="utf-8">
    <link rel="canonical" href="{{$article->share_url}}">
    <meta property="op:markup_version" content="v1.0">
    <meta property="fb:article_style" content="default">
    <meta property="fb:use_automatic_ad_placement" content="true">
    <title>{{$article->title}}</title>
</head>
<body>
<article>
    <header>
        <section class="op-ad-template">
            <!-- Ads to be automatically placed throughout the article -->
            <figure class="op-ad op-ad-default">
                <iframe width="300" height="250" src="https://www.facebook.com/adnw_request?placement=643012882521252_643012889187918&adtype=banner300x250"></iframe>
            </figure>

        </section>

        <!-- The header image shown inside your article -->
        <figure data-mode=aspect-fit>
            <img src=@if (isset( $article->photo) and File::exists( public_path() . '/focus_photos'."/". $article->photo) and !empty( $article->photo))"{!!'http://getez.info/focus_photos'."/". $article->photo!!}"
            @else "{!!"http://getez.info/images/index.png"!!}"@endif/>
            <figcaption>{{$article->title}}</figcaption>
        </figure>

        <!-- The title and subtitle shown in your article -->
        <h1>{{$article->title}}</h1>
         <h2>{{ $article->category->name}}</h2>
        <!-- A kicker for your article -->
        <!-- The author of your article -->
        <address>
            {{$article->author->name}}
        </address>
        <!-- The published and last modified time stamps -->
        <time class="op-published" datetime="{{$article->fb_publish_at}}">{{$article->publish_at}}</time>
        <time class="op-modified" dateTime="{{$article->fb_updated_at}}">{{$article->updated_at}}</time>
    </header>
	
    {!!$article->content!!}

    <figure class="op-tracker">
        <iframe ><script>
                var _gaq = _gaq || [];
                _gaq.push(['_setAccount', 'UA-29579256-1']);
                _gaq.push(['_trackPageview']);

                (function() {
                    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                })();</script>

        </iframe>
    </figure>
    <footer>   
	<ul class="op-related-articles">
        @foreach ($foot_articles as $k=>$article)
  
            <li><a href="{{"http://getez.info/".$article->ez_map[0]->unique_id."/".hyphenize($article->title)}}">{{$article->title}}</a></li>
       
        @endforeach 
		</ul>
        <small>© GetEzInfo 2016</small>
    </footer>
</article>
</body>
</html>