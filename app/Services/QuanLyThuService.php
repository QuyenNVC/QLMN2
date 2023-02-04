<?php
namespace App\Services;
use App\Models\QuanLyThu;
use App\Http\Controllers\helpers\HelperController;

class QuanLyThuService {
    public function getData($request = null) {
        $soChungTu =  $request->soChungTu;
        $ngayChungTu =  $request->ngayChungTu;
        $tenKhachHang =  HelperController::string_filter_helper($request->tenKhachHang);
        $id_coso = $request->id_coso;
        $items = QuanLyThu::where('so_chung_tu', 'like', "%$soChungTu%")->where('ngay_chung_tu', 'like', "%$ngayChungTu%")->where('ten_khach_hang', 'like', "%$tenKhachHang%")->whereNull('isDeleted');
        if($id_coso) {
            $items = $items->where('id_coso', $id_coso);
        }
        $count = $items->count();
        $items = $items->orderBy('created_at', 'desc')->offset($request->pageSize*($request->page - 1))->limit($request->pageSize)->get();
        return [
            'items' => $items,
            'count' => $count
        ];
    }
}