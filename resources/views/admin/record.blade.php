@extends('admin.layout.app')

@section('content')
   <div id="app">
       @if(isset($id))
       <record :id_enrollment="{{ $id }}" />
       @else
       <record />
       @endif
   </div>
@endsection

@section('js')
    <script type="application/javascript" src="{{ asset('js/app.js') }}"></script>
@endsection