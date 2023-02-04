@extends('admin.layout.app')

@section('content')
   <div id="app">
       <hinh-thuc-cham-cong />
   </div>
@endsection

@section('js')
    <script type="application/javascript" src="{{ asset('js/app.js') }}"></script>
@endsection