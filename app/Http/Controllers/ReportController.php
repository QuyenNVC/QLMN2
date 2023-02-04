<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NhanVien;
use App\Models\Record;
use App\Models\HinhThucChamCong;
use App\Services\ApiResultService;
use App\Services\ExcelService;
use App\Services\EmployeeService;
use App\Services\StudentService;
use App\Services\ClassService;
use App\Services\MonitorPhysicalService;
use App\Services\DateService;
use App\Services\CommonService;

class ReportController extends Controller
{
    /**
     * Function get view employee resume
     * @author HaoDT
     */
    public function employeeResume()
    {
        return view('admin.report.employee-resume');
    }

    /**
     * Function get data employee resume
     * @author HaoDT
     */
    public function exportExcelEmployeeResume(Request $request)
    {
        try {
            $staffIds = $request->get('staff_ids', []);
            $excludes = $request->get('excludes_ids', []);
            $name = $request->get('name', '');
            $departments = $request->get('department', []);
            $workingStatus = $request->get('working_status', -1);
            $pageSize = $request->get('page_size', 10);
            $page = $request->get('page', 1);
            $data = EmployeeService::getData($staffIds, $excludes, $name, $departments, $workingStatus, $pageSize, $page, true);

            $items = $data['data'];
            for ($i = 0; $i < count($items); $i++) {
                $items[$i]['department_name'] = $items[$i]['department'] ? $items[$i]['department']['name'] : '';
            }
            $columnTitle = [
                [
                    'column' => 'A11',
                    'title' => 'Mã NV',
                    'width' => 7,
                    'field' => 'ma_nv',
                    'align' => 'center',
                ],
                [
                    'column' => 'B11',
                    'title' => 'Họ tên',
                    'width' => 30,
                    'field' => 'fullname',
                    'align' => 'left',
                ],
                [
                    'column' => 'C11',
                    'title' => 'Giới tính',
                    'width' => 9,
                    'field' => 'gender',
                    'align' => 'center',
                ],
                [
                    'column' => 'D11',
                    'title' => 'Ngày sinh',
                    'width' => 15,
                    'field' => 'birthday',
                    'align' => 'center',
                ],
                [
                    'column' => 'E11',
                    'title' => 'Dân tộc',
                    'width' => 10,
                    'field' => 'dan_toc',
                    'align' => 'left',
                ],
                [
                    'column' => 'F11',
                    'title' => 'CMND',
                    'width' => 15,
                    'field' => 'cmnd',
                    'align' => 'center',
                ],
                [
                    'column' => 'G11',
                    'title' => 'Nơi cấp',
                    'width' => 15,
                    'field' => 'noi_cap',
                    'align' => 'left',
                ],
                [
                    'column' => 'H11',
                    'title' => 'Ngày cấp',
                    'width' => 12,
                    'field' => 'ngay_cap',
                    'align' => 'center',
                ],
                [
                    'column' => 'I11',
                    'title' => 'SDT',
                    'width' => 12,
                    'field' => 'phone',
                    'align' => 'center',
                ],
                [
                    'column' => 'J11',
                    'title' => 'Email',
                    'width' => 30,
                    'field' => 'email',
                    'align' => 'left',
                ],
                [
                    'column' => 'K11',
                    'title' => 'Lương ngày',
                    'width' => 15,
                    'field' => 'luong_ngay',
                    'align' => 'right',
                ],
                [
                    'column' => 'L11',
                    'title' => 'Địa chỉ',
                    'width' => 30,
                    'field' => 'address',
                    'align' => 'left',
                ],
                [
                    'column' => 'M11',
                    'title' => 'Ngày vào làm',
                    'width' => 15,
                    'field' => 'ngay_vao_lam',
                    'align' => 'center',
                ],
                [
                    'column' => 'N11',
                    'title' => 'Ngày nghỉ làm',
                    'width' => 15,
                    'field' => 'ngay_nghi_lam',
                    'align' => 'center',
                ],
                [
                    'column' => 'O11',
                    'title' => 'Phòng ban',
                    'width' => 30,
                    'field' => 'department_name',
                    'align' => 'left',
                ],
            ];

            $result = ExcelService::export($items, 'Thống kê lý lịch trích ngang', $columnTitle, 'report-employee-resume.xlsx', 12);
            return ApiResultService::success($result);
        } catch (\Exception $e) {
            return ApiResultService::error('Lỗi server');
        }
    }

    /**
     * Function get view student resume
     * @author HaoDT
     */
    public function studentResume()
    {
        return view('admin.report.student-resume');
    }

