@extends('_layouts/admin')
@section('content')
    <script language="javascript" type="text/javascript">
        function resizeIframe(obj) {
            console.log(obj.contentWindow.document.body.scrollHeight);
            obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
        }
    </script>
    <iframe src="{{route('admin.logs.content')}}" frameborder="0" scrolling="yes" width="100%" onload="resizeIframe(this)" />
@endsection
@push('scripts')

@endpush