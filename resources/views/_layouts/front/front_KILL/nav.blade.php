<div class="widewrapper masthead navbar-fixed-top">
    <div class="container">
        <a href="{{URL('/')}}" id="logo">
            <img src="{{asset('images/logo.png')}}" alt="logo">
        </a>

        <div class="share-circle-main" style="display: none">
        <a class="btn bg-light-blue btn-group-xs share-circle"
           href="http://www.facebook.com/sharer/sharer.php?u={!!rawurldecode($page['share_url'])!!}&description={!!rawurldecode($page['sub_title'])!!}"
           target="_blank" alt="Facebook">
            <i class="fa fa-facebook"></i>
        </a>
        <a class="btn bg-red btn-group-xs share-circle"
           href="https://plus.google.com/share?url={!!rawurldecode($page['share_url'])!!}&t={!!rawurldecode($page['sub_title'])!!}"
           target="_blank">
            <i class="fa fa-google-plus"></i></a>

            <a class="btn bg-aqua share-circle"
               href="https://twitter.com/intent/tweet?url={!!rawurldecode($page['share_url'])!!}&text={!!rawurldecode($page['sub_title'])!!}"
               target="_blank">
                <i class="fa fa-twitter"></i></a>

            <a class="btn share-circle" style="background-color: rgb(210,87,47) ;color:white "
               href="http://www.plurk.com/?qualifier=shares&status={!!rawurldecode($page['share_url'])!!}"
               target="_blank">
                <strong style="font-family: Noto Sans CJK JP Black">P</strong></a>
        </div>
        <div id="mobile-nav-toggle" class="pull-right">
            <a href="#" data-toggle="collapse" data-target=".clean-nav .navbar-collapse">
                <i class="fa fa-bars"></i>
            </a>
        </div>

        <nav class="pull-right clean-nav">

            <div class="collapse navbar-collapse">
                <ul class="nav nav-pills navbar-nav">
                    @foreach($categories as $cid=>$name)
                        <li @if ( $page['sub_title'] ==$name ) class=" active" @endif>

                            <a href="{{route('articles.category',[$cid,$name])}}">{{$name}}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </nav>

    </div>
</div>