    /**
     * Function export excel student resume
     * @author HaoDT
     */
    public function exportExcelStudentResume(Request $request)
    {
        try {
            $schoolYears = $request->get('school_year', []);
            $grades = $request->get('grades', []);
            $class = $request->get('class', []);
            $months = $request->get('months', []);
            $status = $request->get('status', -1);
            $pageSize = $request->get('page_size', 10);
            $page = $request->get('page', 1);
            $studentIds = $request->get('student_ids', []);
            $excludes = $request->get('excludes_ids', []);


            $data = StudentService::getData($studentIds, $excludes, $schoolYears, $grades, $class, $months, $status, $pageSize, $page, true);
            $items = $data['data'];
            for ($i = 0; $i < count($items); $i++) {
                $items[$i]['class_name'] = $items[$i]['class'] ? $items[$i]['class']['name'] : '';
                $items[$i]['gender'] = $items[$i]['gender'] == 1 ? 'Nam' : 'Nữ';
            }
            $columnTitle = [
                [
                    'column' => 'A11',
                    'title' => 'Mã HS',
                    'width' => 7,
                    'field' => 'id',
                    'align' => 'center',
                ],
                [
                    'column' => 'B11',
                    'title' => 'Họ tên',
                    'width' => 30,
                    'field' => 'name',
                    'align' => 'left',
                ],
                [
                    'column' => 'C11',
                    'title' => 'Ngày sinh',
                    'width' => 15,
                    'field' => 'birth_date',
                    'align' => 'center',
                ],
                [
                    'column' => 'D11',
                    'title' => 'Giới tính',
                    'width' => 9,
                    'field' => 'gender',
                    'align' => 'center',
                ],
                [
                    'column' => 'E11',
                    'title' => 'Phụ huynh',
                    'width' => 28,
                    'field' => 'parent_name',
                    'align' => 'left',
                ],
                [
                    'column' => 'F11',
                    'title' => 'Điện thoại',
                    'width' => 15,
                    'field' => 'parent_phone',
                    'align' => 'center',
                ],
                [
                    'column' => 'G11',
                    'title' => 'Địa chỉ',
                    'width' => 45,
                    'field' => 'address',
                    'align' => 'left',
                ],
                [
                    'column' => 'H11',
                    'title' => 'Lớp học',
                    'width' => 28,
                    'field' => 'class_name',
                    'align' => 'left',
                ],
            ];

            $result = ExcelService::export($items, 'Thống kê lý lịch trích ngang', $columnTitle, 'report-student-resume.xlsx', 12);
            return ApiResultService::success($result);
        } catch (\Exception $e) {
            return ApiResultService::error('Lỗi server');
        }
    }

    /**
     * Function get view student birthday
     * @author HaoDT
     */
    public function studentBirthday()
    {
        return view('admin.report.student-birthday');
    }

    /**
     * Function get view student birthday
     * @author HaoDT
     */
    public function teacherClass()
    {
        return view('admin.report.teacher-class');
    }

    /**
     * Function export excel teacher class
     * @author HaoDT
     */
    public function exportExcelTeacherClass(Request $request)
    {
        try {
            $classIds = $request->get('class_ids', []);
            $excludes = $request->get('excludes_ids', []);
            $departments = $request->get('department', []);
            $class = $request->get('class', []);
            $pageSize = $request->get('page_size', 10);
            $page = $request->get('page', 1);
            $data = ClassService::getData($classIds, $excludes, $class, $departments, $pageSize, $page, true);
            $items = $data['data'];


            // hard class name room
            $classRooms = ['1' => 'Tầng 1', '2' => 'Tầng 2', '3' => 'Tầng 3',  '4' => 'Tầng 4'];
            // handle time study
            for ($i = 0; $i < count($items); $i++) {
                $items[$i]['time_study'] = $items[$i]['time_start'] . '-' . $items[$i]['time_end'];
                $items[$i]['class_room_name'] = $classRooms[$items[$i]['class_room']];
            }

            $columnTitle = [
                [
                    'column' => 'A11',
                    'title' => 'Mã lớp',
                    'width' => 7,
                    'field' => 'id_class',
                    'align' => 'center',
                ],
                [
                    'column' => 'B11',
                    'title' => 'Lớp học',
                    'width' => 30,
                    'field' => 'class_name',
                    'align' => 'left',
                ],
                [
                    'column' => 'C11',
                    'title' => 'Giáo viên',
                    'width' => 30,
                    'field' => 'fullname',
                    'align' => 'left',
                ],
                [
                    'column' => 'D11',
                    'title' => 'Thời gian học',
                    'width' => 35,
                    'field' => 'time_study',
                    'align' => 'center',
                ],
                [
                    'column' => 'E11',
                    'title' => 'Số học sinh tối đa',
                    'width' => 20,
                    'field' => 'max_quantity',
                    'align' => 'center',
                ],
                [
                    'column' => 'F11',
                    'title' => 'Năm học',
                    'width' => 15,
                    'field' => 'period',
                    'align' => 'center',
                ],
                [
                    'column' => 'G11',
                    'title' => 'Khối lớp',
                    'width' => 30,
                    'field' => 'grade_name',
                    'align' => 'left',
                ],
                [
                    'column' => 'H11',
                    'title' => 'Địa điểm học',
                    'width' => 28,
                    'field' => 'class_room_name',
                    'align' => 'left',
                ],
            ];

            $result = ExcelService::export($items, 'Giáo viên phụ trách lớp', $columnTitle, 'report-teacher-class.xlsx', 12);
            return ApiResultService::success($result);
        } catch (\Exception $e) {
            return ApiResultService::error('Lỗi server');
        }
    }

