@extends('admin.layout.app')

@section('content')
   <div id="app">
       <phu-cap />
   </div>
@endsection

@section('js')
    <script type="application/javascript" src="{{ asset('js/app.js') }}"></script>
@endsection