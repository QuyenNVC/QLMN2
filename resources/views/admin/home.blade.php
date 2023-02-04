@extends('admin.layout.app')

@section('content')
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title">Dashboard</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  Library
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Container fluid  -->
    <!-- ============================================================== -->
    <div class="container-fluid">
      <!-- ============================================================== -->
      <!-- Sales Cards  -->
      <!-- ============================================================== -->
      <div class="row">
        @foreach ($data as $item)
        @can($item['permission'])
        <div class="col-md-6 col-lg-{{$item['col-lg']}} col-xlg-3">
          <a href="{{ $item['link'] }}">
            <div class="card card-hover">
              <div class="box {{$item['bg-color']}} text-center">
                <h1 class="font-light text-white">
                  <i class="mdi mdi-{{$item['icon']}}"></i>
                </h1>
                <h6 class="text-white">{{$item['title']}}</h6>
              </div>
            </div>
          </a>
        </div>
        @endcan
        @endforeach
      </div>
      <!-- ============================================================== -->
      <!-- Sales chart -->
      <!-- ============================================================== -->
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="d-md-flex align-items-center">
                <div>
                  <h4 class="card-title">Phân tích số liệu</h4>
                  <h5 class="card-subtitle">Tổng quan về tháng gần nhất</h5>
                </div>
              </div>
              <div class="row">
                <!-- column -->
                <div class="col-lg-9">
                  {{-- <div class="flot-chart">
                    <div
                      class="flot-chart-content"
                      id="flot-line-chart"
                    ></div>
                  </div> --}}
                  <img src="{{asset('img/chart.jpg')}}" alt="" srcset="">
                </div>
                <div class="col-lg-3">
                  <div class="row">
                    @foreach($data_info as $item)
                    <div class="col-6 mb-3">
                      <div class="bg-dark p-10 text-white text-center">
                        <i class="mdi mdi-{{$item['icon']}} fs-3 mb-1 font-16"></i>
                        <h5 class="mb-0 mt-1">{{$item['data']}}</h5>
                        <small class="font-light">{{$item['title']}}</small>
                      </div>
                    </div>
                    @endforeach
                  </div>
                </div>
                <!-- column -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- ============================================================== -->
      <!-- Sales chart -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Recent comment and chats -->
      <!-- ============================================================== -->
      <!-- ============================================================== -->
      <!-- Recent comment and chats -->
      <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- footer -->
    <!-- ============================================================== -->
    <footer class="footer text-center">
      All Rights Reserved by Matrix-admin. Designed and Developed by
      <a href="https://www.smartidads.com/">SmartID</a>.
    </footer>
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
  </div>

@endsection