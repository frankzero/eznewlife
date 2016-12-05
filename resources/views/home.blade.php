@extends('_layouts/enl')
@section('content')
    <div class="col-md-9 blog-main">
        <article class="blog-post">
            <div class="row center-block ">
                <div id="myCarousel" class="carousel slide col-md-8">

                    <div class="carousel-inner">
                        @foreach ($expert_articles as $k=> $article)
                            <div class="item @if($k==0) active @endif">
                                @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                                    <img src="{!!("/focus_photos/".$article->photo) !!}" class="img-responsive" alt="{{$article->title}}">
                                @else
                                    <img src="http://www.southlandvw.ca/static/img/new-volkswagen-vehicles-on-the-road.jpg" alt="{{$article->title}}">
                                @endif
                                <div class="carousel-caption">
                                    <h3><a href="{{route('articles.show', ['id'=>$article->id,'title'=>$article->title])}}">{{$k}}{{$article->title}}</a></h3>
                                </div>
                            </div><!-- End Item -->

                        @endforeach

                    </div>
                    <!-- Controls -->
                    <a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="fa fa-caret-left btn-circle"></span></a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="fa fa-caret-right btn-circle"></span></a>
                </div>

                    <div class="list-group col-md-4 article-rand" style="height: 100%">

                        @for ($i = 4; $i < 8; $i++)
                            <a class="list-group-item list-group-item-black " href="{{route('articles.show', ['id'=>$rand_articles[$i]->id,'title'=>$rand_articles[$i]->title])}}" style="height: 25%">
                                <h4 class="list-group-item-heading" >{{$k}}
                                    @if (strlen(strip_tags($rand_articles[$i]->title))<100)
                                        {!!strip_tags($rand_articles[$i]->title)!!}
                                    @else
                                        {!!substr(strip_tags($rand_articles[$i]->title),0,99)!!}...
                                    @endif
                                </h4>
                            </a>
                        @endfor

                     </div>
            </div>

       </article>

        <article class="blog-post">
            <hr>
            <div class="row center-block article-other-main">
            @foreach ($other_articles as $k=> $article)
                    <div class="row center-block">
            <div class="col-md-6 col-sm-6 col-lg-6">


                    <a href="{{route('articles.show', ['id'=>$article->id,'title'=>$article->title])}}">
                    @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                        <img  src="{!!("/focus_photos/".$article->photo) !!}" class="img-responsive img-thumbnail" alt="{{$article->title}}">
                    @else
                        <img src="http://www.southlandvw.ca/static/img/new-volkswagen-vehicles-on-the-road.jpg"   class="img-responsive img-thumbnail"  alt="{{$article->title}}">
                    @endif
                    </a>

            </div>

            <div class="col-md-6 col-sm-6 col-lg-6">

                    <div class="article-other-text" style="height: 100%">
                       <div class="body"> <h5><a href="{{route('articles.show', ['id'=>$article->id,'title'=>$article->title])}}"><strong>{{$article->title}}</strong></a></h5>
                       </div>
    <HR/>
                        <div class="body">
                        <a href="{{route('articles.show', ['id'=>$article->id,'title'=>$article->title])}}">
                        @if (strlen(strip_tags($article->content))<61)
                            {!!strip_tags($article->content)!!}
                        @else
                            {!!substr(strip_tags($article->content),0,60)!!}...
                        @endif
                        </a></div>
                    </div>

            </div>
                        </div>
            @if ($k<3)
            <hr>
            @endif
            @endforeach
                <div id="loader-icon" class="overlay text-center body">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div>
            {!! $other_articles->render() !!}
            <input type="hidden" name="total" id="total" value="{{1}}">
            <input type="hidden" name="page" id="page" value="{{1}}">
            <input type="hidden" name="rowcount" id="rountcount" value="{{1}}">
            <input type="hidden" name="total_page" id="total_page" value="{{5}}">
            total-page
        </article>
    </div>
    <div id="ajaxContent">
    </div>




@endsection
@push('scripts')
<script>
$("window").load(function() {
    var boxheight = $('#myCarousel .carousel-inner').innerHeight();
    var itemlength = $('.article-rand .list-group-item').length;
    console.log(boxheight+"-"+itemlength);
    var triggerheight = Math.floor (boxheight/itemlength+1);
    $('.list-group-item').outerHeight(triggerheight);
});

$('#ajaxContent').load('{{route('articles.ajax' )}}');

$('.pagination a').on('click', function (event) {
    event.preventDefault();
    if ( $(this).attr('href') != '#' ) {
        $("html, body").animate({ scrollTop: 0 }, "fast");
        $('#ajaxContent').load($(this).attr('href'));
    }
});



    $(document).ready(function(){
        function getresult(url) {
            $.ajax({
                url: url,
                type: "GET",
                data:  {rowcount:$("#rowcount").val()},
                beforeSend: function(){
                    $('#loader-icon').show();
                },
                complete: function(){
                    $('#loader-icon').hide();
                },
                success: function(data){
                    $("#faq-result").append(data);
                },
                error: function(){}
            });
        }
        $(window).scroll(function(){
            if ($(window).scrollTop() == $(document).height() - $(window).height()){
                if($(".pagenum:last").val() <= $("#total_page").val()) {
                    var pagenum = parseInt($(".pagenum:last").val()) + 1;
                    getresult('{{route('articles.index' )}}');
                }
            }
        });
    });

</script>
@endpush