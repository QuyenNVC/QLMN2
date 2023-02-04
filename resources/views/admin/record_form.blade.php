@extends('admin.layout.app')

@section('content')
   <div id="app">
       @if(isset($id))
       <record-form :mahs="{{ $id }}" />
       @else
       <record-form />
       @endif
   </div>
@endsection

@section('js')
    <script type="application/javascript" src="{{ asset('js/app.js') }}"></script>
@endsection