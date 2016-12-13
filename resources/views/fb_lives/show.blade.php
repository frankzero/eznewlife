<!-- CONFIG - by SocialWall.me
1/ Update your Background (:16)
2/ Update your Logo (:87)
3/ Update your Title (:88)
4/ Update your Access Token (:105)
5/ Update your Post ID (:106) -->
<!-- Original source code: https://gist.github.com/anonymous/7073ea6c601f28aa65e5a077ef875526 -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Facebook Reactions </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" >

    <style>
        html {
            box-sizing: border-box;
            width: 100%;
            height: 100%;
            margin:0;
            padding:0;
            /*background: url("background.png") no-repeat center fixed;*/ /* YOUR BACKGROUND URL HERE */
            -webkit-background-size: cover;
            background-size: cover;
        }
        *, *:before, *:after {
            box-sizing: inherit;
        }
        body{
            margin: 0;
            font-family: 'Source Sans Pro', sans-serif, "微軟正黑體", "微软雅黑", "Helvetica Neue", Helvetica, Arial, "メイリオ", "맑은 고딕";
            color: #0f0302;
            width: 100%;
            height: 100%;
            /* background: rgba(0,0,0,0.3)*/
        }
        .body{font-family: 'Source Sans Pro', sans-serif, "微軟正黑體", "微软雅黑", "Helvetica Neue", Helvetica, Arial, "メイリオ", "맑은 고딕";}
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .h1,
        .h2,
        .h3,
        .h4,
        .h5,
        .h6
        ,body {

            font-family: 'Source Sans Pro', sans-serif, "微軟正黑體", "微软雅黑", "Helvetica Neue", Helvetica, Arial, "メイリオ", "맑은 고딕";
        }
        header {
            text-align: center;
        }
        header .logo {
            position: relative;
            margin:100px auto 20px;
            width:250px;
        }

        h1 {
            text-align: center;
            width:100%;
            font-size: 50px;
            padding: 30px 0;
            margin:0;
            color:#fff;
        }
        @media (max-width: 767px) {
            h1 {
                text-align: center;
                width:100%;
                font-size:80px;
                padding: 30px 0;
                margin:0;
                color:#fff;
            }
        }
        #wrap{
            position: relative;
            margin: 0px auto;
            width: 1280px;
            height: 720px;
        }
        .tc {
            text-align: center;
            font-size: 3rem;
            font-weight: bold;
        }


        .wf {
           /* width: 12%;*/
        }

        .wf img.illu {
            width: 90%;
            display: block;
            margin: 20px auto;
            position: relative;
        }

        .wf img.emoji {
            width: 60px;
            display: inline-block;
            margin-right:5px;
        }

        .wf {
           /* width: 13%;*/
            float: left;
            margin: 10px 1%;
            border: solid 1px #eee;
            background: #fff;
            border-radius: 8px;
            position: relative;
            text-align: center;
        }

    </style>
</head>
<body>

<div>
    <h1 style="color:#ffec7e;">{!! $fb_lives->title!!}</h1>
</div>

</div>
<p>


<!-- REACTIONS -->
<div class="row center-block" >
    @foreach ($answers as $k)
        <div class="{{$class}}">
            <div class=" tc wf btn btn-default btn-block  {{$k}}"><img class="emoji" src="/images/fbemojis/{{$k}}.gif"><span class="counter"></span></div>
        </div>
    @endforeach
</div>
<script src="/js/jquery.min.js"></script>
<script src="/js/lodash.min.js"></script>
<script>
    "use strict";
    //var access_token = 'EAACEdEose0cBACtKE7oKfLoXkkkvvkbFFoKpjyvM0i6b3SrMZC3nfP0ZBdpnOag2DGxNIyMQLjU9FyQevV6Cj1ba3OTCeypwJIFNqMtSmNr6QEG36BdSRfGXuguwZBxGuQUuNsgZCoYgP57HVuo1i2Qk9vrOZBKi0cR71gU1601VqyzNCY9ML'; // PASTE HERE YOUR FACEBOOK ACCESS TOKEN
    /*開發者user token 會員 約一個小時會過期喔*/
    var access_token='{!!$fb_lives->fb_access_token!!}';
    //  var access_token='EAACEdEose0cBACY2zyLuAevCKrJs6tuSlztrLhKkbOYNZAsU3zqhqvZB8mMpuf1lziOzXtZBDSVdvZCDs5Ygsj3WJVGSKyAA9ZCRYRzeAZChlcU9GYgA3KIFLDwf9NomepWwIdTXnOOyAz399lfi81b9EfL3DvvGZBCJa5fvpnIPgZDZD';
    /*發文的文章的postid 1161536443883444*/
    var postID = '{!!$fb_lives->fb_video_id!!}'; // PASTE HERE YOUR POST ID
    var refreshTime = 2; // Refresh time in seconds
    var defaultCount = 0; // Default count to start with
    /*
     rtmp://rtmp-api.facebook.com:80/rtmp/
     1159042324132856?ds=1&s_l=1&a=AaaMnMrTFq4auTAU
     */

    var reactions = [{!!$reactions!!}].map(function (e) {
        var code = 'reactions_' + e.toLowerCase();
        return 'reactions.type(' + e + ').limit(0).summary(total_count).as(' + code + ')'
    }).join(',');

            <?php $i=0;?>
            @foreach ($answers as $k)

    var	v{{++$i}} = $('.{{$k}} .counter');

    @endforeach

    function refreshCounts() {
        var url = 'https://graph.facebook.com/v2.8/?ids=' + postID + '&fields=' + reactions + '&access_token=' + access_token;
        <?php $i=0;?>
            $.getJSON(url, function(res){
            @foreach ($answers as $k)

            v{{++$i}}.text(defaultCount + res[postID].reactions_{{$k}}.summary.total_count);
            @endforeach
        });
    }

    $(document).ready(function(){
        setInterval(refreshCounts, refreshTime * 1000);
        refreshCounts();
    });
</script>

</body>

</html>