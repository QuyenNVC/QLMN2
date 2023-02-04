@extends('admin.layout.app')

@section('content')
   <div id="app">
       <bao-cao-tam-ung-nhan-vien />
   </div>
@endsection

@section('js')
    <script type="application/javascript" src="{{ asset('js/app.js') }}"></script>
@endsection