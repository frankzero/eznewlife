@extends('_layouts/enl')
@section('content')
<style>
#cse-search-results iframe{
    width:100%;
}
</style>

    <div class="col-md-8 blog-main">
    
    
        <script type="text/javascript" src="http://www.google.com/cse/query_renderer.js"></script>
    
        <div style="background-color:#fff;padding: 0 5px 5px 5px;margin-bottom: 20px;">

            <h3 style="margin:0;">熱門搜尋</h3>
            <div id="queries"></div>
        </div>
        <script src="http://www.google.com/cse/api/partner-pub-6621952572807243/cse/7523242174/queries/js?oe=UTF-8&amp;callback=(new+PopularQueryRenderer(document.getElementById(%22queries%22))).render"></script>


       <div id="cse-search-results"></div>
        <script type="text/javascript">
          var googleSearchIframeName = "cse-search-results";
          var googleSearchFormName = "cse-search-box";
          var googleSearchFrameWidth = 800;
          var googleSearchDomain = "www.google.com.tw";
          var googleSearchPath = "/cse";
        </script>
        <script type="text/javascript" src="http://www.google.com/afsonline/show_afs_search.js"></script>

    </div>


@endsection
@push('scripts')
<script>
    function hiliter(term, src_str) {
//cdnjs.cloudflare.com/ajax/libs/highlight.js/8.4/highlight.min.js
        // var rgxp = new RegExp(word, 'gi');
        //var repl = '<code>' + word + '</code>';
        term = term.replace(/(\s+)/, "(<[^>]+>)*$1(<[^>]+>)*");
        var pattern = new RegExp("(" + term + ")", "gi");

        src_str = src_str.replace(pattern, "<mark>$1</mark>");
        src_str = src_str.replace(/(<mark>[^<>]*)((<[^>]+>)+)([^<>]*<\/mark>)/, "$1</mark>$2<mark>$4");

        //element = element.replace(rgxp, repl);
        return src_str;
    }
</script>
@endpush