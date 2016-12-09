<div class="alert  alert-dismissible  center-block   text-center"   role="alert"  itemscope itemtype="https://schema.org/WebSite">
    <link itemprop="url" href="{{https(route('darks.index'))}}"/>
    <form method="GET" action="/search" accept-charset="UTF-8" itemprop="potentialAction" itemscope="itemscope" itemtype="https://schema.org/SearchAction" id="dark_search" class="form-horizontal dark_search">

    <meta itemprop="target" content="{{https(route('darks.search'))}}?q={q}"/>
    <div class="input-group input-group-sm">
        {!!Form::text('q',(Input::old('q')) ? Input::old('q') : '',array('class' => 'form-control','id'=>'q','placeholder'=>'搜尋文章','required','itemprop'=>"query-input"))!!}
           <span class="input-group-btn">
               <button class="btn btn-default bg-purple-active btn-flat" type="submit">Go!</button>
           </span>
    </div>
    {!! Form::close() !!}
</div>

