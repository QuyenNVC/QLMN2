@extends('admin.layout.app')

@section('content')
   <div id="app">
       <chi-luong-nhan-vien title="Báo cáo chi lương nhân viên" phong-ban-user="{{ session('idPB') }}">
   </div>
@endsection

@section('js')
    <script type="application/javascript" src="{{ asset('js/app.js') }}"></script>
@endsection