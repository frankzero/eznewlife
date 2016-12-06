@extends('_layouts/front')
@section('content')
    <div class="col-md-8 blog-main">
                <article class="blog-post">
                    <header>
                    </header>
                    <div class="body">
                        <h1>{{ $article->title }}</h1>
                        <div class="meta">
                            <i class="fa  fa-bookmark"></i>  {{$page['title']}}<i class="fa fa-user"></i> {{ $article->author->name }} <i class="fa fa-calendar"></i>{{substr($article->publish_at,0,10)}} &nbsp;
                            <!-- Go to www.addthis.com/dashboard to generate a new set of sharing buttons -->
                            <!-- Go to www.addthis.com/dashboard to generate a new set of sharing buttons -->
                            <i class="fa  fa-share-alt"></i>
                            <!-- Go to www.addthis.com/dashboard to generate a new set of sharing buttons -->
                            <a href="https://api.addthis.com/oexchange/0.8/forward/facebook/offer?url={!!rawurldecode(Request::url())!!}&pubid=ra-563b331d9e76fc9f&ct=1&title={{rawurldecode($page['sub_title'])}}&pco=tbxnj-1.0" target="_blank"><img src="https://cache.addthiscdn.com/icons/v2/thumbs/16x16/facebook.png" border="0" alt="Facebook"/></a>
                            <a href="https://api.addthis.com/oexchange/0.8/forward/google_plusone_share/offer?url={!!rawurldecode(Request::url())!!}&pubid=ra-563b331d9e76fc9f&ct=1&title={{rawurldecode($page['sub_title'])}}&pco=tbxnj-1.0" target="_blank"><img src="https://cache.addthiscdn.com/icons/v2/thumbs/16x16/google_plusone_share.png" border="0" alt="Google+"/></a>
                            <a href="https://api.addthis.com/oexchange/0.8/forward/plurk/offer?url={!!rawurldecode(Request::url())!!}&pubid=ra-563b331d9e76fc9f&ct=1&title={{rawurldecode($page['sub_title'])}}&pco=tbxnj-1.0" target="_blank"><img src="https://cache.addthiscdn.com/icons/v2/thumbs/16x16/plurk.png" border="0" alt="Plurk"/></a>
                            <a href="https://api.addthis.com/oexchange/0.8/forward/twitter/offer?url={!!rawurldecode(Request::url())!!}&pubid=ra-563b331d9e76fc9f&ct=1&title={{rawurldecode($page['sub_title'])}}&pco=tbxnj-1.0" target="_blank"><img src="https://cache.addthiscdn.com/icons/v2/thumbs/16x16/twitter.png" border="0" alt="Twitter"/></a>

                        </div>
                        {!! $article->content !!}
                    </div>
                </article>
                <aside class="social-icons clearfix">
                    <h3>Share on </h3>
                    <a href="#"><i class="fa fa-facebook"></i></a> <a href="#"><i class="fa fa-twitter"></i></a> <a href="#"><i class="fa fa-google"></i></a>
                </aside>

                <aside class="comments" id="comments">
                    <hr>

                    <h2><i class="fa fa-comments"></i> 6 Comments</h2>

                    <article class="comment">
                        <header class="clearfix">
                            <img src="{{asset('images/128.png')}}" alt="A Smart Guy" class="avatar">
                            <div class="meta">
                                <h3><a href="#">John Doe</a></h3>
                                    <span class="date">
                                        24 August 2015
                                    </span>
                                    <span class="separator">
                                        -
                                    </span>

                                <a href="#create-comment" class="reply-link">Reply</a>
                            </div>
                        </header>
                        <div class="body">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere sit perspiciatis debitis, vel ducimus praesentium expedita, assumenda ipsum cum corrupti dolorum modi. Rem ipsam similique sapiente obcaecati tenetur beatae voluptatibus.
                        </div>
                    </article>

                    <article class="comment reply">
                        <header class="clearfix">
                            <img src="{{asset('images/128.png')}}" alt="A Smart Guy" class="avatar">
                            <div class="meta">
                                <h3><a href="#">John Doe</a></h3>
                                    <span class="date">
                                        24 August 2015
                                    </span>
                                    <span class="separator">
                                        -
                                    </span>

                                <a href="#create-comment" class="reply-link">Reply</a>
                            </div>
                        </header>
                        <div class="body">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere sit perspiciatis debitis, vel ducimus praesentium expedita, assumenda ipsum cum corrupti dolorum modi. Rem ipsam similique sapiente obcaecati tenetur beatae voluptatibus.
                        </div>
                    </article>

                    <article class="comment ">
                        <header class="clearfix">
                            <img src="{{asset('images/128.png')}}" alt="A Smart Guy" class="avatar">
                            <div class="meta">
                                <h3><a href="#">John Doe</a></h3>
                                    <span class="date">
                                        24 August 2015
                                    </span>
                                    <span class="separator">
                                        -
                                    </span>

                                <a href="#create-comment" class="reply-link">Reply</a>
                            </div>
                        </header>
                        <div class="body">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere sit perspiciatis debitis, vel ducimus praesentium expedita, assumenda ipsum cum corrupti dolorum modi. Rem ipsam similique sapiente obcaecati tenetur beatae voluptatibus.
                        </div>
                    </article>

                    <article class="comment">
                        <header class="clearfix">
                            <img src="{{asset('images/128.png')}}" alt="A Smart Guy" class="avatar">
                            <div class="meta">
                                <h3><a href="#">John Doe</a></h3>
                                    <span class="date">
                                        24 August 2015
                                    </span>
                                    <span class="separator">
                                        -
                                    </span>

                                <a href="#create-comment" class="reply-link">Reply</a>
                            </div>
                        </header>
                        <div class="body">
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facere sit perspiciatis debitis, vel ducimus praesentium expedita, assumenda ipsum cum corrupti dolorum modi. Rem ipsam similique sapiente obcaecati tenetur beatae voluptatibus.
                        </div>
                    </article>
                </aside>

                <aside class="create-comment" id="create-comment">
                    <hr>

                    <h2><i class="fa fa-pencil"></i> Add Comment</h2>

                    <form action="#" method="get" accept-charset="utf-8">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="name" id="comment-name" placeholder="Your Name" class="form-control input-lg">
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="email" id="comment-email" placeholder="Email" class="form-control input-lg">
                            </div>
                        </div>

                        <input type="url" name="name" id="comment-url" placeholder="Website" class="form-control input-lg">

                        <textarea rows="10" name="message" id="comment-body" placeholder="Your Message" class="form-control input-lg"></textarea>

                        <div class="buttons clearfix">
                            <button type="submit" class="btn btn-xlarge btn-clean-one">Submit</button>
                        </div>
                    </form>
                </aside>
                </div>


@endsection