<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentFee;
use App\Models\Record;
use App\Models\Classes;
use App\Models\SchoolYear;
use App\Models\CostAnnualFeeAnnualFee;
use App\Models\Grade;
use App\Models\CostExtraServiceExtraService;
use App\Models\CostMonthlyFeeMonthlyFee;
use App\Models\CostDailyFee;
use App\Models\DailyFee;
use App\Models\Doituong;
use App\Models\DiemDanh;
use App\Models\HinhThucDiemDanh;
use App\Models\QuanLyThu;
use App\Models\LogQuanLyThu;
use App\Http\Controllers\QuanLyThuController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\helpers\HelperController;
use App\Services\CostMonthlyFeeService;
use App\Services\CostAnnualFeeService;
use App\Services\CostExtraServiceService;
use App\Services\DateService;
use App\Services\HinhThucDiemDanhService;

class StudentFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.student_fee');
    }

    public function getList(Request $request) {
        try {
            // Khởi tạo biến lưu giá trị đầu vào
            $date = $request->month;
            $request->year = HelperController::format_month_year_helper($date)[1];
            $request->month = HelperController::format_month_year_helper($date)[0];
            $class = Classes::find($request->class);
            $id_coso = $class->id_coso;
            $grade = $class->grade;
            $school_year = DateService::defineSchoolYear($date)->id;

            // getDataStudents
            $students = Record::join('student_fee', 'enrollment_records.id', '=', 'student_fee.ma_hs')
            ->join('class', 'class.id', '=', 'enrollment_records.id_class')
            ->where('enrollment_records.status', '<=', 1)
            ->where('student_fee.month', $request->month)
            ->where('student_fee.year', $request->year)
            ->whereNull('enrollment_records.isDeleted')
            ->where('id_class', $request->class)
            ->select(['enrollment_records.id as ma_hs', 'enrollment_records.address', 'enrollment_records.name as name', 'enrollment_records.doi_tuong_uu_tien as doi_tuong_uu_tien',
            'enrollment_records.extra_services', 'student_fee.data as data', 'student_fee.payment_date as payment_date', 'student_fee.note as note', 'student_fee.isIncludedAnnualFee as isIncludedAnnualFee'])->get();

            // getDataAnnualFees
            $fees = CostAnnualFeeService::getData($id_coso, $grade, $school_year);
            $annualFees = count($fees) ? $fees[0]->fees : [];
            // getDataMonthlyFees
            $fees = CostMonthlyFeeService::getData($id_coso, $grade, $school_year);
            $monthlyFees = count($fees) ? $fees[0]->fees : [];

            // getDataDailyFees
            $dailyFees = DailyFee::leftJoin('class_daily_fee', 'daily_fee.id', '=', 'class_daily_fee.id_type')
            ->where('class_daily_fee.id_class', $request->class)->where('id_coso', $id_coso)
            ->whereNull('isDeleted')->get();

            // getDataServiceFees
            $fees = CostExtraServiceService::getData($id_coso, $grade, $school_year);
            $serviceFees = count($fees) ? $fees[0]->fees : [];

            // getDoiTuongUuTien
            $doiTuongUuTiens = Doituong::whereNull('isDeleted')->get();

            $hinhThucDiemDanhs = HinhThucDiemDanhService::getData($id_coso);

            $feeTienAn = DailyFee::where('name', 'Tiền ăn')->where('id_coso', $id_coso)->whereNull('isDeleted')->get()->first();
            $hinhThucCoTinhTienAn = [];
            if($feeTienAn) {
                foreach($hinhThucDiemDanhs as $key => $item) {
                    $idOfHinhThucs = '';
                    $idOfHinhThucs = $item->daily;
                    
                    if(strlen($idOfHinhThucs) > 1) {
                        $idOfHinhThucs = explode(',', $idOfHinhThucs);
                        $index = array_search($feeTienAn->id, $idOfHinhThucs);
                        if ($index !== false) {
                            $hinhThucCoTinhTienAn[] = $item->id;
                        }
                    }
                    else if(strlen($idOfHinhThucs) == 1) {
                        if($idOfHinhThucs == $feeTienAn->id) {
                            $hinhThucCoTinhTienAn[] = $item->id;
                        }
                    }
                }
            }

            // Dùng cho cách tính học phí theo ngày
            // $feeHocPhi = DailyFee::where('name', 'Học phí')->where('id_coso', $id_coso)->whereNull('isDeleted')->get()->first();
            // $hinhThucCoTinhHocPhi = [];
            // if($feeHocPhi) {
               
            //     foreach($hinhThucDiemDanhs as $key => $item) {
            //         $idOfHinhThucs = '';
            //         $idOfHinhThucs = $item->daily;
                    
            //         if(strlen($idOfHinhThucs) > 1) {
            //             $idOfHinhThucs = explode(',', $idOfHinhThucs);
            //             $index = array_search($feeHocPhi->id, $idOfHinhThucs);
            //             if ($index !== false) {
            //                 $hinhThucCoTinhHocPhi[] = $item->id;
            //             }
            //         }
            //         else if(strlen($idOfHinhThucs) == 1) {
            //             if($idOfHinhThucs == $feeHocPhi->id) {
            //                 $hinhThucCoTinhHocPhi[] = $item->id;
            //             }
            //         }
            //     }
            // }

            $hinhThucsIncludedKhauTruThang = [];
            foreach($hinhThucDiemDanhs as $hinh_thuc) {
                if($hinh_thuc->khau_tru_hoc_phi_thang) {
                    $hinhThucsIncludedKhauTruThang[] = $hinh_thuc->id;
                }
            }
            

            // TÍNH LẠI HỌC PHÍ HÀNG THÁNG Ở BACK-END
            $feeTienAn = 0;
            foreach($dailyFees as $item) {
                if($item['name'] == 'Tiền ăn') {
                    $feeTienAn = $item['cost'];
                }
            }

            // Trường hợp tính học phí theo ngày
            $feeHocPhi = 0;
            // foreach($dailyFees as $item) {
            //     if($item['name'] == 'Học phí') {
            //         $feeHocPhi = $item['cost'];
            //     }
            // }

            $hocPhiThangKhac = 0;
            foreach($monthlyFees as $item) {
                // Công thức cũ tính học phí và các học phí tháng khác khi học phí còn nằm trong danh mục học phí tháng
                if($item['name'] == 'Học phí') {
                    $feeHocPhi = $item['cost'];
                } else {
                    $hocPhiThangKhac += $item['cost'];
                }
            }

            $dateOfMonth = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
            $countDateInWeek = 0;
            for($i = 0; $i<$dateOfMonth; $i++) {
                $date = mktime (0, 0, 0, $request->month, $i+1 , $request->year);
                $dateInfo = getdate($date);
                if($dateInfo['weekday'] != 'Saturday' && $dateInfo['weekday'] != 'Sunday') {
                    $countDateInWeek++;
                }
            }

            if($request->month == 1) {
                $dateOfMonth = 31;
            } else {
                $dateOfMonth = cal_days_in_month(CAL_GREGORIAN, $request->month-1, $request->year);
            }
            
            $countDateInWeekLastMonth = 0;
            for($i = 0; $i<$dateOfMonth; $i++) {
                $date = mktime (0, 0, 0, $request->month-1, $i+1 , $request->year);
                $dateInfo = getdate($date);
                if($dateInfo['weekday'] != 'Saturday' && $dateInfo['weekday'] != 'Sunday') {
                    $countDateInWeekLastMonth++;
                }
            }

            // Tính tổng học phí của từng hình thức điểm danh và lọc hình thức khấu trừ học phí tháng
            $hinhThucsIncludedKhauTruThang = [];
            $arrayTongHocPHiTheoTungHinhThucDiemDanh = [];
            foreach($hinhThucDiemDanhs as $key => $item) {
                $idOfHinhThucs = [];
                if(strlen($item->daily) > 1) {
                    $idOfHinhThucs = explode(',', $item->daily);
                } else {
                    $idOfHinhThucs[] = $item->daily;
                }
                if($item->khau_tru_hoc_phi_thang) {
                    $hinhThucsIncludedKhauTruThang[] = $item->id;
                }
                $sumFee = 0;
                foreach($dailyFees as $dailyFee) {

                    $index = in_array($dailyFee->id, $idOfHinhThucs);
                    if($index !== false) {
                        $sumFee += $dailyFee->cost;
                    }
                }
                $arrayTongHocPHiTheoTungHinhThucDiemDanh[$item->id] = $sumFee;
            }

            // Tính học phí đầu năm
            $hocPhiDauNam = 0;
            foreach($annualFees as $item) {
                $hocPhiDauNam += $item["cost"];
            }

            // Chạy vòng lặp for từng học sinnh để tính output
            $dataResponse = [];
            for($i = 0; $i < count($students); $i++) {
                $item = $students[$i];
                $student_fee_i = [];

                $student_fee_i['ma_hs'] = $item->ma_hs;
                $student_fee_i['name'] = $item->name;
                $student_fee_i['note'] = $item->note;
                $student_fee_i['payment_date'] = $item->payment_date;
                $student_fee_i['address'] = $item->address;

                // Tính tiền ăn lớn nhất tháng này
                $student_fee_i['tien_an'] = $countDateInWeek*$feeTienAn;

                // Data điểm danh tháng trước
                if($request->month == 1) {
                    $dataDiemDanh = DiemDanh::where('ma_hs', $item->ma_hs)->where('month', 12)->where('year', $request->year-1)->get()->first();
                    $no_thang_truoc = StudentFee::where('ma_hs', $item->ma_hs)->where('month', 12)->where('year', $request->year - 1)->get()->first();
                } else {
                    $dataDiemDanh = DiemDanh::where('ma_hs', $item->ma_hs)->where('month', $request->month-1)->where('year', $request->year)->get()->first();
                    $no_thang_truoc = StudentFee::where('ma_hs', $item->ma_hs)->where('month', $request->month - 1)->where('year', $request->year)->get()->first();
                }
                
                // Nợ tháng trước của từng học sinh
                if($no_thang_truoc) {
                    $no_thang_truoc = $no_thang_truoc->data ? json_decode($no_thang_truoc->data, true)['con_no'] : 0;
                } else {
                    $no_thang_truoc = 0;
                }
                $student_fee_i['debt'] = $no_thang_truoc;
                
                // Đã thu và còn nợ tháng này của từng học sinh
                $paid = StudentFee::where('ma_hs', $item->ma_hs)->where('month', $request->month)->where('year', $request->year)->get()->first();
                if($paid) {
                    $daThu = $paid->data ? json_decode($paid->data, true)['paid'] : '';
                    $conNo = $paid->data ? json_decode($paid->data, true)['con_no'] : '';
                    $tamTinh = $paid->data ? json_decode($paid->data, true)['tam_tinh'] : '';
                } else {
                    $daThu = '';
                    $conNo = '';
                    $tamTinh = '';
                }
                $student_fee_i['paid'] = $daThu;
                $student_fee_i['con_no'] = $conNo;

                if(isset($dataDiemDanh->data)) {
                    $dataDiemDanhDecode = (array)json_decode($dataDiemDanh->data);
                    unset($dataDiemDanhDecode['ma_hs']);
                    $dataDiemDanhDecode = array_diff($dataDiemDanhDecode, [NULL]);
                    $countDataDiemDanhByType = array_count_values($dataDiemDanhDecode);
                } else {
                    $countDataDiemDanhByType = [];
                }

                // Tính tổng học phí theo ngày
                $hocPhiTheoNgay = 0;
                foreach($countDataDiemDanhByType as $hinh_thuc => $count) {
                    $hocPhiTheoNgay += $arrayTongHocPHiTheoTungHinhThucDiemDanh[$hinh_thuc]*$count;
                }

                // Tính tiền ăn tháng trước
                $soNgayAn = 0;
                foreach($hinhThucCoTinhTienAn as $hinh_thuc) {
                    if(array_key_exists($hinh_thuc, $countDataDiemDanhByType)) {
                        $soNgayAn += $countDataDiemDanhByType[$hinh_thuc];
                    }
                }

                // Tính số ngày học tháng trước (dùng cho cách tính học phí theo theo ngày)
                // $soNgayHoc = 0;
                // foreach($hinhThucCoTinhHocPhi as $hinh_thuc) {
                //     if(array_key_exists($hinh_thuc, $countDataDiemDanhByType)) {
                //         $soNgayHoc += $countDataDiemDanhByType[$hinh_thuc];
                //     }
                // }

                // Tính học phí ngày trừ tiền ăn học phí
                // $student_fee_i['daily_fees'] = $hocPhiTheoNgay - $soNgayAn*$feeTienAn - $soNgayHoc*$feeHocPhi;
                // Tính học phí theo ngày trừ tiền ăn, trường hợp học phí tính theo tháng
                $student_fee_i['daily_fees'] = $hocPhiTheoNgay - $soNgayAn*$feeTienAn;

                // Tính học phí tháng này
                // if($item->isIncludedAnnualFee) {
                //     $student_fee_i['hoc_phi'] = $feeHocPhi*$countDateInWeek;
                // } else {
                //     $student_fee_i['hoc_phi'] = intval(($countDateInWeek - $countDateInWeekLastMonth + $soNgayHoc)*$feeHocPhi);
                // }
                $student_fee_i["hoc_phi"] = $feeHocPhi;
                
                // Tính ngày nghỉ lễ tết tháng trước
                $countNghiLeTet = 0;
                foreach($hinhThucsIncludedKhauTruThang as $hinh_thuc) {
                    if(array_key_exists($hinh_thuc, $countDataDiemDanhByType)) {
                        $countNghiLeTet += $countDataDiemDanhByType[$hinh_thuc];
                    }
                }

                // $student_fee_i['hoc_phi'] = intval(($countDateInWeekLastMonth-$countNghiLeTet)/$countDateInWeekLastMonth*$feeHocPhi);

                // Tính học phí khác theo tháng có trừ những ngày nghỉ lễ
                // $student_fee_i['monthly_fees'] = intval($hocPhiThangKhac*($countDateInWeekLastMonth-$countNghiLeTet)/$countDateInWeekLastMonth);

                // Tính hóc phí khác theo tháng không trừ những ngày nghỉ lễ
                $student_fee_i['monthly_fees'] = intval($hocPhiThangKhac);

                // Tính học phí dịch vụ cộng thêm
                $hocPhiDichVuCongThem = 0;
                if($item->extra_services) {
                    $extra_services = json_decode($item->extra_services, true);
                    foreach($serviceFees as $service) {
                        if(array_key_exists($service['id'], $extra_services)) {
                            if($extra_services[$service['id']]) {
                                $hocPhiDichVuCongThem += $service['cost'];
                            }
                        }
                    }
                }

                // Tính khấu trừ tiền ăn
                $student_fee_i['khau_tru_tien_an'] = $countDateInWeekLastMonth*$feeTienAn - $soNgayAn*$feeTienAn;

                // Tính học phí đầu năm
                if($item->isIncludedAnnualFee) {
                    $student_fee_i['yearly_fees'] = $hocPhiDauNam;
                    $student_fee_i['khau_tru_tien_an'] = 0;
                } else {
                    $student_fee_i['yearly_fees'] = 0;
                }
                
                // Tính tiền dịch vụ cộng thêm có trừ lễ
                // $student_fee_i['service_fees'] = intval(($countDateInWeekLastMonth-$countNghiLeTet)/$countDateInWeekLastMonth*$hocPhiDichVuCongThem);
                // dịch vụ cộng thêm không trừ lễ
                $student_fee_i['service_fees'] = intval($hocPhiDichVuCongThem);

                // Tính tiến giảm trừ
                if($item->doi_tuong_uu_tien) {
                    foreach($doiTuongUuTiens as $doi_tuong) {
                        if($doi_tuong->id == $item->doi_tuong_uu_tien) {
                            if($doi_tuong->dinh_muc_giam != NULL && $doi_tuong->dinh_muc_giam != 0) {
                                $student_fee_i['family_allowances'] = intval(($student_fee_i['hoc_phi'] + $student_fee_i['tien_an'] + $student_fee_i['monthly_fees'] + $student_fee_i['daily_fees'] + $student_fee_i['service_fees'])*$doi_tuong->dinh_muc_giam/100);
                            } else {
                                $student_fee_i['family_allowances'] = $doi_tuong->dinh_muc_giam_tien;
                            }
                            break;
                        }
                    }
                } else {
                    $student_fee_i['family_allowances'] = 0;
                }

                // Tính số tiền phải thu
                $student_fee_i['tam_tinh'] = $tamTinh ? $tamTinh : $student_fee_i['hoc_phi'] + $student_fee_i['tien_an'] + $student_fee_i['monthly_fees'] + $student_fee_i['daily_fees'] + $student_fee_i['yearly_fees'] + $student_fee_i['service_fees'] - $student_fee_i['family_allowances'] + $student_fee_i['debt'] - $student_fee_i['khau_tru_tien_an'];
                // break;
                $dataResponse[] = $student_fee_i;
            }
            $data = [
                'status'    => true,
                'student_fees'  => $dataResponse
            ];
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   => $e
            ];
        }
        return response()->json($data);
    }

    public function hocPhiThangNay(Request $request) {
        try {
            // Khởi tạo biến lưu giá trị đầu vào
            $date = $request->month;
            $request->year = HelperController::format_month_year_helper($date)[1];
            $request->month = HelperController::format_month_year_helper($date)[0];
            $class = Classes::find($request->class);
            $id_coso = $class->id_coso;
            $grade = $class->grade;
            $school_year = DateService::defineSchoolYear($date)->id;

            // getDataStudents
            $students = Record::join('student_fee', 'enrollment_records.id', '=', 'student_fee.ma_hs')
            ->join('class', 'class.id', '=', 'enrollment_records.id_class')
            ->where('enrollment_records.status', '<=', 1)
            ->where('student_fee.month', $request->month)
            ->where('student_fee.year', $request->year)
            ->whereNull('enrollment_records.isDeleted')
            ->select(['enrollment_records.id as ma_hs', 'enrollment_records.address', 'enrollment_records.name as name', 'enrollment_records.doi_tuong_uu_tien as doi_tuong_uu_tien',
            'enrollment_records.extra_services', 'student_fee.data as data', 'student_fee.payment_date as payment_date', 'student_fee.note as note', 'student_fee.isIncludedAnnualFee as isIncludedAnnualFee'])->get();

            // // getDataAnnualFees
            // $annualFees = CostAnnualFeeAnnualFee::join('cost_annual_fee', 'cost_annual_fee_annual_fee.id_cost', '=', 'cost_annual_fee.id')->join('annual_fee', 'annual_fee.id', '=', 'cost_annual_fee_annual_fee.id_type')->where('cost_annual_fee.grade', $request->grade)->whereNull('cost_annual_fee.isDeleted')->whereNull('annual_fee.isDeleted')->select(['annual_fee.name', 'cost_annual_fee_annual_fee.cost'])->get();

            // // getDataMonthlyFees
            // $monthlyFees = CostMonthlyFeeMonthlyFee::join('cost_monthly_fee', 'cost_monthly_fee_monthly_fee.id_cost', '=', 'cost_monthly_fee.id')->join('monthly_fee', 'monthly_fee.id', '=', 'cost_monthly_fee_monthly_fee.id_type')->where('cost_monthly_fee.grade', $request->grade)->whereNull('cost_monthly_fee.isDeleted')->whereNull('monthly_fee.isDeleted')->select(['monthly_fee.id','monthly_fee.name', 'cost_monthly_fee_monthly_fee.cost'])->get();

            // // getDataDailyFees
            // $dailyFees = DailyFee::leftJoin('class_daily_fee', 'daily_fee.id', '=', 'class_daily_fee.id_type')
            // ->where('class_daily_fee.id_class', $request->class)
            // ->whereNull('isDeleted')->get();

            // // getDataServiceFees
            // $serviceFees = CostExtraServiceExtraService::join('cost_extra_service', 'cost_extra_service_extra_service.id_cost', '=', 'cost_extra_service.id')->join('extra_service', 'extra_service.id', '=', 'cost_extra_service_extra_service.id_type')->where('cost_extra_service.grade', $request->grade)->whereNull('cost_extra_service.isDeleted')->select(['extra_service.id', 'extra_service.name', 'cost_extra_service_extra_service.cost'])->get();

            // getDataAnnualFees
            $fees = CostAnnualFeeService::getData($id_coso, $grade, $school_year);
            $annualFees = count($fees) ? $fees[0]->fees : [];
            // getDataMonthlyFees
            $fees = CostMonthlyFeeService::getData($id_coso, $grade, $school_year);
            $monthlyFees = count($fees) ? $fees[0]->fees : [];

            // getDataDailyFees
            $dailyFees = DailyFee::leftJoin('class_daily_fee', 'daily_fee.id', '=', 'class_daily_fee.id_type')
            ->where('class_daily_fee.id_class', $request->class)->where('id_coso', $id_coso)
            ->whereNull('isDeleted')->get();

            // getDataServiceFees
            $fees = CostExtraServiceService::getData($id_coso, $grade, $school_year);
            $serviceFees = count($fees) ? $fees[0]->fees : [];

            // getDoiTuongUuTien
            $doiTuongUuTiens = Doituong::whereNull('isDeleted')->get();

            $hinhThucDiemDanhs = HinhThucDiemDanhService::getData($id_coso);

            $feeTienAn = DailyFee::where('name', 'Tiền ăn')->where('id_coso', $id_coso)->whereNull('isDeleted')->get()->first();
            $hinhThucCoTinhTienAn = [];
            if($feeTienAn) {
                foreach($hinhThucDiemDanhs as $key => $item) {
                    $idOfHinhThucs = '';
                    $idOfHinhThucs = $item->daily;
                    
                    if(strlen($idOfHinhThucs) > 1) {
                        $idOfHinhThucs = explode(',', $idOfHinhThucs);
                        $index = array_search($feeTienAn->id, $idOfHinhThucs);
                        if ($index !== false) {
                            $hinhThucCoTinhTienAn[] = $item->id;
                        }
                    }
                    else if(strlen($idOfHinhThucs) == 1) {
                        if($idOfHinhThucs == $feeTienAn->id) {
                            $hinhThucCoTinhTienAn[] = $item->id;
                        }
                    }
                }
            }

            // Tính học phí theo ngày và duyệt các hình thức điểm danh có học phí theo ngày để sau đó trừ đi
            $feeHocPhi = DailyFee::where('name', 'Học phí')->where('id_coso', $id_coso)->whereNull('isDeleted')->get()->first();
            if($feeTienAn) {
                foreach($hinhThucDiemDanhs as $key => $item) {
                    // $idOfHinhThucs = '';
                    $idOfHinhThucs = $item->daily;
                    
                    if(strlen($idOfHinhThucs) > 1) {
                        $idOfHinhThucs = explode(',', $idOfHinhThucs);
                        $index = array_search($feeTienAn->id, $idOfHinhThucs);
                        if ($index !== false) {
                            $hinhThucCoTinhHocPhi[] = $item->id;
                        }
                    }
                    else if(strlen($idOfHinhThucs) == 1) {
                        if($idOfHinhThucs == $feeTienAn->id) {
                            $hinhThucCoTinhHocPhi[] = $item->id;
                        }
                    }
                }
            }

            $hinhThucsIncludedKhauTruThang = [];
            foreach($hinhThucDiemDanhs as $hinh_thuc) {
                if($hinh_thuc->khau_tru_hoc_phi_thang) {
                    $hinhThucsIncludedKhauTruThang[] = $hinh_thuc->id;
                }
            }

            $feeTienAn = 0;
            foreach($dailyFees as $item) {
                if($item['name'] == 'Tiền ăn') {
                    $feeTienAn = $item['cost'];
                }
            }

            // Trường hợp tính học phí theo ngày
            $feeHocPhi = 0;
            // $hocPhiThangKhac = 0;
            // foreach($dailyFees as $item) {
            //     if($item['name'] == 'Học phí') {
            //         $feeHocPhi = $item['cost'];
            //     }
            // }

            // Trường hợp tính học phí theo tháng
            $hocPhiThangKhac = 0;
            foreach($monthlyFees as $item) {
                if($item['name'] == 'Học phí') {
                    $feeHocPhi = $item['cost'];
                } else {
                    $hocPhiThangKhac = $item['cost'];
                }
            }

            $dateOfMonth = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
            
            $countDateInWeek = 0;
            for($i = 0; $i<$dateOfMonth; $i++) {
                $date = mktime (0, 0, 0, $request->month, $i+1 , $request->year);
                $dateInfo = getdate($date);
                if($dateInfo['weekday'] != 'Saturday' && $dateInfo['weekday'] != 'Sunday') {
                    $countDateInWeek++;
                }
            }

            if($request->month == 1) {
                $dateOfMonth = 31;
            } else {
                $dateOfMonth = cal_days_in_month(CAL_GREGORIAN, $request->month-1, $request->year);
            }
            
            $countDateInWeekLastMonth = 0;
            for($i = 0; $i<$dateOfMonth; $i++) {
                $date = mktime (0, 0, 0, $request->month, $i+1 , $request->year);
                $dateInfo = getdate($date);
                if($dateInfo['weekday'] != 'Saturday' && $dateInfo['weekday'] != 'Sunday') {
                    $countDateInWeekLastMonth++;
                }
            }

            // Tính tổng học phí của từng hình thức điểm danh và lọc hình thức khấu trừ học phí tháng
            $hinhThucsIncludedKhauTruThang = [];
            $arrayTongHocPHiTheoTungHinhThucDiemDanh = [];
            foreach($hinhThucDiemDanhs as $key => $item) {
                $idOfHinhThucs = [];
                if(strlen($item->daily) > 1) {
                    $idOfHinhThucs = explode(',', $item->daily);
                } else {
                    $idOfHinhThucs[] = $item->daily;
                }
                if($item->khau_tru_hoc_phi_thang) {
                    $hinhThucsIncludedKhauTruThang[] = $item->id;
                }
                $sumFee = 0;
                foreach($dailyFees as $dailyFee) {

                    $index = in_array($dailyFee->id, $idOfHinhThucs);
                    if($index !== false) {
                        $sumFee += $dailyFee->cost;
                    }
                }
                $arrayTongHocPHiTheoTungHinhThucDiemDanh[$item->id] = $sumFee;
            }

            // Tính học phí đầu năm
            $hocPhiDauNam = 0;
            foreach($annualFees as $item) {
                $hocPhiDauNam += $item['cost'];
            }

            // Chạy vòng lặp for từng học sinh để tính output
            $dataResponse = [];
            for($i = 0; $i < count($students); $i++) {
                $item = $students[$i];
                $student_fee_i = [];

                $student_fee_i['ma_hs'] = $item->ma_hs;
                $student_fee_i['name'] = $item->name;
                $student_fee_i['note'] = $item->note;
                $student_fee_i['payment_date'] = $item->payment_date;
                $student_fee_i['address'] = $item->address;

                // Data điểm danh tháng này
                $dataDiemDanh = DiemDanh::where('ma_hs', $item->ma_hs)->where('month', $request->month)->where('year', $request->year)->get()->first();
                if(isset($dataDiemDanh->data)) {
                    $dataDiemDanhDecode = (array)json_decode($dataDiemDanh->data);
                    unset($dataDiemDanhDecode['ma_hs']);
                    $dataDiemDanhDecode = array_diff($dataDiemDanhDecode, [NULL]);
                    $countDataDiemDanhByType = array_count_values($dataDiemDanhDecode);
                } else {
                    $countDataDiemDanhByType = [];
                }

                // Data điểm danh tháng trước
                if($request->month == 1) {
                    $dataDiemDanhThangTruoc = DiemDanh::where('ma_hs', $item->ma_hs)->where('month', 12)->where('year', $request->year-1)->get()->first();
                    $no_thang_truoc = StudentFee::where('ma_hs', $item->ma_hs)->where('month', 12)->where('year', $request->year - 1)->get()->first();
                } else {
                    $dataDiemDanhThangTruoc = DiemDanh::where('ma_hs', $item->ma_hs)->where('month', $request->month-1)->where('year', $request->year)->get()->first();
                    $no_thang_truoc = StudentFee::where('ma_hs', $item->ma_hs)->where('month', $request->month - 1)->where('year', $request->year)->get()->first();
                }
                if(isset($dataDiemDanhThangTruoc->data)) {
                    $dataDiemDanhDecode = (array)json_decode($dataDiemDanhThangTruoc->data);
                    unset($dataDiemDanhDecode['ma_hs']);
                    $dataDiemDanhDecode = array_diff($dataDiemDanhDecode, [NULL]);
                    $countDataDiemDanhThangTruocByType = array_count_values($dataDiemDanhDecode);
                } else {
                    $countDataDiemDanhThangTruocByType = [];
                }

                // Số ngày học tháng này
                $soNgayHoc = 0;
                foreach($hinhThucCoTinhHocPhi as $hinh_thuc) {
                    if(array_key_exists($hinh_thuc, $countDataDiemDanhByType)) {
                        $soNgayHoc += $countDataDiemDanhByType[$hinh_thuc];
                    }
                }
                
                // Tiền ăn tháng này
                $soNgayAn = 0;
                
                foreach($hinhThucCoTinhTienAn as $hinh_thuc) {
                    if(array_key_exists($hinh_thuc, $countDataDiemDanhByType)) {
                        $soNgayAn += $countDataDiemDanhByType[$hinh_thuc];
                    }
                }
                $student_fee_i['tien_an'] = $soNgayAn*$feeTienAn;
                
                // Nợ tháng trước của từng học sinh
                if($no_thang_truoc) {
                    $no_thang_truoc = $no_thang_truoc->data ? json_decode($no_thang_truoc->data, true)['con_no'] : 0;
                } else {
                    $no_thang_truoc = 0;
                }
                $student_fee_i['debt'] = $no_thang_truoc;
                
                // Đã thu và còn nợ tháng này của từng học sinh
                $paid = StudentFee::where('ma_hs', $item->ma_hs)->where('month', $request->month)->where('year', $request->year)->get()->first();
                if($paid) {
                    $daThu = $paid->data ? json_decode($paid->data, true)['paid'] : 0;
                    // $conNo = $paid->data ? json_decode($paid->data, true)['con_no'] : '';
                } else {
                    $daThu = 0;
                    // $conNo = '';
                }
                $student_fee_i['paid'] = $daThu;
                // $student_fee_i['con_no'] = $conNo;

                // Tính tổng học phí theo ngày tháng trước và tháng này
                $hocPhiTheoNgay = 0;
                foreach($countDataDiemDanhThangTruocByType as $hinh_thuc => $count) {
                    $hocPhiTheoNgay += $arrayTongHocPHiTheoTungHinhThucDiemDanh[$hinh_thuc]*$count;
                }
                foreach($countDataDiemDanhByType as $hinh_thuc => $count) {
                    $hocPhiTheoNgay += $arrayTongHocPHiTheoTungHinhThucDiemDanh[$hinh_thuc]*$count;
                }

                // TRƯỜNG HỢP HỌC PHÍ TÍNH THEO NGÀY
                // Tính tiền học phí tháng trước
                // $soNgayHocThangTruoc = 0;
                // foreach($hinhThucCoTinhHocPhi as $hinh_thuc) {
                //     if(array_key_exists($hinh_thuc, $countDataDiemDanhThangTruocByType)) {
                //         $soNgayHocThangTruoc += $countDataDiemDanhThangTruocByType[$hinh_thuc];
                //     }
                // }

                // Tính tiền học phí tháng này bằng lấy số tiền học hiện tại tháng này trừ đi những ngày chưa học của tháng trước
                // if($item->isIncludedAnnualFee) {
                //     $student_fee_i['hoc_phi'] = intval(($soNgayHoc)*$feeHocPhi);
                // } else {
                //     $student_fee_i['hoc_phi'] = intval(($soNgayHoc - $countDateInWeekLastMonth + $soNgayHocThangTruoc)*$feeHocPhi);
                // }

                // TRƯỜNG HỢP HỌC PHÍ TÍNH THEO THÁNG
                // $student_fee_i["hoc_phi"] = intval($feeHocPhi*($countDateInWeek-$soNgayHoc)/$countDateInWeek);
                $student_fee_i["hoc_phi"] = intval($feeHocPhi*$soNgayHoc/$countDateInWeek);

                // Tính tiền ăn tháng trước
                $soNgayAnThangTruoc = 0;
                foreach($hinhThucCoTinhTienAn as $hinh_thuc) {
                    if(array_key_exists($hinh_thuc, $countDataDiemDanhThangTruocByType)) {
                        $soNgayAnThangTruoc += $countDataDiemDanhThangTruocByType[$hinh_thuc];
                    }
                }

                // Tính học phí ngày trừ tiền ăn và học phí trường hợp học phí tính theo ngày
                // $student_fee_i['daily_fees'] = $hocPhiTheoNgay - ($soNgayAn + $soNgayAnThangTruoc)*$feeTienAn - ($soNgayHoc + $soNgayHocThangTruoc)*$feeHocPhi;
                // Tính học phí theo ngày trừ tiền ăn, trường hợp học phí tính theo tháng
                $student_fee_i['daily_fees'] = $hocPhiTheoNgay - ($soNgayAn + $soNgayAnThangTruoc)*$feeTienAn;

                // Tính ngày nghỉ lễ tết tháng trước
                $countNghiLeTet = 0;
                foreach($hinhThucsIncludedKhauTruThang as $hinh_thuc) {
                    if(array_key_exists($hinh_thuc, $countDataDiemDanhThangTruocByType)) {
                        $countNghiLeTet += $countDataDiemDanhThangTruocByType[$hinh_thuc];
                    }
                }

                // Cách tính cũ khi học phí vẫn còn tính theo tháng
                // $student_fee_i['hoc_phi'] = intval(($soNgayAn/$countDateInWeek-$countNghiLeTet/$countDateInWeekLastMonth)*$feeHocPhi);

                // Tính học phí khác theo tháng
                // $student_fee_i['monthly_fees'] = intval($hocPhiThangKhac*($soNgayAn/$countDateInWeek-$countNghiLeTet/$countDateInWeekLastMonth));
                // cách tính thứ 2, học phí tháng không trừ nghỉ lễ của tháng trước nữa
                $student_fee_i['monthly_fees'] = intval($hocPhiThangKhac*($soNgayAn/$countDateInWeek));

                // Tính học phí dịch vụ cộng thêm
                $hocPhiDichVuCongThem = 0;
                if($item->extra_services) {
                    $extra_services = json_decode($item->extra_services, true);
                    foreach($serviceFees as $service) {
                        if(array_key_exists($service['id'], $extra_services)) {
                            if($extra_services[$service['id']]) {
                                $hocPhiDichVuCongThem += $service['cost'];
                            }
                        }
                    }
                }

                // Tính học phí đầu năm
                // Trong trường hợp đã học các tháng trước thì đã đóng học phí đầu năm rồi, còn mới học tháng đầu tiên thì tính bằng 0 để hoàn tiền lại
                $student_fee_i['yearly_fees'] = 0;

                // Tính dịch vụ cộng thêm có trừ lễ
                // $student_fee_i['service_fees'] = intval(($soNgayAn/$countDateInWeek-$countNghiLeTet/$countDateInWeekLastMonth)*$hocPhiDichVuCongThem);
                // Tính dịch vụ cộng thêm không trừ lễ
                $student_fee_i['service_fees'] = intval(($soNgayAn/$countDateInWeek)*$hocPhiDichVuCongThem);

                // Tính khấu trừ tiền ăn
                $student_fee_i['khau_tru_tien_an'] = $item->isIncludedAnnualFee == null ? $countDateInWeekLastMonth*$feeTienAn - $soNgayAnThangTruoc*$feeTienAn : 0;

                // Tính tiến giảm trừ
                if($item->doi_tuong_uu_tien) {
                    foreach($doiTuongUuTiens as $doi_tuong) {
                        if($doi_tuong->id == $item->doi_tuong_uu_tien) {
                            if($doi_tuong->dinh_muc_giam != NULL && $doi_tuong->dinh_muc_giam != 0) {
                                $student_fee_i['family_allowances'] = intval(($student_fee_i['hoc_phi'] + $student_fee_i['tien_an'] + $student_fee_i['monthly_fees'] + $student_fee_i['daily_fees'] + $student_fee_i['service_fees'])*$doi_tuong->dinh_muc_giam/100);
                            } else {
                                $student_fee_i['family_allowances'] = $doi_tuong->dinh_muc_giam_tien;
                            }
                            break;
                        }
                    }
                } else {
                    $student_fee_i['family_allowances'] = 0;
                }

                // Tính số tiền phải thu
                $student_fee_i['tam_tinh'] = $student_fee_i['hoc_phi'] + $student_fee_i['tien_an'] + $student_fee_i['monthly_fees'] + $student_fee_i['daily_fees'] + $student_fee_i['yearly_fees'] + $student_fee_i['service_fees'] - $student_fee_i['family_allowances'] + $student_fee_i['debt'] - $student_fee_i['khau_tru_tien_an'];

                if($student_fee_i['tam_tinh'] > $student_fee_i['paid']) {
                    $student_fee_i['thu_them'] = $student_fee_i['tam_tinh'] - $student_fee_i['paid'];
                    $student_fee_i['hoan_tien'] = '';
                } else {
                    $student_fee_i['hoan_tien'] = $student_fee_i['paid'] - $student_fee_i['tam_tinh'];
                    $student_fee_i['thu_them'] = '';
                }

                // break;
                $dataResponse[] = $student_fee_i;
            }
            $data = [
                'status'    => true,
                'student_fees'  => $dataResponse
            ];
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   => $e
            ];
        }
        return response()->json($data);
    }

    public function tinhHocPhi(Request $request) {
        try {
            $date = $request->month;
            $request->year = HelperController::format_month_year_helper($date)[1];
            $request->month = HelperController::format_month_year_helper($date)[0];
            $date1 = mktime(0, 0, 0, $request->month, 1, $request->year);
            $curMonth = date('m');
            $curYear = date('Y');
            $date2 = mktime(0, 0, 0, $curMonth, 1 ,$curYear);
            if($date2 >= $date1) {
                $students = Record::where('id_class', $request->class)
                ->where('status', 1)
                ->whereNull('isDeleted')
                ->get();
                foreach($students as $item) {
                    $isIncludedAnnualFee = [];
                    if($request->month < 6) {
                        $isIncludedAnnualFee = StudentFee::where('ma_hs', $item->id)
                        ->where(function ($q) use ($request) {
                            $q->where(function ($q1) use ($request) {
                                $q1->where('year', $request->year-1)->where('month', '>', 6);
                            })
                            ->orWhere(function($q1) use ($request) {
                                $q1->where('year', $request->year)->where('month', '<', 6);
                            });
                        })
                        ->where('isIncludedAnnualFee', 1)
                        ->get()->first();
                    } else {
                        $isIncludedAnnualFee = StudentFee::where('ma_hs', $item->id)->where('year', $request->year)->where('month','>', 6)
                        ->where('isIncludedAnnualFee', 1)
                        ->get()->first();
                    }
                    $student_fee = StudentFee::where('ma_hs', $item->id)
                    ->where('year', $request->year)
                    ->where('month', $request->month)
                    ->get()->first();
                    if(!$student_fee) {
                        $student_fee = new StudentFee();
                        $student_fee->ma_hs = $item->id;
                        $student_fee->year = $request->year;
                        $student_fee->month = $request->month;
                        if(!$isIncludedAnnualFee) {
                            $student_fee->isIncludedAnnualFee = 1;
                        }
                        $student_fee->save();
                    }
                }
                $data = [
                    'status'    =>  true,
                    'message'   =>  'Tạo dữ liệu thành công'
                ];
            } else {
                $data = [
                    'status'    =>  true,
                    'message'   =>  'Chưa đến kỳ thanh toán mới'
                ];
            }
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   => $e
            ];
        }
        return response()->json($data);
    }

    public function dongHocPhi(Request $request) {
        try {
            $date = $request->month;
            $request->year = HelperController::format_month_year_helper($date)[1];
            $request->month = HelperController::format_month_year_helper($date)[0];
            $class = Classes::find($request->class);
            $student_fee = StudentFee::where('ma_hs', $request->ma_hs)->where('month', $request->month)->where('year', $request->year)->get()->first();
            $old_data_student_fee = json_decode($student_fee->data);
            if(isset($old_data_student_fee->paid)) {
                $data_student_fee = [
                    'tam_tinh' => $request->tam_tinh,
                    'paid' => $request->paid + $old_data_student_fee->paid,
                    'con_no' => $request->tam_tinh - $request->paid - $old_data_student_fee->paid
                ];
            } else {
                $data_student_fee = [
                    'tam_tinh' => $request->tam_tinh,
                    'paid' => $request->paid,
                    'con_no' => $request->tam_tinh - $request->paid
                ];
            }
            if($data_student_fee['tam_tinh'] < $data_student_fee['paid'] || !is_numeric($data_student_fee['paid'])) {
                $data = [
                    'status' => false,
                    'message'   =>  "Số tiền không hợp lệ"
                ];
                return response()->json($data);
            }
            $data_student_fee_json_encode = json_encode($data_student_fee);
            $student_fee->data = $data_student_fee_json_encode;
            if($student_fee->payment_date == null) {
                $student_fee->payment_date = date('Y-m-d');
            }
            $student_fee->save();
            $quan_ly_thu = QuanLyThu::where('ma_khach_hang', $request->ma_hs)->where('month', $request->month)->where('year', $request->year)->get()->first();
            if(!$quan_ly_thu) {
                $record = Record::find($request->ma_hs);
                $quan_ly_thu = new QuanLyThu();
                $quan_ly_thu->stt = QuanLyThuController::getSTT()+1;
                $quan_ly_thu->so_chung_tu = QuanLyThuController::generateSoChungTu();
                $quan_ly_thu->ngay_chung_tu = date('Y-m-d');
                $quan_ly_thu->month = $request->month;
                $quan_ly_thu->year = $request->year;
                $quan_ly_thu->isFromStudent = 1;
                $quan_ly_thu->ma_khach_hang = $request->ma_hs;
                $quan_ly_thu->ten_khach_hang = $record->name;
                $quan_ly_thu->dia_chi = $record->address;
                $quan_ly_thu->noi_dung_phieu_thu = 'Đóng học phí tháng '.$request->month.'/'.$request->year;
                $quan_ly_thu->ghi_chu = '';
                $quan_ly_thu->thanh_tien = $data_student_fee['tam_tinh'];
                $quan_ly_thu->da_thu = $data_student_fee['paid'];
                $quan_ly_thu->con_no = $data_student_fee['con_no'];
                if($quan_ly_thu->thanh_tien <= $quan_ly_thu->da_thu) {
                    $quan_ly_thu->isPaid = 1;
                }
                $quan_ly_thu->id_coso = $request->id_coso;
                $quan_ly_thu->save();
                $log_quan_ly_thu = new LogQuanLyThu();
                $log_quan_ly_thu->phieu_thu_id = $quan_ly_thu->id;
                $log_quan_ly_thu->user_id = Auth::id();
                $log_quan_ly_thu->so_lan = 1;
                $log_quan_ly_thu->save();
            } else {
                $quan_ly_thu->thanh_tien = $data_student_fee['tam_tinh'];
                $quan_ly_thu->da_thu = $data_student_fee['paid'];
                $quan_ly_thu->con_no = $data_student_fee['con_no'];
                if($quan_ly_thu->thanh_tien <= $quan_ly_thu->da_thu) {
                    $quan_ly_thu->isPaid = 1;
                }
                $quan_ly_thu->id_coso = $request->id_coso;
                $quan_ly_thu->save();
                $log_quan_ly_thu = LogQuanLyThu::where('phieu_thu_id', $quan_ly_thu->id)->orderBy('so_lan', 'desc')->get()->first();
                $log_quan_ly_thu_new = new LogQuanLyThu();
                $log_quan_ly_thu_new->phieu_thu_id = $quan_ly_thu->id;
                $log_quan_ly_thu_new->user_id = Auth::id();
                $log_quan_ly_thu_new->so_lan = $log_quan_ly_thu->so_lan+1;
                $log_quan_ly_thu_new->save();
            }

            $data = [
                'status' => true
            ];
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   => $e
            ];
        }
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
