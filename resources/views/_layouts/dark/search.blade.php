<div class="alert  alert-dismissible  center-block   text-center"   role="alert"  itemscope itemtype="http://schema.org/WebSite">
    <link itemprop="url" href="{{https(route('darks.index'))}}"/>
    {!!Form::open(['url'=>https(route("darks.search")),"itemprop"=>"potentialAction","itemscope", "itemtype"=>"http://schema.org/SearchAction",'method'=>'get','id' => 'dark_search','class'=>"form-horizontal dark_search"])!!}
    <meta itemprop="target" content="{{https(route('darks.search'))}}?q={q}"/>
    <div class="input-group input-group-sm">
        {!!Form::text('q',(Input::old('q')) ? Input::old('q') : '',array('class' => 'form-control','id'=>'q','placeholder'=>'搜尋文章','required','itemprop'=>"query-input"))!!}
           <span class="input-group-btn">
               <button class="btn btn-default bg-purple-active btn-flat" type="submit">Go!</button>
           </span>
    </div>
    {!! Form::close() !!}
</div>

