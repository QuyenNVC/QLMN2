@extends('admin.layout.app')

@section('content')
   <div id="app">
    @if (Auth::user()->id_coso)
    <cost-annual-fee :id_coso="{{Auth::user()->id_coso}}" />
    @else
    <cost-annual-fee />
    @endif
   </div>
@endsection

@section('js')
    <script type="application/javascript" src="{{ asset('js/app.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
@endsection