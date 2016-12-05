@extends('_layouts/enl')
@section('content')
    <div class="col-md-8 blog-main">

        <div class="center-block hover11 column">
            <?php $y=0; ?>
            @foreach ($cate_articles as $k=> $article)
                <?php $y++; ?>
                @if ($y%2==1)
                        <div class="row">
                            @endif
                <div class="col-lg-6 col-md-12 ">
                    <div class="thumbnail">
                        <div style="margin-bottom: 2%">
                            <figure>
                            <a href="{{route('articles.show', ['id'=>$article->id,'title'=>$article->title])}}">
                                @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                                    <img src="{!!("focus_photos/".$article->photo) !!}?lastmod={!!date("YmdH")!!}"
                                         class="img-responsive " alt="{{$article->title}}">
                                @else
                                    <img src="http://www.southlandvw.ca/static/img/new-volkswagen-vehicles-on-the-road.jpg"
                                         class="img-responsive img-thumbnail" alt="{{$article->title}}">
                                @endif
                            </a>
                             </figure>

                            <div class="info">
                                <h4>
                                    <a href="{{route('articles.show', ['id'=>$article->id,'title'=>$article->title])}}"><strong>
                                    @if (strlen(strip_tags($article->content))==mb_Strlen(strip_tags($article->content)))

                                    @else
                                            @if (strlen(strip_tags($article->title))<53)
                                                {!!strip_tags($article->title)!!}
                                            @else
                                                {!!substr(strip_tags($article->title),0,51)!!}...
                                            @endif
                                            @endif
                                        </strong></a>
                                </h4>
                            </div>
                       </div>


                        <div class="caption category-context" >
                            <a href="{{route('articles.show', ['id'=>$article->id,'title'=>$article->title])}}">
                                @if (strlen(strip_tags($article->content))==mb_Strlen(strip_tags($article->content)))

                                @else
                                    @if (strlen(strip_tags($article->content))<100)
                                        {!!strip_tags($article->content)!!}
                                    @else
                                        {!!substr(strip_tags($article->content),0,99)!!}...
                                    @endif
                                @endif
                            </a>
                        </div>
                    </div>
                </div>

                @if ($y==5 and $k==4)
                <?php $y++;?>
                <div class="col-lg-6 col-md-12 ">
                    <div class="alert  alert-dismissible   bg-gray text-center" style="padding-top:100px;padding-bottom:100px" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h1 class="text-muted"><i class="fa fa-bullhorn"></i> advertise</h1>
                    </div>
                </div>
                @endif

                @if ($y%2==0 or ($cate_articles->count()==($k+1)))
                        </div>
                @endif
            @endforeach

        </div>
        <article class="ad-lg">
            <div class="row">
                <div class="alert  alert-dismissible col-lg-10 col-lg-offset-1   bg-gray text-center" style="padding-top:20px;padding-bottom:20px" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h1 class="text-muted"><i class="fa fa-bullhorn"></i> advertise</h1>
                </div>
            </div>

        </article>
        <div class="row text-center"><div class="col-lg-offset-3 col-offset-6">{!!$cate_articles->render()!!}</div></div>
        <article class="ad-lg">
            <div class="row">
                <div class="alert  alert-dismissible col-lg-10 col-lg-offset-1   bg-gray text-center" style="padding-top:20px;padding-bottom:20px" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h1 class="text-muted"><i class="fa fa-bullhorn"></i> advertise</h1>
                </div>
            </div>

        </article>

    </div>


@endsection
