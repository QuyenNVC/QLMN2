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
            // getDataStudents
            $students = Record::join('student_fee', 'enrollment_records.id', '=', 'student_fee.ma_hs')
            ->join('class', 'class.id', '=', 'enrollment_records.id_class')
            ->where('enrollment_records.status', '<=', 1)
            ->where('class.school_year', $request->school_year)
            ->where('student_fee.month', $request->month)
            ->where('student_fee.year', $request->year)
            ->whereNull('enrollment_records.isDeleted')
            ->select(['enrollment_records.id as ma_hs', 'enrollment_records.address', 'enrollment_records.name as name', 'enrollment_records.doi_tuong_uu_tien as doi_tuong_uu_tien',
            'enrollment_records.extra_services', 'student_fee.data as data', 'student_fee.payment_date as payment_date', 'student_fee.note as note', 'student_fee.isIncludedAnnualFee as isIncludedAnnualFee'])->get();

            // getDataAnnualFees
            $annualFees = CostAnnualFeeAnnualFee::join('cost_annual_fee', 'cost_annual_fee_annual_fee.id_cost', '=', 'cost_annual_fee.id')->join('annual_fee', 'annual_fee.id', '=', 'cost_annual_fee_annual_fee.id_type')->where('cost_annual_fee.grade', $request->grade)->whereNull('cost_annual_fee.isDeleted')->whereNull('annual_fee.isDeleted')->select(['annual_fee.name', 'cost_annual_fee_annual_fee.cost'])->get();

            // getDataMonthlyFees
            $monthlyFees = CostMonthlyFeeMonthlyFee::join('cost_monthly_fee', 'cost_monthly_fee_monthly_fee.id_cost', '=', 'cost_monthly_fee.id')->join('monthly_fee', 'monthly_fee.id', '=', 'cost_monthly_fee_monthly_fee.id_type')->where('cost_monthly_fee.grade', $request->grade)->whereNull('cost_monthly_fee.isDeleted')->whereNull('monthly_fee.isDeleted')->select(['monthly_fee.name', 'cost_monthly_fee_monthly_fee.cost'])->get();

            // getDataDailyFees
            $dailyFees = DailyFee::leftJoin('class_daily_fee', 'daily_fee.id', '=', 'class_daily_fee.id_type')
            ->where('class_daily_fee.id_class', $request->class)
            ->whereNull('isDeleted')->get();

            // getDataServiceFees
            $serviceFees = CostExtraServiceExtraService::join('cost_extra_service', 'cost_extra_service_extra_service.id_cost', '=', 'cost_extra_service.id')->join('extra_service', 'extra_service.id', '=', 'cost_extra_service_extra_service.id_type')->where('cost_extra_service.grade', $request->grade)->whereNull('cost_extra_service.isDeleted')->select(['extra_service.id', 'extra_service.name', 'cost_extra_service_extra_service.cost'])->get();

            // getDoiTuongUuTien
            $doiTuongUuTiens = Doituong::whereNull('isDeleted')->get();

            $hinhThucDiemDanhs = HinhThucDiemDanh::whereNull('isDeleted')->get();

            $feeTienAn = DailyFee::where('name', 'Su???t ??n')->get()->first();
            if($feeTienAn) {
                $hinhThucCoTinhTienAn = [];
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
                // if($hinhThucCoTinhTienAn) {
                //     $data['hinh_thuc_co_tinh_tien_an'] = $hinhThucCoTinhTienAn;
                // }
            }

            $feeHocPhi = DailyFee::where('name', 'H???c ph??')->get()->first();
            if($feeHocPhi) {
                $hinhThucCoTinhHocPhi = [];
                foreach($hinhThucDiemDanhs as $key => $item) {
                    $idOfHinhThucs = '';
                    $idOfHinhThucs = $item->daily;
                    
                    if(strlen($idOfHinhThucs) > 1) {
                        $idOfHinhThucs = explode(',', $idOfHinhThucs);
                        $index = array_search($feeHocPhi->id, $idOfHinhThucs);
                        if ($index !== false) {
                            $hinhThucCoTinhHocPhi[] = $item->id;
                        }
                    }
                    else if(strlen($idOfHinhThucs) == 1) {
                        if($idOfHinhThucs == $feeHocPhi->id) {
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
            // var_dump($hinhThucsIncludedKhauTruThang);
            

            // T??NH L???I H???C PH?? H??NG TH??NG ??? BACK-END
            $feeTienAn = 0;
            foreach($dailyFees as $item) {
                if($item['name'] == 'Su???t ??n') {
                    $feeTienAn = $item['cost'];
                }
            }

            $feeHocPhi = 0;
            foreach($dailyFees as $item) {
                if($item['name'] == 'H???c ph??') {
                    $feeHocPhi = $item['cost'];
                }
            }

            $hocPhiThangKhac = 0;
            foreach($monthlyFees as $item) {
                // C??ng th???c c?? t??nh h???c ph?? v?? c??c h???c ph?? th??ng kh??c khi h???c ph?? c??n n???m trong danh m???c h???c ph?? th??ng
                // if($item['name'] == 'H???c ph?? theo th??ng') {
                //     $feeHocPhi = $item['cost'];
                // } else {
                //     $hocPhiThangKhac += $item['cost'];
                // }

                $hocPhiThangKhac += $item['cost'];
            }

            $dateOfMonth = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
            $countDateInWeek = 0;
            for($i = 0; $i<$dateOfMonth; $i++) {
                $date = mktime (0, 0, 0, $request->month, $i , $request->year);
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
                $date = mktime (0, 0, 0, $request->month-1, $i , $request->year);
                $dateInfo = getdate($date);
                if($dateInfo['weekday'] != 'Saturday' && $dateInfo['weekday'] != 'Sunday') {
                    $countDateInWeekLastMonth++;
                }
            }

            // T??nh t???ng h???c ph?? c???a t???ng h??nh th???c ??i???m danh v?? l???c h??nh th???c kh???u tr??? h???c ph?? th??ng
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
                // var_dump($idOfHinhThucs); echo "<br>";
                foreach($dailyFees as $dailyFee) {

                    $index = in_array($dailyFee->id, $idOfHinhThucs);
                    // var_dump($index);
                    if($index !== false) {
                        $sumFee += $dailyFee->cost;
                    }
                }
                $arrayTongHocPHiTheoTungHinhThucDiemDanh[$item->id] = $sumFee;
            }
            // var_dump($arrayTongHocPHiTheoTungHinhThucDiemDanh);

            // T??nh h???c ph?? ?????u n??m
            $hocPhiDauNam = 0;
            foreach($annualFees as $item) {
                $hocPhiDauNam += $item->cost;
            }

            // Ch???y v??ng l???p for t???ng h???c sinnh ????? t??nh output
            $dataResponse = [];
            for($i = 0; $i < count($students); $i++) {
                $item = $students[$i];
                $student_fee_i = [];

                $student_fee_i['ma_hs'] = $item->ma_hs;
                $student_fee_i['name'] = $item->name;
                $student_fee_i['note'] = $item->note;
                $student_fee_i['payment_date'] = $item->payment_date;
                $student_fee_i['address'] = $item->address;

                // T??nh ti???n ??n l???n nh???t th??ng n??y
                $student_fee_i['tien_an'] = $countDateInWeek*$feeTienAn;


                if($request->month == 1) {
                    $dataDiemDanh = DiemDanh::where('ma_hs', $item->ma_hs)->where('month', 12)->where('year', $request->year-1)->get()->first();
                    $no_thang_truoc = StudentFee::where('ma_hs', $item->ma_hs)->where('month', 12)->where('year', $request->year - 1)->get()->first();
                } else {
                    $dataDiemDanh = DiemDanh::where('ma_hs', $item->ma_hs)->where('month', $request->month-1)->where('year', $request->year)->get()->first();
                    $no_thang_truoc = StudentFee::where('ma_hs', $item->ma_hs)->where('month', $request->month - 1)->where('year', $request->year)->get()->first();
                }
                
                // N??? th??ng tr?????c c???a t???ng h???c sinh
                if($no_thang_truoc) {
                    $no_thang_truoc = $no_thang_truoc->data ? json_decode($no_thang_truoc->data, true)['con_no'] : 0;
                } else {
                    $no_thang_truoc = 0;
                }
                $student_fee_i['debt'] = $no_thang_truoc;
                
                // ???? thu v?? c??n n??? th??ng n??y c???a t???ng h???c sinh
                $paid = StudentFee::where('ma_hs', $item->ma_hs)->where('month', $request->month)->where('year', $request->year)->get()->first();
                if($paid) {
                    $daThu = $paid->data ? json_decode($paid->data, true)['paid'] : '';
                    $conNo = $paid->data ? json_decode($paid->data, true)['con_no'] : '';
                } else {
                    $daThu = '';
                    $conNo = '';
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

                // T??nh t???ng h???c ph?? theo ng??y
                $hocPhiTheoNgay = 0;
                foreach($countDataDiemDanhByType as $hinh_thuc => $count) {
                    $hocPhiTheoNgay += $arrayTongHocPHiTheoTungHinhThucDiemDanh[$hinh_thuc]*$count;
                }

                // T??nh ti???n ??n th??ng tr?????c
                $soNgayAn = 0;
                foreach($hinhThucCoTinhTienAn as $hinh_thuc) {
                    if(array_key_exists($hinh_thuc, $countDataDiemDanhByType)) {
                        $soNgayAn += $countDataDiemDanhByType[$hinh_thuc];
                    }
                }

                // T??nh s??? ng??y h???c th??ng tr?????c
                $soNgayHoc = 0;
                foreach($hinhThucCoTinhHocPhi as $hinh_thuc) {
                    if(array_key_exists($hinh_thuc, $countDataDiemDanhByType)) {
                        $soNgayHoc += $countDataDiemDanhByType[$hinh_thuc];
                    }
                }

                // T??nh h???c ph?? ng??y tr??? ti???n ??n h???c ph??
                $student_fee_i['daily_fees'] = $hocPhiTheoNgay - $soNgayAn*$feeTienAn - $soNgayHoc*$feeHocPhi;

                // T??nh h???c ph?? th??ng n??y
                if($item->isIncludedAnnualFee) {
                    $student_fee_i['hoc_phi'] = $feeHocPhi*$countDateInWeek;
                } else {
                    $student_fee_i['hoc_phi'] = intval(($countDateInWeek - $countDateInWeekLastMonth + $soNgayHoc)*$feeHocPhi);
                }
                
                // T??nh ng??y ngh??? l??? t???t th??ng tr?????c
                $countNghiLeTet = 0;
                foreach($hinhThucsIncludedKhauTruThang as $hinh_thuc) {
                    if(array_key_exists($hinh_thuc, $countDataDiemDanhByType)) {
                        $countNghiLeTet += $countDataDiemDanhByType[$hinh_thuc];
                    }
                }

                // $student_fee_i['hoc_phi'] = intval(($countDateInWeekLastMonth-$countNghiLeTet)/$countDateInWeekLastMonth*$feeHocPhi);

                // T??nh h???c ph?? kh??c theo th??ng
                $student_fee_i['monthly_fees'] = intval($hocPhiThangKhac*($countDateInWeekLastMonth-$countNghiLeTet)/$countDateInWeekLastMonth);

                // T??nh h???c ph?? d???ch v??? c???ng th??m
                $hocPhiDichVuCongThem = 0;
                if($item->extra_services) {
                    $extra_services = json_decode($item->extra_services, true);
                    foreach($serviceFees as $service) {
                        if(array_key_exists($service->id, $extra_services)) {
                            if($extra_services[$service->id]) {
                                $hocPhiDichVuCongThem += $service->cost;
                            }
                        }
                    }
                }

                // T??nh kh???u tr??? ti???n ??n
                $student_fee_i['khau_tru_tien_an'] = $countDateInWeekLastMonth*$feeTienAn - $soNgayAn*$feeTienAn;

                // T??nh h???c ph?? ?????u n??m
                if($item->isIncludedAnnualFee) {
                    $student_fee_i['yearly_fees'] = $hocPhiDauNam;
                    $student_fee_i['khau_tru_tien_an'] = 0;
                } else {
                    $student_fee_i['yearly_fees'] = 0;
                }
                
                // T??nh ti???n d???ch v??? c???ng th??m
                $student_fee_i['service_fees'] = intval(($countDateInWeekLastMonth-$countNghiLeTet)/$countDateInWeekLastMonth*$hocPhiDichVuCongThem);

                // T??nh ti???n gi???m tr???
                if($item->doi_tuong_uu_tien) {
                    foreach($doiTuongUuTiens as $doi_tuong) {
                        if($doi_tuong->id == $item->doi_tuong_uu_tien) {
                            // var_dump($doi_tuong.dinh_muc_giam);
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

                // T??nh s??? ti???n ph???i thu
                $student_fee_i['tam_tinh'] = $student_fee_i['hoc_phi'] + $student_fee_i['tien_an'] + $student_fee_i['monthly_fees'] + $student_fee_i['daily_fees'] + $student_fee_i['yearly_fees'] + $student_fee_i['service_fees'] - $student_fee_i['family_allowances'] + $student_fee_i['debt'] - $student_fee_i['khau_tru_tien_an'];

                // var_dump($hocPhiTheoNgay);
                // var_dump($student_fee_i);
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
            // getDataStudents
            $students = Record::join('student_fee', 'enrollment_records.id', '=', 'student_fee.ma_hs')
            ->join('class', 'class.id', '=', 'enrollment_records.id_class')
            ->where('enrollment_records.status', '<=', 1)
            ->where('class.school_year', $request->school_year)
            ->where('student_fee.month', $request->month)
            ->where('student_fee.year', $request->year)
            ->whereNull('enrollment_records.isDeleted')
            ->select(['enrollment_records.id as ma_hs', 'enrollment_records.address', 'enrollment_records.name as name', 'enrollment_records.doi_tuong_uu_tien as doi_tuong_uu_tien',
            'enrollment_records.extra_services', 'student_fee.data as data', 'student_fee.payment_date as payment_date', 'student_fee.note as note', 'student_fee.isIncludedAnnualFee as isIncludedAnnualFee'])->get();

            // getDataAnnualFees
            $annualFees = CostAnnualFeeAnnualFee::join('cost_annual_fee', 'cost_annual_fee_annual_fee.id_cost', '=', 'cost_annual_fee.id')->join('annual_fee', 'annual_fee.id', '=', 'cost_annual_fee_annual_fee.id_type')->where('cost_annual_fee.grade', $request->grade)->whereNull('cost_annual_fee.isDeleted')->whereNull('annual_fee.isDeleted')->select(['annual_fee.name', 'cost_annual_fee_annual_fee.cost'])->get();

            // getDataMonthlyFees
            $monthlyFees = CostMonthlyFeeMonthlyFee::join('cost_monthly_fee', 'cost_monthly_fee_monthly_fee.id_cost', '=', 'cost_monthly_fee.id')->join('monthly_fee', 'monthly_fee.id', '=', 'cost_monthly_fee_monthly_fee.id_type')->where('cost_monthly_fee.grade', $request->grade)->whereNull('cost_monthly_fee.isDeleted')->whereNull('monthly_fee.isDeleted')->select(['monthly_fee.name', 'cost_monthly_fee_monthly_fee.cost'])->get();

            // getDataDailyFees
            $dailyFees = DailyFee::leftJoin('class_daily_fee', 'daily_fee.id', '=', 'class_daily_fee.id_type')
            ->where('class_daily_fee.id_class', $request->class)
            ->whereNull('isDeleted')->get();

            // getDataServiceFees
            $serviceFees = CostExtraServiceExtraService::join('cost_extra_service', 'cost_extra_service_extra_service.id_cost', '=', 'cost_extra_service.id')->join('extra_service', 'extra_service.id', '=', 'cost_extra_service_extra_service.id_type')->where('cost_extra_service.grade', $request->grade)->whereNull('cost_extra_service.isDeleted')->select(['extra_service.id', 'extra_service.name', 'cost_extra_service_extra_service.cost'])->get();

            // getDoiTuongUuTien
            $doiTuongUuTiens = Doituong::whereNull('isDeleted')->get();

            $hinhThucDiemDanhs = HinhThucDiemDanh::whereNull('isDeleted')->get();

            $feeTienAn = DailyFee::where('name', 'Su???t ??n')->get()->first();
            if($feeTienAn) {
                $hinhThucCoTinhTienAn = [];
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

            $feeHocPhi = DailyFee::where('name', 'H???c ph??')->get()->first();
            if($feeHocPhi) {
                $hinhThucCoTinhHocPhi = [];
                foreach($hinhThucDiemDanhs as $key => $item) {
                    $idOfHinhThucs = '';
                    $idOfHinhThucs = $item->daily;
                    
                    if(strlen($idOfHinhThucs) > 1) {
                        $idOfHinhThucs = explode(',', $idOfHinhThucs);
                        $index = array_search($feeHocPhi->id, $idOfHinhThucs);
                        if ($index !== false) {
                            $hinhThucCoTinhHocPhi[] = $item->id;
                        }
                    }
                    else if(strlen($idOfHinhThucs) == 1) {
                        if($idOfHinhThucs == $feeHocPhi->id) {
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
                if($item['name'] == 'Su???t ??n') {
                    $feeTienAn = $item['cost'];
                }
            }

            $feeHocPhi = 0;
            $hocPhiThangKhac = 0;
            foreach($dailyFees as $item) {
                if($item['name'] == 'H???c ph??') {
                    $feeHocPhi = $item['cost'];
                }
            }

            // $feeHocPhi = 0;
            // $hocPhiThangKhac = 0;
            // foreach($monthlyFees as $item) {
            //     if($item['name'] == 'H???c ph?? theo th??ng') {
            //         $feeHocPhi = $item['cost'];
            //     } else {
            //         $hocPhiThangKhac = $item['cost'];
            //     }
            // }

            $dateOfMonth = cal_days_in_month(CAL_GREGORIAN, $request->month, $request->year);
            $countDateInWeek = 0;
            for($i = 0; $i<$dateOfMonth; $i++) {
                $date = mktime (0, 0, 0, $request->month, $i , $request->year);
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
                $date = mktime (0, 0, 0, $request->month-1, $i , $request->year);
                $dateInfo = getdate($date);
                if($dateInfo['weekday'] != 'Saturday' && $dateInfo['weekday'] != 'Sunday') {
                    $countDateInWeekLastMonth++;
                }
            }

            // T??nh t???ng h???c ph?? c???a t???ng h??nh th???c ??i???m danh v?? l???c h??nh th???c kh???u tr??? h???c ph?? th??ng
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
                // var_dump($idOfHinhThucs); echo "<br>";
                foreach($dailyFees as $dailyFee) {

                    $index = in_array($dailyFee->id, $idOfHinhThucs);
                    // var_dump($index);
                    if($index !== false) {
                        $sumFee += $dailyFee->cost;
                    }
                }
                $arrayTongHocPHiTheoTungHinhThucDiemDanh[$item->id] = $sumFee;
            }

            // T??nh h???c ph?? ?????u n??m
            $hocPhiDauNam = 0;
            foreach($annualFees as $item) {
                $hocPhiDauNam += $item->cost;
            }

            // Ch???y v??ng l???p for t???ng h???c sinh ????? t??nh output
            $dataResponse = [];
            for($i = 0; $i < count($students); $i++) {
                $item = $students[$i];
                $student_fee_i = [];

                $student_fee_i['ma_hs'] = $item->ma_hs;
                $student_fee_i['name'] = $item->name;
                $student_fee_i['note'] = $item->note;
                $student_fee_i['payment_date'] = $item->payment_date;
                $student_fee_i['address'] = $item->address;

                // Data ??i???m danh th??ng n??y
                $dataDiemDanh = DiemDanh::where('ma_hs', $item->ma_hs)->where('month', $request->month)->where('year', $request->year)->get()->first();
                if(isset($dataDiemDanh->data)) {
                    $dataDiemDanhDecode = (array)json_decode($dataDiemDanh->data);
                    unset($dataDiemDanhDecode['ma_hs']);
                    $dataDiemDanhDecode = array_diff($dataDiemDanhDecode, [NULL]);
                    $countDataDiemDanhByType = array_count_values($dataDiemDanhDecode);
                } else {
                    $countDataDiemDanhByType = [];
                }

                // Data ??i???m danh th??ng tr?????c
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

                // S??? ng??y h???c th??ng n??y
                $soNgayHoc = 0;
                foreach($hinhThucCoTinhHocPhi as $hinh_thuc) {
                    if(array_key_exists($hinh_thuc, $countDataDiemDanhByType)) {
                        $soNgayHoc += $countDataDiemDanhByType[$hinh_thuc];
                    }
                }
                
                // Ti???n ??n th??ng n??y
                $soNgayAn = 0;
                foreach($hinhThucCoTinhTienAn as $hinh_thuc) {
                    if(array_key_exists($hinh_thuc, $countDataDiemDanhByType)) {
                        $soNgayAn += $countDataDiemDanhByType[$hinh_thuc];
                    }
                }
                $student_fee_i['tien_an'] = $soNgayAn*$feeTienAn;

                // N??? th??ng tr?????c c???a t???ng h???c sinh
                if($no_thang_truoc) {
                    $no_thang_truoc = $no_thang_truoc->data ? json_decode($no_thang_truoc->data, true)['con_no'] : 0;
                } else {
                    $no_thang_truoc = 0;
                }
                $student_fee_i['debt'] = $no_thang_truoc;
                
                // ???? thu v?? c??n n??? th??ng n??y c???a t???ng h???c sinh
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

                // T??nh t???ng h???c ph?? theo ng??y th??ng tr?????c v?? th??ng n??y
                $hocPhiTheoNgay = 0;
                foreach($countDataDiemDanhThangTruocByType as $hinh_thuc => $count) {
                    $hocPhiTheoNgay += $arrayTongHocPHiTheoTungHinhThucDiemDanh[$hinh_thuc]*$count;
                }
                foreach($countDataDiemDanhByType as $hinh_thuc => $count) {
                    $hocPhiTheoNgay += $arrayTongHocPHiTheoTungHinhThucDiemDanh[$hinh_thuc]*$count;
                }

                // T??nh ti???n h???c ph?? th??ng tr?????c
                $soNgayHocThangTruoc = 0;
                foreach($hinhThucCoTinhHocPhi as $hinh_thuc) {
                    if(array_key_exists($hinh_thuc, $countDataDiemDanhThangTruocByType)) {
                        $soNgayHocThangTruoc += $countDataDiemDanhThangTruocByType[$hinh_thuc];
                    }
                }

                // T??nh ti???n h???c ph?? th??ng n??y b???ng l???y s??? ti???n h???c hi???n t???i th??ng n??y tr??? ??i nh???ng ng??y ch??a h???c c???a th??ng tr?????c
                if($item->isIncludedAnnualFee) {
                    $student_fee_i['hoc_phi'] = intval(($soNgayHoc)*$feeHocPhi);
                } else {
                    $student_fee_i['hoc_phi'] = intval(($soNgayHoc - $countDateInWeekLastMonth + $soNgayHocThangTruoc)*$feeHocPhi);
                }

                // T??nh ti???n ??n th??ng tr?????c
                $soNgayAnThangTruoc = 0;
                foreach($hinhThucCoTinhTienAn as $hinh_thuc) {
                    if(array_key_exists($hinh_thuc, $countDataDiemDanhThangTruocByType)) {
                        $soNgayAnThangTruoc += $countDataDiemDanhThangTruocByType[$hinh_thuc];
                    }
                }

                // T??nh h???c ph?? ng??y tr??? ti???n ??n v?? h???c ph??
                $student_fee_i['daily_fees'] = $hocPhiTheoNgay - ($soNgayAn + $soNgayAnThangTruoc)*$feeTienAn - ($soNgayHoc + $soNgayHocThangTruoc)*$feeHocPhi;

                // T??nh ng??y ngh??? l??? t???t th??ng tr?????c
                $countNghiLeTet = 0;
                foreach($hinhThucsIncludedKhauTruThang as $hinh_thuc) {
                    if(array_key_exists($hinh_thuc, $countDataDiemDanhThangTruocByType)) {
                        $countNghiLeTet += $countDataDiemDanhThangTruocByType[$hinh_thuc];
                    }
                }

                // C??ch t??nh c?? khi h???c ph?? v???n c??n t??nh theo th??ng
                // $student_fee_i['hoc_phi'] = intval(($soNgayAn/$countDateInWeek-$countNghiLeTet/$countDateInWeekLastMonth)*$feeHocPhi);

                // T??nh h???c ph?? kh??c theo th??ng
                $student_fee_i['monthly_fees'] = intval($hocPhiThangKhac*($soNgayAn/$countDateInWeek-$countNghiLeTet/$countDateInWeekLastMonth));

                // T??nh h???c ph?? d???ch v??? c???ng th??m
                $hocPhiDichVuCongThem = 0;
                if($item->extra_services) {
                    $extra_services = json_decode($item->extra_services, true);
                    foreach($serviceFees as $service) {
                        if(array_key_exists($service->id, $extra_services)) {
                            if($extra_services[$service->id]) {
                                $hocPhiDichVuCongThem += $service->cost;
                            }
                        }
                    }
                }

                // T??nh h???c ph?? ?????u n??m
                // Trong tr?????ng h???p ???? h???c c??c th??ng tr?????c th?? ???? ????ng h???c ph?? ?????u n??m r???i, c??n m???i h???c th??ng ?????u ti??n th?? t??nh b???ng 0 ????? ho??n ti???n l???i
                $student_fee_i['yearly_fees'] = 0;

                $student_fee_i['service_fees'] = intval(($soNgayAn/$countDateInWeek-$countNghiLeTet/$countDateInWeekLastMonth)*$hocPhiDichVuCongThem);

                // T??nh kh???u tr??? ti???n ??n
                $student_fee_i['khau_tru_tien_an'] = $item->isIncludedAnnualFee == null ? $countDateInWeekLastMonth*$feeTienAn - $soNgayAnThangTruoc*$feeTienAn : 0;

                // T??nh ti???n gi???m tr???
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

                // T??nh s??? ti???n ph???i thu
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
                    'message'   =>  'T???o d??? li???u th??nh c??ng'
                ];
            } else {
                $data = [
                    'status'    =>  true,
                    'message'   =>  'Ch??a ?????n k??? thanh to??n m???i'
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
                    'message'   =>  "S??? ti???n kh??ng h???p l???"
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
                $quan_ly_thu->noi_dung_phieu_thu = '????ng h???c ph?? th??ng '.$request->month.'/'.$request->year;
                $quan_ly_thu->ghi_chu = '';
                $quan_ly_thu->thanh_tien = $data_student_fee['tam_tinh'];
                $quan_ly_thu->da_thu = $data_student_fee['paid'];
                $quan_ly_thu->con_no = $data_student_fee['con_no'];
                if($quan_ly_thu->thanh_tien <= $quan_ly_thu->da_thu) {
                    $quan_ly_thu->isPaid = 1;
                }
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
