<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords"
        content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Matrix lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Matrix admin lite design, Matrix admin lite dashboard bootstrap 5 dashboard template">
    <meta name="description"
        content="Matrix Admin Lite Free Version is powerful and clean admin dashboard template, inpired from Bootstrap Framework">
    <meta name="robots" content="noindex,nofollow">
    <title>Phần mềm quản lý trường mầm non</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    <!-- Custom CSS -->
    <link href="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/extra-libs/calendar/calendar.css') }}" rel="stylesheet" />
    <!-- bootstrap -->
    <link href="{{ asset('assets/libs/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/6.5.95/css/materialdesignicons.min.css" integrity="sha512-Zw6ER2h5+Zjtrej6afEKgS8G5kehmDAHYp9M2xf38MPmpUWX39VrYmdGtCrDQbdLQrTnBVT8/gcNhgS4XPgvEg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Serif&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('dist/css/style.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<style>
    i.el-alert__closebtn.el-icon-close {
        display: none;
    }

    .wrap-table .el-table .el-input__inner {
        max-width: 100%;
    }

    .el-table .cell {
        word-break: break-word !important;
    }

    .sidebar-link>span {
        white-space: pre-line;
    }

    @media screen and (max-width: 768px) {
        /* .wrap-filter {
            flex-direction: column;
        } */

        .wrap-filter .form-group-input {
            justify-content: center !important;
        }

        .wrap-filter .form-group-input:nth-child(2) {
            flex-basis: 100%;
            margin-top: 14px;
        }

        .wrap-filter .form-group-input:nth-child(2) .chon-phong-ban {
            flex: 2;
        }
    }
