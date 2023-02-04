<?php
namespace App\Services;
use App\Models\QuanLyChi;
use App\Http\Controllers\helpers\HelperController;

class QuanLyChiService {
    public function getData($request = null) {
        if($request->month) {
            $date = $request->month;
            $request->year = HelperController::format_month_year_helper($date)[1];
            $request->month = HelperController::format_month_year_helper($date)[0];
        }
        $soChungTu =  $request->soChungTu;
        $ngayChungTu =  $request->ngayChungTu;
        $noi_dung_phieu_chi =  HelperController::string_filter_helper($request->noi_dung_phieu_chi);
        $items = QuanLyChi::where('so_chung_tu', 'like', "%$soChungTu%")->where('ngay_chung_tu', 'like', "%$ngayChungTu%")->where('noi_dung_phieu_chi', 'like', "%$noi_dung_phieu_chi%");
        if($request->month) {
            $items = $items->where('month', $request->month);
        }
        if($request->year) {
            $items = $items->where('year', $request->year);
        }
        if($request->loai_chi_phi) {
            $items = $items->where('loai_chi_phi', $request->loai_chi_phi);
        }
        if($request->id_coso) {
            $items = $items->where('id_coso', $request->id_coso);
        }
        $count = $items->count();
        $items = $items->orderBy('created_at', 'desc')->offset($request->pageSize*($request->page - 1))->limit($request->pageSize)->get();
        return [
            'count' => $count,
            'items' => $items
        ];
    }
}