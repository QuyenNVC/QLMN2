@extends('admin.layout.app')

@section('content')
    <div id="app">
       @if(isset($id))
       <student :id_student="{{ $id }}" />
       @else
       <student />
       @endif
    </div>
    {{-- <div id="app">
        <student-list />
    </div> --}}
@endsection

@section('js')
    <script type="application/javascript" src="{{ asset('js/app.js') }}"></script>
@endsection