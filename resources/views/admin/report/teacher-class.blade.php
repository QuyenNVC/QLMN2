@extends('admin.layout.app')

@section('content')
   <div id="app">
       <teacher-class />
   </div>
@endsection

@section('js')
    <script type="application/javascript" src="{{ asset('js/app.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
@endsection