</style>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full"
        data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin5">
                    
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <a class="navbar-brand" href="/dashboard" style="display: flex; align-items: center">
                        <!-- Logo icon -->
                        <b class="logo-icon ps-2">
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <img src="{{ asset('assets/images/logo-icon.png') }}" alt="homepage" class="light-logo" />

                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span class="logo-text" style="font-weight: bold; margin-top: 2px">
                            <!-- dark Logo text -->
                            <!-- <img src="{{ asset('assets/images/logo-text.png') }}" alt="homepage" class="light-logo" /> -->
                            QUẢN TRỊ
                        </span>
                        <!-- Logo icon -->
                        <!-- <b class="logo-icon"> -->
                        <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                        <!-- Dark Logo icon -->
                        <!-- <img src="{{ asset('assets/images/logo-text.png') }}" alt="homepage" class="light-logo" /> -->

                        <!-- </b> -->
                        <!--End Logo icon -->
                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- Toggle which is visible on mobile only -->
                    <!-- ============================================================== -->
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                            class="ti-menu ti-close"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-start me-auto">
                        <li class="nav-item d-none d-lg-block"><a
                                class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)"
                                data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a></li>
                        <!-- ============================================================== -->
                        <!-- create new -->
                        <!-- ============================================================== -->
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="d-none d-md-block">Create New <i class="fa fa-angle-down"></i></span>
                                <span class="d-block d-md-none"><i class="fa fa-plus"></i></span>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li> -->
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <!-- <li class="nav-item search-box"> <a class="nav-link waves-effect waves-dark"
                                href="javascript:void(0)"><i class="ti-search"></i></a>
                            <form class="app-search position-absolute">
                                <input type="text" class="form-control" placeholder="Search &amp; enter"> <a
                                    class="srh-btn"><i class="ti-close"></i></a>
                            </form>
                        </li> -->
                    </ul>
                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav float-end">
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-bell font-24"></i>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li> -->
                        <!-- ============================================================== -->
                        <!-- End Comment -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                        <!-- <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="#" id="2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                 <i class="font-24 mdi mdi-comment-processing"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end mailbox animated bounceInDown" aria-labelledby="2">
                                <ul class="list-style-none">
                                    <li>
                                        <div class=""> -->
                                            <!-- Message -->
                                            <!-- <a href="javascript:void(0)" class="link border-top">
                                                <div class="d-flex no-block align-items-center p-10">
                                                    <span class="btn btn-success btn-circle"><i
                                                            class="ti-calendar"></i></span>
                                                    <div class="ms-2">
                                                        <h5 class="mb-0">Event today</h5>
                                                        <span class="mail-desc">Just a reminder that event</span>
                                                    </div>
                                                </div>
                                            </a> -->
                                            <!-- Message -->
                                            <!-- <a href="javascript:void(0)" class="link border-top">
                                                <div class="d-flex no-block align-items-center p-10">
                                                    <span class="btn btn-info btn-circle"><i
                                                            class="ti-settings"></i></span>
                                                    <div class="ms-2">
                                                        <h5 class="mb-0">Settings</h5>
                                                        <span class="mail-desc">You can customize this template</span>
                                                    </div>
                                                </div>
                                            </a> -->
                                            <!-- Message -->
                                            <!-- <a href="javascript:void(0)" class="link border-top">
                                                <div class="d-flex no-block align-items-center p-10">
                                                    <span class="btn btn-primary btn-circle"><i
                                                            class="ti-user"></i></span>
                                                    <div class="ms-2">
                                                        <h5 class="mb-0">Pavan kumar</h5>
                                                        <span class="mail-desc">Just see the my admin!</span>
                                                    </div>
                                                </div>
                                            </a> -->
                                            <!-- Message -->
                                            <!-- <a href="javascript:void(0)" class="link border-top">
                                                <div class="d-flex no-block align-items-center p-10">
                                                    <span class="btn btn-danger btn-circle"><i
                                                            class="fa fa-link"></i></span>
                                                    <div class="ms-2">
                                                        <h5 class="mb-0">Luanch Admin</h5>
                                                        <span class="mail-desc">Just see the my new admin!</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </ul>
                        </li> -->
                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->

                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="../../assets/images/users/1.jpg" alt="user" class="rounded-circle" width="31">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end user-dd animated" aria-labelledby="navbarDropdown">
                                <!-- <a class="dropdown-item" href="javascript:void(0)"><i class="ti-user me-1 ms-1"></i>
                                    My Profile</a>
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-wallet me-1 ms-1"></i>
                                    My Balance</a>
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-email me-1 ms-1"></i>
                                    Inbox</a> -->
                                <!-- <div class="dropdown-divider"></div> -->
                                <a class="dropdown-item" href="{{ route('nguoidung.doi-mat-khau') }}"><i
                                        class="ti-settings me-1 ms-1"></i> Đổi mật khẩu</a>
                                <!-- <div class="dropdown-divider"></div> -->
                                <a class="dropdown-item" href="{{ route('login') }}"><i
                                        class="fa fa-power-off me-1 ms-1"></i> Đăng xuất </a>
                                <!-- <div class="dropdown-divider"></div> -->
                                <!-- <div class="ps-4 p-10"><a href="javascript:void(0)"
                                        class="btn btn-sm btn-success btn-rounded text-white">View Profile</a></div> -->
                            </ul>
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar" data-sidebarbg="skin5">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav" class="pt-4">
                        @canany(['superAdmin', 'RecordController@quanly'])
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link has-arrow"
                                href="{{ route('enrollment.quanly') }}" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span
                                    class="hide-menu">Quản lý tuyển sinh</span></a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                @can('superAdmin')
                                <li class="sidebar-item"><a href="{{ route('enrollment.quanly') }}" class="sidebar-link"><i
                                            class="mdi mdi-format-list-bulleted"></i><span class="hide-menu"> Kỳ tuyển sinh </span></a>
                                </li>
                                @endcan
                                @can('RecordController@quanly')
                                <li class="sidebar-item"><a href="{{ route('record.quanly') }}" class="sidebar-link"><i
                                            class="mdi mdi-format-list-bulleted"></i><span class="hide-menu"> Hồ sơ tuyển sinh </span></a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan
                        @can('ClassController@index')
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{ route('class.quanly') }}" aria-expanded="false"><i class="mdi mdi-presentation-play"></i><span
                                    class="hide-menu">Quản lý lớp học</span></a></li>
                        @endcan
                        <!-- @if (session('user') == "admin")
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{ route('nguoidung.quanly') }}" aria-expanded="false"><i class="mdi mdi-chart-bar"></i><span
                                    class="hide-menu">Người dùng</span></a></li>
                        @endif -->
                        <!-- <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{ route('record.quanly') }}" aria-expanded="false"><i class="mdi mdi-file-document"></i><span
                                    class="hide-menu">Hồ sơ nhập học</span></a></li> -->
                        @canany(['StudentController@index', 'DiemDanhController@index', 'NhatKySucKhoeController@index','TheoDoiSucKhoeController@index','StudentFeeController@index'])
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link has-arrow"
                                href="{{ route('student.quanly') }}" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span
                                    class="hide-menu">Quản lý học sinh</span></a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                @can('StudentController@index')
                                <li class="sidebar-item"><a href="{{ route('student.quanly') }}" class="sidebar-link"><i
                                            class="mdi mdi-account-multiple"></i><span class="hide-menu"> Học sinh </span></a>
                                </li>
                                @endcan
                                @can('DiemDanhController@index')
                                <li class="sidebar-item"><a href="{{ route('diemdanh.quanly') }}" class="sidebar-link"><i
                                            class="mdi mdi-check-bold"></i><span class="hide-menu"> Điểm danh học sinh </span></a>
                                </li>
                                @endcan
                                @can('NhatKySucKhoeController@index')
                                <li class="sidebar-item"><a href="{{ route('nhatkysuckhoe.quanly') }}" class="sidebar-link"><i
                                            class="mdi mdi-table-edit"></i><span class="hide-menu"> Nhật ký sức khỏe </span></a>
                                </li>
                                @endcan
                                @can('TheoDoiSucKhoeController@index')
                                <li class="sidebar-item"><a href="{{ route('theodoisuckhoe.quanly')}}" class="sidebar-link"><i
                                            class="mdi mdi-clipboard-check-outline"></i><span class="hide-menu"> Theo dõi phát triển thể chất - nhận thức </span></a>
                                </li>
                                @endcan
                                @can('StudentFeeController@index')
                                <li class="sidebar-item"><a href="{{ route('thanhtoanhocphi.quanly') }}" class="sidebar-link"><i
                                            class="mdi mdi-cash-multiple"></i><span class="hide-menu"> Thanh toán học phí</span></a>
                                </li>
                                @endcan
                                <li class="sidebar-item"><a href="#" class="sidebar-link"><i
                                            class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Báo xuất ăn </span></a>
                                </li>
                                <li class="sidebar-item"><a href="#" class="sidebar-link"><i
                                            class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Dịch vụ đưa đón học sinh </span></a>
                                </li>
                                
                                <li class="sidebar-item"><a href="#" class="sidebar-link"><i
                                            class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Điều phối giáo viên </span></a>
                                </li>
                            </ul>
                        </li>
                        @endcanany
                        @can('PhongBanController@quanLy')
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                            href="{{ route('phongban.quanly') }}" aria-expanded="false"><i class="mdi mdi-chart-bubble"></i><span
                                class="hide-menu">Quản lý phòng ban</span></a></li>
                        @endcan
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark has-arrow"
                                href="{{ route('nhanvien.quanly') }}" aria-expanded="false"><i class="mdi mdi-account-key"></i><span
                                    class="hide-menu">Quản lý nhân viên </span></a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                @can(['NhanVienController@create'])
                                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark"
                                href="{{ route('nhanvien.quanly') }}" aria-expanded="false"><i class="mdi mdi-account-key"></i><span
                                    class="hide-menu">Nhân viên </span></a>
                                @endcan
                                @can('PhuCapController@getPhuCapNhanVien')
                                    <li class="sidebar-item"><a href="{{ route('phucap.phuCapNhanVien') }}" class="sidebar-link"><i class="fa-solid fa-money-bills"></i><span class="hide-menu"> Phụ cấp nhân viên
                                        </span></a></li>
                                @endcan
                                @can('TangGiamLuongController@getTangGiamLuongNhanVien')
                                    <li class="sidebar-item"><a href="{{ route('tanggiamluong.tangGiamLuongNhanVien') }}" class="sidebar-link"><i class="fa-solid fa-money-bill-trend-up"></i><span class="hide-menu"> Tăng/giảm lương nhân viên
                                            </span></a></li>
                                @endcan
                                @can('ChamCongController@getForm')
                                <li class="sidebar-item"><a href="{{ route('chamcong.quanly') }}" class="sidebar-link"><i
                                            class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Bảng chấm công
                                        </span></a></li>
                                @endcan
                                @can('UngLuongController@getUngLuongNhanVien')
                                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{ route('chiluong.chiLuongNhanVien') }}" aria-expanded="false"><i class="mdi mdi-pencil"></i><span
                                    class="hide-menu">Chi lương</span></a></li>
                                @endcan
                                @can('UngLuongController@getUngLuongNhanVien')
                                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{ route('ungluong.ungLuongNhanVien') }}" aria-expanded="false"><i class="mdi mdi-move-resize-variant"></i><span
                                    class="hide-menu">Ứng lương</span></a></li>
                                @endcan
                                @canany(['BaoCaoController@getBaoCaoChiLuong', 'BaoCaoController@getBaoCaoNgayCong', 'BaoCaoController@getBaoCaoNghiViec', 'BaoCaoController@getBaoCaoPhuCap'])
                                    <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark"
                                href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-receipt"></i><span
                                    class="hide-menu">Báo cáo </span></a>
                                    <ul aria-expanded="false" class="collapse  second-level">
                                        @can('BaoCaoController@getBaoCaoChiLuong')
                                        <li class="sidebar-item"><a href="{{ route('admin.baoCaoChiLuong') }}" class="sidebar-link"><i
                                                    class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Báo cáo chi lương
                                                </span></a></li>
                                        @endcan
                                        @can('BaoCaoController@getBaoCaoNgayCong')
                                        <li class="sidebar-item"><a href="{{ route('admin.baoCaoNgayCong') }}" class="sidebar-link"><i
                                                    class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Báo cáo ngày công
                                                </span></a></li>
                                        @endcan
                                        @can('BaoCaoController@getBaoCaoNghiViec')
                                        <li class="sidebar-item"><a href="{{ route('admin.baoCaoNghiViec') }}" class="sidebar-link"><i
                                                    class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Báo cáo nghỉ việc
                                                </span></a></li>
                                        @endcan
                                        @can('BaoCaoController@getBaoCaoPhuCap')
                                        <li class="sidebar-item"><a href="{{ route('admin.baoCaoPhuCap') }}" class="sidebar-link"><i
                                                    class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Báo cáo phụ cấp
                                                </span></a></li>
                                        @endcan
                                        @can('BaoCaoController@getBaoCaoTamUng')
                                        <li class="sidebar-item"><a href="{{ route('admin.baoCaoTamUng') }}" class="sidebar-link"><i
                                                    class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Báo cáo tạm ứng
                                                </span></a></li>
                                        @endcan
                                    </ul>
                                </li>
                                @endcanany
                            </ul>
                        </li>
                        @can('ThucDonController@index')
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="{{ route('thucdon.quanly') }}" aria-expanded="false"><i class="mdi mdi-move-resize-variant"></i><span
                                    class="hide-menu">Quản lý thực đơn nhà trẻ</span></a></li>
                        @endcan
                        @canany(['QuanLyThuController@index','QuanLyChiController@index'])
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link has-arrow"
                                href="#" aria-expanded="false"><i class="mdi mdi-cash-usd"></i><span
                                    class="hide-menu">Quản lý thu chi</span></a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                @can('QuanLyThuController@index')
                                <li class="sidebar-item"><a href="{{ route('quanlythu.index') }}" class="sidebar-link"><i class="fa-solid fa-file-invoice-dollar"></i><span class="hide-menu"> Quản lý thu </span></a>
                                </li>
                                @endcan
                                @can('QuanLyChiController@index')
                                <li class="sidebar-item"><a href="{{ route('quanlychi.index') }}" class="sidebar-link"><i class="fa-solid fa-hand-holding-dollar"></i><span class="hide-menu"> Quản lý chi </span></a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="#" aria-expanded="false"><i class="mdi mdi-hospital-building mdi mdi-hospital-building"></i><span
                                    class="hide-menu">Quản lý cơ sở vật chất</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="#" aria-expanded="false"><i class="mdi mdi-notification-clear-all"></i><span
                                    class="hide-menu">Giáo án giảng dạy</span></a></li>
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                href="#" aria-expanded="false"><i class="mdi mdi-note-multiple"></i><span
                                    class="hide-menu">Sổ liên lạc điện tử</span></a></li>
                        @canany([
                            'ReportController@employeeResume', 
                            'ReportController@teacherClass', 
                            'ReportController@timekeepingEmployee',
                            'ReportController@studentResume', 
                            'ReportController@studentBirthday', 
                            'ReportController@monitorPhysicalConditionStudent',
                        ])
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link has-arrow""
                                href="#" aria-expanded="false"><i class="mdi mdi-chart-bar"></i><span
                                    class="hide-menu">Báo cáo thống kê</span></a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                @can(['ReportController@employeeResume', 'ReportController@teacherClass', 'ReportController@timekeepingEmployee'])
                                <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark"
                                    href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-border-inside"></i><span
                                        class="hide-menu">Nhân viên</span></a>
                                    <ul aria-expanded="false" class="collapse  second-level">
                                        @can('ReportController@employeeResume')
                                        <li class="sidebar-item"><a href="{{ route('report.employeeResume') }}" class="sidebar-link"><i
                                                    class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Lý lịch trích ngang
                                                </span></a></li>
                                        @endcan
                                        @can('ReportController@teacherClass')
                                        <li class="sidebar-item"><a href="{{ route('report.teacherClass') }}" class="sidebar-link"><i
                                                    class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Giáo viên phụ trách lớp
                                                </span></a></li>
                                        @endcan
                                        @can('ReportController@timekeepingEmployee')
                                        <li class="sidebar-item"><a href="{{ route('report.timekeepingEmployee') }}" class="sidebar-link"><i
                                                    class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Chấm công
                                                </span></a></li>
                                        @endcan
                                        @can('ReportController@salary')
                                        <li class="sidebar-item"><a href="{{ route('report.salary') }}" class="sidebar-link"><i
                                                    class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Chi lương
                                                </span></a></li>
                                        @endcan
                                    </ul>
                                </li>
                                @endcan
                                @can(['ReportController@studentResume', 'ReportController@studentBirthday', 'ReportController@monitorPhysicalConditionStudent', 'ReportController@attendance'])
                                <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark"
                                    href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-border-inside"></i><span
                                        class="hide-menu">Học sinh</span></a>
                                    <ul aria-expanded="false" class="collapse  second-level">
                                        @can('ReportController@studentResume')
                                        <li class="sidebar-item"><a href="{{ route('report.studentResume') }}" class="sidebar-link"><i
                                                    class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Lý lịch trích ngang
                                                </span></a></li>
                                        @endcan
                                        @can('ReportController@studentBirthday')
                                        <li class="sidebar-item"><a href="{{ route('report.studentBirthday') }}" class="sidebar-link"><i
                                                    class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Danh sách sinh nhật
                                                </span></a></li>
                                        @endcan
                                        @can('ReportController@monitorPhysicalConditionStudent')
                                        <li class="sidebar-item"><a href="{{ route('report.monitorPhysicalConditionStudent') }}" class="sidebar-link"><i
                                                    class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Theo dõi sự phát triển
                                                </span></a></li>
                                        @endcan
                                        @can('ReportController@attendance')
                                        <li class="sidebar-item"><a href="{{ route('report.attendance') }}" class="sidebar-link"><i
                                                    class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Điểm danh
                                                </span></a></li>
                                        @endcan
                                    </ul>
                                </li>
                                @endcan
                            </ul>        
                        </li>
                        @endcanany
                        <!-- MENU CẤP 1 -->
                        @canany(['DailyFeeController@index', 'CostDailyFeeController@index', 'MonthlyFeeController@index', 'CostMonthlyFeeController@index', 'AnnualFeeController@index', 'CostAnnualFeeController@index', 'ExtraServiceController@index', 'CostExtraServiceController@index', 'PhuCapController@quanLy', 'TangGiamLuongController@quanLy', 'superAdmin',
                        'DanhMucChiPhiController@index',
                        'NhaCungCapController@index',
                        'DanhMucCoSoVatChatController@index',
                        'ThanhPhanDinhDuongController@index',
                        'SuatAnController@index',
                        'HinhThucDiemDanhController@quanLy'
                        ])
                        <li class="sidebar-item"><a class="sidebar-link waves-effect waves-dark has-arrow"
                                href="#" aria-expanded="false"><i class="mdi mdi-menu"></i><span
                                    class="hide-menu">Quản lý danh mục</span></a>
                            <!-- MENU CẤP 2 -->
                            <ul aria-expanded="false" class="collapse first-level">
                                @can('HinhThucDiemDanhController@quanLy')
                                <li class="sidebar-item"><a href="{{ route('HinhThucDiemDanh.quanly') }}" class="sidebar-link"><i
                                            class="mdi mdi-format-align-justify"></i><span class="hide-menu"> Hình thức điểm danh </span></a>
                                </li>
                                @endcan
                                @can('superAdmin')
                                <li class="sidebar-item"><a href="{{ route('doituong.quanly') }}" class="sidebar-link"><i
                                                class="mdi mdi-group"></i><span class="hide-menu"> Nhóm đối tượng học sinh </span></a>
                                </li>
                                @endcan
                                @can('superAdmin')
                                <li class="sidebar-item"><a href="{{ route('nangkhieu.quanly') }}" class="sidebar-link"><i
                                            class="mdi mdi-silverware-spoon"></i><span class="hide-menu"> Quản lý năng khiếu </span></a>
                                </li>
                                @endcan
                                @can('DailyFeeController@index')
                                <li class="sidebar-item"><a href="{{ route('dailyfee.quanly') }}" class="sidebar-link"><i
                                            class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Quản lý học phí theo ngày </span></a>
                                </li>
                                @endcan
                                @can('CostDailyFeeController@index')
                                <li class="sidebar-item"><a href="{{ route('costdailyfee.quanly') }}" class="sidebar-link"><i
                                            class="mdi mdi-coin"></i><span class="hide-menu"> Quản lý định mức theo ngày </span></a>
                                </li>
                                @endcan
                                @can('MonthlyFeeController@index')
                                <li class="sidebar-item"><a href="{{ route('monthlyfee.quanly') }}" class="sidebar-link"><i
                                            class="mdi mdi-bank"></i><span class="hide-menu"> Quản lý học phí tháng </span></a>
                                </li>
                                @endcan
                                @can('CostMonthlyFeeController@index')
                                <li class="sidebar-item"><a href="{{ route('costmonthlyfee.quanly') }}" class="sidebar-link"><i
                                            class="mdi mdi-coin"></i><span class="hide-menu"> Quản lý định mức học phí tháng </span></a>
                                </li>
                                @endcan
                                @can('AnnualFeeController@index')
                                <li class="sidebar-item"><a href="{{ route('annualfee.quanly') }}" class="sidebar-link"><i
                                            class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Quản lý học phí hàng năm </span></a>
                                </li>
                                @endcan
                                @can('CostAnnualFeeController@index')
                                <li class="sidebar-item"><a href="{{ route('costannualfee.quanly') }}" class="sidebar-link"><i
                                            class="mdi mdi-coin"></i><span class="hide-menu"> Quản lý định mức học phí hàng năm </span></a>
                                </li>
                                @endcan
                                @can('ExtraServiceController@index')
                                <li class="sidebar-item"><a href="{{ route('extraservice.quanly') }}" class="sidebar-link"><i
                                            class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Quản lý dịch vụ cộng thêm </span></a>
                                </li>
                                @endcan
                                @can('CostExtraServiceController@index')
                                <li class="sidebar-item"><a href="{{ route('costextraservice.quanly') }}" class="sidebar-link"><i
                                            class="mdi mdi-coin"></i><span class="hide-menu"> Quản lý định mức dịch vụ cộng thêm </span></a>
                                </li>
                                @endcan
                                @can('superAdmin')
                                    <li class="sidebar-item"><a href="{{ route('hinhthucchamcong.quanly') }}" class="sidebar-link"><i
                                                class="mdi mdi-all-inclusive"></i><span class="hide-menu"> Hình thức chấm công
                                            </span></a></li>
                                @endcan
                                @can('superAdmin')
                                <li class="sidebar-item"><a href="{{ route('phucap.quanly') }}" class="sidebar-link"><i class="fa-solid fa-file-invoice-dollar"></i><span class="hide-menu"> Danh mục phụ cấp </span></a>
                                </li>
                                @endcan
                                @can('superAdmin')
                                <li class="sidebar-item"><a href="{{ route('tanggiamluong.quanly') }}" class="sidebar-link"><i class="fa-solid fa-money-bills"></i><span class="hide-menu"> Danh mục tăng/giảm lương </span></a>
                                        </li>
                                @endcan
                                @can('superAdmin')
                                <li class="sidebar-item"><a href="{{ route('tieuchuanphattrien.quanly') }}" class="sidebar-link"><i
                                            class="mdi mdi-developer-board"></i><span class="hide-menu"> Danh mục tiêu chuẩn phát triển </span></a>
                                </li>
                                @endcan
                                @can('DanhMucChiPhiController@index')
                                <li class="sidebar-item"><a href="{{ route('danhmucchiphi.quanly') }}" class="sidebar-link"><i class="fa-regular fa-rectangle-list"></i><span class="hide-menu"> Danh mục chi phí </span></a>
                                </li>
                                @endcan
                                @can('superAdmin')
                                <li class="sidebar-item"><a href="{{ route('nhomcosovatchat.quanly') }}" class="sidebar-link"><i class="fa-solid fa-landmark-flag"></i><span class="hide-menu"> Nhóm cơ sở vật chất </span></a>
                                </li>
                                @endcan
                                @can('NhaCungCapController@index')
                                <li class="sidebar-item"><a href="{{ route('nhacungcap.quanly') }}" class="sidebar-link"><i class="fa-solid fa-truck-droplet"></i><span class="hide-menu"> Danh mục nhà cung cấp </span></a>
                                </li>
                                @endcan
                                @can('DanhMucCoSoVatChatController@index')
                                <li class="sidebar-item"><a href="{{ route('danhmuccosovatchat.quanly') }}" class="sidebar-link"><i class="fa-solid fa-boxes-packing"></i><span class="hide-menu"> Danh mục cơ sở vật chất </span></a>
                                </li>
                                @endcan
                                @can('superAdmin')
                                <li class="sidebar-item"><a href="{{ route('nhomthucpham.quanly') }}" class="sidebar-link"><i class="fa-solid fa-carrot"></i><span class="hide-menu"> Nhóm thực phẩm </span></a>
                                </li>
                                @endcan
                                @can('ThanhPhanDinhDuongController@index')
                                <li class="sidebar-item"><a href="{{ route('thanhphandinhduong.quanly') }}" class="sidebar-link"><i class="fa-brands fa-nutritionix"></i><span class="hide-menu"> Thành phần dinh dưỡng thực phẩm </span></a>
                                </li>
                                @endcan
                                @can('superAdmin')
                                <li class="sidebar-item"><a href="{{ route('nhucaudinhduong.quanly') }}" class="sidebar-link"><i class="fa-solid fa-cheese"></i><span class="hide-menu"> Nhu cầu dinh dưỡng cho trẻ </span></a>
                                </li>
                                @endcan
                                @can('superAdmin')
                                <li class="sidebar-item"><a href="{{ route('nhucaunangluong.quanly') }}" class="sidebar-link"><i class="fa-solid fa-child-reaching"></i><span class="hide-menu"> Nhu cầu năng lượng cho trẻ </span></a>
                                </li>
                                @endcan
                                @can('SuatAnController@index')
                                <li class="sidebar-item"><a href="{{ route('suatan.quanly') }}" class="sidebar-link"><i class="fa-solid fa-hotdog"></i><span class="hide-menu"> Quản lý suất ăn </span></a>
                                </li>
                                @endcan
                                {{-- @can('ThucDonController@index')
                                <li class="sidebar-item"><a href="{{ route('thucdon.quanly') }}" class="sidebar-link"><i class="fa-solid fa-utensils"></i><span class="hide-menu"> Quản lý thực đơn </span></a>
                                </li>
                                @endcan --}}
                            </ul>
                        </li>
                        @endcanany
                        @canany(['LogController@quanLy','InfoController@index', 'UserController@quanLy', 'RoleController@index'])
                        <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link has-arrow"
                                href="/" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span
                                    class="hide-menu">Cài đặt</span></a>
                            <ul aria-expanded="false" class="collapse  first-level">
                                @canany(['UserController@quanLy', 'superAdmin'])
                                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link has-arrow"
                                        href="#" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span
                                            class="hide-menu">Quản lý người dùng</span></a>
                                    <ul aria-expanded="false" class="collapse  first-level">
                                        @can('UserController@quanLy')
                                        <li class="sidebar-item"><a href="{{ route('nguoidung.quanly') }}" class="sidebar-link"><i
                                                    class="mdi mdi-account"></i><span class="hide-menu"> Người dùng </span></a>
                                        </li>
                                        @endcan
                                        @can('superAdmin')
                                        <li class="sidebar-item"><a href="{{ route('nhomnguoidung.quanly') }}" class="sidebar-link"><i
                                                    class="mdi mdi-account-group"></i><span class="hide-menu"> Nhóm người dùng </span></a>
                                        </li>
                                        @endcan
                                    </ul>
                                </li>
                                @endcanany
                                @can('InfoController@index')
                                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                                    href="{{ route('thongtin.quanly')}}" aria-expanded="false"><i class="mdi mdi-information-outline"></i><span
                                        class="hide-menu">Thông tin trường mầm non</span></a></li>
                                @endcan
                                <li class="sidebar-item"><a href="/" class="sidebar-link"><i class="fa-solid fa-gears"></i><span class="hide-menu"> Cấu hình hệ thống </span></a>
                                @can('LogController@quanLy')
                                <li class="sidebar-item"><a href="{{ route('log.quanly') }}" class="sidebar-link"><i class="fa-solid fa-book"></i><span class="hide-menu"> Log người dùng </span></a>
                                </li>
                                @endcan
                                <li class="sidebar-item"><a href="/" class="sidebar-link"><i class="fa-solid fa-trash-arrow-up"></i><span class="hide-menu"> Sao lưu/phục hồi dữ liệu </span></a>
                                </li>
                            </ul>
                        </li>
                        @endcanany
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        @yield('content')
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    

    <script src="{{ asset('dist/js/jquery.ui.touch-punch-improved.js') }}"></script>
    <script src="{{ asset('dist/js/jquery-ui.min.js') }}"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js') }}"></script>
    <script src="{{ asset('assets/extra-libs/sparkline/sparkline.js') }}"></script>
    <!--Wave Effects -->
    <script src="{{ asset('dist/js/waves.js') }}"></script>
    <!--Menu sidebar -->
    <script src="{{ asset('dist/js/sidebarmenu.js') }}"></script>
    <!--Custom JavaScript -->
    <script src="{{ asset('dist/js/custom.min.js') }}"></script>
    <!-- this page js -->
    <script src="{{ asset('assets/libs/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/calendar/cal-init.js') }}"></script>

    {{-- chart js --}}
    <script src="{{ asset('assets/libs/flot/excanvas.js') }}"></script>
    <script src="{{ asset('assets/libs/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('assets/libs/flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('assets/libs/flot/jquery.flot.time.js') }}"></script>
    <script src="{{ asset('assets/libs/flot/jquery.flot.stack.js') }}"></script>
    <script src="{{ asset('assets/libs/flot/jquery.flot.crosshair.js') }}"></script>
    <script src="{{ asset('assets/libs/flot.tooltip/js/jquery.flot.tooltip.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/chart/chart-page-init.js') }}"></script>
    {{-- end chart js --}}

    @yield('js')
</body>

</html>