    /**
     * Function get view student birthday
     * @author HaoDT
     */
    public function monitorPhysicalConditionStudent()
    {
        return view('admin.report.monitor-physical-condition-student');
    }

    /**
     * Function export excel teacher class
     * @author HaoDT
     */
    public function exportExcelMonitorPhysical(Request $request)
    {
        try {
            $monitorPhysicalIds = $request->get('monitor_physical__ids', []);
            $excludes = $request->get('excludes_ids', []);
            $class = $request->get('class', []);
            $grades = $request->get('grades', []);
            $month = $request->get('month', []);
            $year = $request->get('year', []);
            $certification = $request->get('certification', []);
            $pageSize = $request->get('page_size', 10);
            $page = $request->get('page', 1);
            $data = MonitorPhysicalService::getData($monitorPhysicalIds, $excludes, $class, $grades, $month, $year, $certification, $pageSize, $page, true);
            $items = $data['data'];


            // handle value
            for ($i = 0; $i < count($items); $i++) {
                $items[$i]['be_khoe'] = $items[$i]['be_khoe'] ? 'Có' : '';
                $items[$i]['be_ngoan'] = $items[$i]['be_ngoan'] ? 'Có' : '';
                $items[$i]['h-standard'] = '';
                $items[$i]['w-standard'] = '';
                $items[$i]['month_old'] = DateService::monthBetweenTwoDay($items[$i]['birth_date'], date('Y-m-d'));
            }

            $columnTitle = [
                [
                    'column' => 'A11',
                    'title' => 'ID',
                    'width' => 7,
                    'field' => 'id',
                    'align' => 'center',
                ],
                [
                    'column' => 'B11',
                    'title' => 'Tên học sinh',
                    'width' => 30,
                    'field' => 'name',
                    'align' => 'left',
                ],
                [
                    'column' => 'C11',
                    'title' => 'Tháng tuổi',
                    'width' => 10,
                    'field' => 'month_old',
                    'align' => 'left',
                ],
                [
                    'column' => 'D11',
                    'title' => 'H-Tiêu chuẩn (cm)',
                    'width' => 17,
                    'field' => 'h-standard',
                    'align' => 'center',
                ],
                [
                    'column' => 'E11',
                    'title' => 'Chiều cao (cm)',
                    'width' => 14,
                    'field' => 'height',
                    'align' => 'center',
                ],
                [
                    'column' => 'F11',
                    'title' => 'Đánh giá chiều cao',
                    'width' => 17,
                    'field' => 'evaluate_height',
                    'align' => 'center',
                ],
                [
                    'column' => 'G11',
                    'title' => 'W-Tiêu chuẩn (kg)',
                    'width' => 17,
                    'field' => 'w-standard',
                    'align' => 'left',
                ],
                [
                    'column' => 'H11',
                    'title' => 'Cân nặng (kg)',
                    'width' => 14,
                    'field' => 'weigth',
                    'align' => 'left',
                ],
                [
                    'column' => 'I11',
                    'title' => 'Đánh giá cân nặng',
                    'width' => 17,
                    'field' => 'evaluate_weigth',
                    'align' => 'left',
                ],
                [
                    'column' => 'J11',
                    'title' => 'Đánh giá sức khỏe/thể chất',
                    'width' => 25,
                    'field' => 'evaluate_physical',
                    'align' => 'left',
                ],
                [
                    'column' => 'K11',
                    'title' => 'Phát triển nhận thức',
                    'width' => 20,
                    'field' => 'cognitive_development',
                    'align' => 'left',
                ],
                [
                    'column' => 'L11',
                    'title' => 'Phát triển năng khiếu',
                    'width' => 20,
                    'field' => 'talent_development',
                    'align' => 'left',
                ],
                [
                    'column' => 'M11',
                    'title' => 'Phát triển tình cảm và quan hệ xã hội',
                    'width' => 34,
                    'field' => 'relationship_development',
                    'align' => 'left',
                ],
                [
                    'column' => 'N11',
                    'title' => 'Phát triển ngôn ngữ',
                    'width' => 20,
                    'field' => 'language_development',
                    'align' => 'left',
                ],
                [
                    'column' => 'O11',
                    'title' => 'Bé khỏe',
                    'width' => 10,
                    'field' => 'be_khoe',
                    'align' => 'left',
                ],
                [
                    'column' => 'P11',
                    'title' => 'Bé ngoan',
                    'width' => 10,
                    'field' => 'be_ngoan',
                    'align' => 'left',
                ],
            ];

            $result = ExcelService::export($items, 'Theo dõi sức khỏe', $columnTitle, 'report-monitor-physical.xlsx', 12);
            return ApiResultService::success($result);
        } catch (\Exception $e) {
            return ApiResultService::error('Lỗi server');
        }
    }

