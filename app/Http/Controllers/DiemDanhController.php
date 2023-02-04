<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DiemDanh;
use App\Models\SchoolYear;
use App\Models\Record;
use App\Models\Classes;
use App\Services\ApiResultService;
use App\Services\StudentService;
use App\Http\Controllers\helpers\HelperController;

class DiemDanhController extends Controller
{
    public function index()
    {
        return view('admin.diem_danh');
    }

    public function getList(Request $request)
    {
        try {
            $date = $request->month;
            $request->year = HelperController::format_month_year_helper($date)[1];
            $request->month = HelperController::format_month_year_helper($date)[0];
            $data = [];
            if (!empty($request->class)) {
                $students = Record::where('status', 1)->where('id_class', $request->class)->get();
                $diemDanhs = [];
                foreach ($students as $student) {
                    $dataStudent = DiemDanh::where('month', $request->month)->where('year', $request->year)->where('ma_hs', $student['id'])->get()->first();
                    if (!empty($dataStudent)) {
                        $diemDanhs[] = $dataStudent['data'];
                    }
                }
                $data = [
                    'status'    =>  true,
                    'diemDanhs' =>  $diemDanhs
                ];
            }
        } catch (Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   => $e
            ];
        }
        return response()->json($data);
    }

    public function getYears()
    {
        try {
            $curYear = date('Y');
            $minYear = SchoolYear::select('name')->orderBy('name')->get()->first();
            $years = [];
            for ($i = (int)$curYear; $i >= (int)$minYear['name']; $i--) {
                $years[] = $i;
            }
            $data = [
                'status'    =>  true,
                'years' => $years
            ];
        } catch (Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   => $e
            ];
        }
        return response()->json($data);
    }

    public function getClasses(Request $request)
    {
        try {
            $lops = Classes::where('grade', $request->grade);
            if($request->school_year) {
                $lops = $lops->where('school_year', $request->school_year);
            }
            if($request->id_coso) {
                $lops = $lops->where('id_coso', $request->id_coso);
            }
            $lops = $lops->get();
            $data = [
                'status'    =>  true,
                'lops' => $lops
            ];
        } catch (Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   => $e
            ];
        }
        return response()->json($data);
    }

    public function dataStudentActive(Request $request, $id_class)
    {
        try {
            $students = Record::where('status', 1)->where('id_class', $id_class)->get();
            $data = [
                'status'    =>  true,
                'students' => $students
            ];
        } catch (Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   => $e
            ];
        }
        return response()->json($data);
    }

    public function updateDiemDanh(Request $request)
    {
        try {
            $date = $request->month;
            $year = HelperController::format_month_year_helper($date)[1];
            $month = HelperController::format_month_year_helper($date)[0];
            $diemDanhs = $request->diemDanhs;

            for ($i = 0; $i < count($diemDanhs); $i++) {
                $item = DiemDanh::where('month', $month)->where('year', $year)->where('ma_hs', $diemDanhs[$i]['ma_hs'])->first();
                if ($item) {
                    $item->data = json_encode($diemDanhs[$i]);
                    $item->save();
                } else {
                    $item = new DiemDanh();
                    $item->data = json_encode($diemDanhs[$i]);
                    $item->month = $month;
                    $item->year = $year;
                    $item->ma_hs = $diemDanhs[$i]["ma_hs"];
                    $item->save();
                }
            }

            $data = [
                'status' => true
            ];
            return json_encode($data);
        } catch (Exception $e) {
            $data = [
                'status' => false
            ];
            return json_encode($data);
        }
    }

    public function getDataAttendance(Request $request)
    {
        try {
            $grades = $request->get('grades', []);
            $class = $request->get('class', []);
            $month = $request->get('month', date('m'));
            $year = $request->get('year', date('Y'));
            if (!$month) {
                $month =  date('m');
            }
            if (!$year) {
                $year =  date('Y');
            }
            $status = $request->get('status', -1);
            if ($status == '') {
                $status = -1;
            }
            $pageSize = $request->get('page_size', 10);
            $page = $request->get('page', 1);
            $data = StudentService::getDataTimekeeping($class, $grades, $month, $year, $status, $page, $pageSize);
            return ApiResultService::success($data);
        } catch (\Exception $e) {
            return ApiResultService::error('Lỗi server');
        }
    }
}
