@extends('admin.layout.app')

@section('content')
   <div id="app">
       <nhat-ky-suc-khoe />
   </div>
@endsection

@section('js')
    <script type="application/javascript" src="{{ asset('js/app.js') }}"></script>
@endsection