    /**
     * Function get view timekeeping employee
     * @author HaoDT
     */
    public function timekeepingEmployee()
    {
        return view('admin.report.timekeeping-employee');
    }

    /**
     * Function export excel teacher class
     * @author HaoDT
     */
    public function exportExcelTimekeepingEmployee(Request $request)
    {
        try {
            $department = $request->get('department', -1);
            $month = $request->get('month', date('m'));
            $year = $request->get('year', date('Y'));
            $items = EmployeeService::getDataTimekeeping($department, $month, $year);


            // get data timekeeping form
            $dataTimeKeepingForm = HinhThucChamCong::all();

            // get data employee
            $dataEmployee = NhanVien::where('id_phong_ban', $department)->where('status_nghi_viec', '!=', 1)->get();
            $arrayEmployeeId = [];
            foreach ($items as $item) {
                $arrayEmployeeId[] = $item['ma_nv'];
            }

            $itemsTemp = [];
            foreach ($dataEmployee as $item) {
                if (in_array($item->ma_nv, $arrayEmployeeId)) {
                    $itemTimeKeeping = $items[0];
                    foreach ($items as  $itemTK) {
                        if ($itemTK['ma_nv'] == $item->ma_nv) {
                            $itemTimeKeeping = $itemTK;
                        }
                    }
                    $itemsTemp[] = $itemTimeKeeping;
                } else {
                    $itemsTemp[] = [
                        'ma_nv' => $item->ma_nv,
                        'fullname' => $item->fullname,
                        'data' => [],
                    ];
                }
            }
            $items = $itemsTemp;

            $columnTitle = [
                [
                    'column' => 'A11',
                    'title' => '#',
                    'width' => 7,
                    'field' => 'stt',
                    'align' => 'center',
                ],
                [
                    'column' => 'B11',
                    'title' => 'Mã NV',
                    'width' => 15,
                    'field' => 'ma_nv',
                    'align' => 'center',
                ],
                [
                    'column' => 'C11',
                    'title' => 'Họ tên',
                    'width' => 30,
                    'field' => 'fullname',
                    'align' => 'left',
                ],
            ];

            // generate column title
            for ($i = 0; $i < count($items); $i++) {
                $items[$i]['stt'] = $i  +  1;
                for ($j = 1; $j <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $j++) {
                    $items[$i]['day_' . $j] = (isset($items[$i]['data'][$j]) && $items[$i]['data'][$j]) ? $items[$i]['data'][$j] : '';
                    $itemColumn = [
                        'column' => CommonService::convertNumberToAlphabet($j + 2) . '11',
                        'title' => $j . '',
                        'width' => 7,
                        'field' => 'day_' . $j,
                        'align' => 'center',
                    ];
                    $columnTitle[] = $itemColumn;
                }
                $j = cal_days_in_month(CAL_GREGORIAN, $month, $year) + 1;
                foreach ($dataTimeKeepingForm as $dataItem) {
                    $items[$i]['tong_' . $dataItem->id] = (isset($items[$i]['data']['tong_' . $dataItem->id]) && $items[$i]['data']['tong_' . $dataItem->id]) ? $items[$i]['data']['tong_' . $dataItem->id] : '';
                    $itemColumn = [
                        'column' => CommonService::convertNumberToAlphabet($j + 2) . '11',
                        'title' => 'tong_' . $dataItem->id,
                        'width' => 10,
                        'field' => 'tong_' . $dataItem->id,
                        'align' => 'center',
                    ];
                    $columnTitle[] = $itemColumn;
                    $j++;
                }
            }

            $result = ExcelService::export($items, 'Chấm công nhân viên', $columnTitle, 'report-timekeeping-employee.xlsx', 12);
            return ApiResultService::success($result);
        } catch (\Exception $e) {
            return ApiResultService::error('Lỗi server');
        }
    }

