<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiResultService;
use App\Services\EmployeeService;

class ChiLuongController extends Controller
{
    /**
     * get view phu_caps
     * Author: HaoDT
     */
    public function getChiLuongNhanVien(Request $request)
    {
        return view('admin.chi_luong_nhan_vien');
    }



    /**
     * get view phu_caps
     * Author: HaoDT
     */
    public function print(Request $request, $ma_nv, $thang, $nam)
    {
        return view('admin.print_pdf')->with('ma_nv', $ma_nv)
            ->with('thang', $thang)
            ->with('nam', $nam);
    }

    /**
     * get data salary
     * @author HaoDT
     */
    public function getDataSalary(Request $request)
    {
        try {
            $staffIds = $request->get('staff_ids', []);
            $excludes = $request->get('excludes_ids', []);
            // $name = $request->get('name', '');
            $departments = $request->get('deparment', []);
            // $workingStatus = $request->get('working_status', -1);
            $month = $request->get('month', date('m'));
            $year = $request->get('year', date('Y'));
            $pageSize = $request->get('page_size', 10);
            $page = $request->get('page', 1);
            $data = EmployeeService::getDataSalary($staffIds, $excludes, $departments, $month, $year, $pageSize, $page);
            return ApiResultService::success($data);
        } catch (\Exception $e) {
            return ApiResultService::error('Lỗi server');
        }
    }
}
