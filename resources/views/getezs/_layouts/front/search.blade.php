<div class="alert  alert-dismissible  center-block   text-center"   role="alert">
    {!!Form::open(['route'=>['articles.search'],'method'=>'post','id' => 'article_search','class'=>"form-horizontal article_search"])!!}

    <div class="input-group input-group-sm">
        {!!Form::text('q',(Input::old('q')) ? Input::old('q') : '',array('class' => 'form-control','id'=>'q','placeholder'=>'搜尋文章','required'))!!}


           <span class="input-group-btn">
                      <button class="btn btn-info btn-flat" type="submit">Go!</button>
           </span>
    </div>
    {!! Form::close() !!}
</div>

