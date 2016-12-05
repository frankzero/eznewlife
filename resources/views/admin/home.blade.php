@extends('_layouts/home')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">后台首xx页</div>

                    <div class="panel-body">

                        <a href="{{ URL('admin/articles/create') }}" class="btn btn-lg btn-primary">新增文章</a>

                        @foreach ($articles as $article)
                            <hr>
                            <div class="article">
                                <h4>{{ $article->title }}</h4>
                                <div class="content">
                                    <p>
                                        {{ $article->body }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ URL('admin/articles/'.$article->id.'/edit') }}" class="btn btn-success">编辑</a>
                            {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['articles.destroy', $article->id]
                                    ]) !!}
                            {!! Form::submit('刪除', ['class' => 'btn btn-danger']) !!}
                            {!! Form::close() !!}

                            <form action="{{ URL('admin/articles/'.$article->id) }}" method="POST" style="display: inline;">
                                <meta name="csrf-token" content="{{ csrf_token() }}" />
                                <input name="_method" type="hidden" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="submit" class="btn btn-danger">删除</button>
                            </form>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