    /**
     * Function get view salary report
     * @author HaoDT
     */
    public function salary()
    {
        return view('admin.report.salary');
    }

    /**
     * Function export excel report salary
     * @author HaoDT
     */
    public function exportExcelReportSalary(Request $request)
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
            $items = $data['data'];


            $columnTitle = [
                [
                    'column' => 'A11',
                    'title' => 'Mã NV',
                    'width' => 7,
                    'field' => 'ma_nv',
                    'align' => 'center',
                ],
                [
                    'column' => 'B11',
                    'title' => 'Họ tên',
                    'width' => 30,
                    'field' => 'fullname',
                    'align' => 'left',
                ],
                [
                    'column' => 'C11',
                    'title' => 'Phòng ban',
                    'width' => 30,
                    'field' => 'department',
                    'align' => 'left',
                ],
                [
                    'column' => 'D11',
                    'title' => 'Số ngày đi làm',
                    'width' => 20,
                    'field' => 'work_day',
                    'align' => 'left',
                ],
                [
                    'column' => 'E11',
                    'title' => 'Lương ngày',
                    'width' => 20,
                    'field' => 'salary_day',
                    'align' => 'left',
                ],
                [
                    'column' => 'F11',
                    'title' => 'Ứng lương',
                    'width' => 20,
                    'field' => 'salary_advance',
                    'align' => 'left',
                ],
                [
                    'column' => 'G11',
                    'title' => 'Phụ cấp',
                    'width' => 20,
                    'field' => 'allowance',
                    'align' => 'left',
                ],
                [
                    'column' => 'H11',
                    'title' => 'Tăng giảm lương',
                    'width' => 20,
                    'field' => 'fluctuate',
                    'align' => 'left',
                ],
                [
                    'column' => 'I11',
                    'title' => 'Tiền lương',
                    'width' => 20,
                    'field' => 'salary',
                    'align' => 'left',
                ],
            ];

            $result = ExcelService::export($items, 'Thống kê chi lương', $columnTitle, 'report-salary.xlsx', 12);
            return ApiResultService::success($result);
        } catch (\Exception $e) {
            return ApiResultService::error('Lỗi server');
        }
    }

    /**
     * Function get view attendance report
     * @author HaoDT
     */
    public function attendance()
    {
        return view('admin.report.attendance');
    }

    /**
     * Function export excel report attendance
     * @author HaoDT
     */
    public function exportExcelReportAttendance(Request $request)
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

            $items = $data['data'];


            $columnTitle = [
                [
                    'column' => 'A11',
                    'title' => 'Mã HS',
                    'width' => 7,
                    'field' => 'student_id',
                    'align' => 'center',
                ],
                [
                    'column' => 'B11',
                    'title' => 'Tên học sinh',
                    'width' => 30,
                    'field' => 'name',
                    'align' => 'left',
                ],
                [
                    'column' => 'C11',
                    'title' => 'Có đi học',
                    'width' => 50,
                    'field' => 'go_to_school',
                    'align' => 'left',
                ],
                [
                    'column' => 'D11',
                    'title' => 'Không đi học',
                    'width' => 50,
                    'field' => 'dont_go_to_school',
                    'align' => 'left',
                ],
                [
                    'column' => 'E11',
                    'title' => 'Bảo lưu',
                    'width' => 14,
                    'field' => 'reserve',
                    'align' => 'center',
                ],
            ];

            $result = ExcelService::export($items, 'Điểm danh học sinh', $columnTitle, 'report-attendance.xlsx', 12);
            return ApiResultService::success($result);
        } catch (\Exception $e) {
            return ApiResultService::error('Lỗi server');
        }
    }
}
