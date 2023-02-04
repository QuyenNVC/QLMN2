@extends('admin.layout.app')

@section('content')
   <div id="app">
       <bao-cao-ngay-cong-nhan-vien />
   </div>
@endsection

@section('js')
    <script type="application/javascript" src="{{ asset('js/app.js') }}"></script>
@endsection