@extends('admin.layout.app')

@section('content')
   <div id="app">
       <cham-cong phong-ban-user="{{ session('idPB') }}"/>
   </div>
@endsection

@section('js')
    <script type="application/javascript" src="{{ asset('js/app.js') }}"></script>
@endsection