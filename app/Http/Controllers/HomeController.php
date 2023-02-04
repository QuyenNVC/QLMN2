<?php

namespace App\Http\Controllers;
use App\Models\Record;
use App\Models\User;
use Carbon\Carbon;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index() {
        $data = [
            [
                'permission'    =>  null,
                'col-lg'    =>  '2',
                'link'      =>  "dashboard",
                'bg-color'  =>  'bg-cyan',
                'icon'      =>  'view-dashboard',
                'title'     =>  'Dashboard'
            ],
            [
                'permission'    =>  'StudentController@index',
                'col-lg'    =>  '4',
                'link'      =>  "danh-sach-hoc-sinh/all",
                'bg-color'  =>  'bg-success',
                'icon'      =>  'chart-areaspline',
                'title'     =>  'Quản lý học sinh'
            ],
            [
                'permission'    =>  'EnrollmentController@index',
                'col-lg'    =>  '2',
                'link'      =>  "ky-tuyen-sinh",
                'bg-color'  =>  'bg-warning',
                'icon'      =>  'collage',
                'title'     =>  'Tuyển sinh'
            ],
            [
                'permission'    =>  'ClassController@index',
                'col-lg'    =>  '2',
                'link'      =>  "lop-hoc",
                'bg-color'  =>  'bg-danger',
                'icon'      =>  'border-outside',
                'title'     =>  'Quản lý lớp học'
            ],
            [
                'permission'    =>  'NhanVienController@create',
                'col-lg'    =>  '4',
                'link'      =>  "nhan-vien",
                'bg-color'  =>  'bg-info',
                'icon'      =>  'arrow-all',
                'title'     =>  'Nhân viên'
            ],
            [
                'permission'    =>  null,
                'col-lg'    =>  '4',
                'link'      =>  "quan-ly-thu",
                'bg-color'  =>  'bg-danger',
                'icon'      =>  'receipt',
                'title'     =>  'Quản lý thu chi'
            ],
            [
                'permission'    =>  null,
                'col-lg'    =>  '2',
                'link'      =>  "danh-muc-co-so-vat-chat",
                'bg-color'  =>  'bg-info',
                'icon'      =>  'relative-scale',
                'title'     =>  'QL cơ sở vật chất'
            ],
            [
                'permission'    =>  null,
                'col-lg'    =>  '2',
                'link'      =>  "",
                'bg-color'  =>  'bg-cyan',
                'icon'      =>  'pencil',
                'title'     =>  'Giáo án giảng dạy'
            ],
            [
                'permission'    =>  null,
                'col-lg'    =>  '2',
                'link'      =>  "",
                'bg-color'  =>  'bg-success',
                'icon'      =>  'calendar-check',
                'title'     =>  'Sổ liên lạc'
            ],
            [
                'permission'    =>  null,
                'col-lg'    =>  '2',
                'link'      =>  "student-resume",
                'bg-color'  =>  'bg-warning',
                'icon'      =>  'alert',
                'title'     =>  'Báo cáo thống kê'
            ]
        ];
        $countStudent = Record::whereNull('isDeleted')->get()->count();
        $start = Carbon::createFromFormat('Y-m-d', date('Y-m-d'))
        ->startOfMonth()
        ->format('Y-m-d');
        $end = Carbon::createFromFormat('Y-m-d', date('Y-m-d'))
        ->endOfMonth()
        ->format('Y-m-d');
        $countNewStudent = Record::whereNull('isDeleted')->whereBetween('ngay_vao_hoc', [$start, $end])->get()->count();
        $data_info = [
            [
                'icon'  =>  'account',
                'title' =>  'Học sinh',
                'data'  =>  $countStudent
            ],
            [
                'icon'  =>  'plus',
                'title' =>  'Nhân viên',
                'data'  =>  '60'
            ],
            [
                'icon'  =>  'cart',
                'title' =>  'Học sinh mới',
                'data'  =>  $countNewStudent
            ],
            [
                'icon'  =>  'tag',
                'title' =>  'Tổng chi',
                'data'  =>  '9540000'
            ],
            [
                'icon'  =>  'table',
                'title' =>  'Phản hồi',
                'data'  =>  '100'
            ],
            [
                'icon'  =>  'web',
                'title' =>  'Truy cập',
                'data'  =>  '18540'
            ],
        ];
        return view('admin.home',['data'    =>  $data, 'data_info' => $data_info]);
    }
}
