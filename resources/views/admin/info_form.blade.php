@extends('admin.layout.app')

@section('content')
   <div id="app">
    @if(isset($id))
       <info :id_coso="{{ $id }}" />
       @else
       <info />
       @endif 
   </div>
@endsection

@section('js')
    <script type="application/javascript" src="{{ asset('js/app.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
@endsection