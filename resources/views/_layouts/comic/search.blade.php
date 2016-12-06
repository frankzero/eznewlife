<div class="alert  alert-dismissible  center-block   text-center"   role="alert"  itemscope itemtype="http://schema.org/WebSite">
    {!!Form::open(['route'=>['comics.search'],'method'=>'get','id' => 'dark_search','class'=>"form-horizontal dark_search"])!!}

    <div class="input-group input-group-sm">
        {!!Form::text('q',(Input::old('q')) ? Input::old('q') : '',array('class' => 'form-control','id'=>'q','placeholder'=>'搜尋動漫','required'))!!}


           <span class="input-group-btn">
                      <button class="btn btn-default bg-maroon-gradient btn-flat" type="submit">Go!</button>
           </span>
    </div>
    {!! Form::close() !!}
</div>

