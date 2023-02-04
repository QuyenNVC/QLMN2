@extends('admin.layout.app')

@section('content')
   <div id="app">
        <student-list></student-list>
        {{-- <main-modal></main-modal> --}}
   </div>
@endsection

@section('js')
    <script type="application/javascript" src="{{ asset('js/app.js') }}"></script>
@endsection