<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UngLuongNhanVien;

class UngLuongController extends Controller
{
    /**
     * Get phu cap nhan vien
     * Author: HaoDT
     */
    public function getUngLuongNhanVien() {
        return view('admin.ung_luong_nhan_vien');
    }

    /**
     * update phu cap nhan vien
     * Author: HaoDT
     */
    public function updateUngLuongNhanVien(Request $request) {
        try {
            $month = $request->thang;
            $year = $request->nam;
            $phuCap = $request->phuCap;


            $item = UngLuongNhanVien::whereRaw('ma_nv = ? and month = ? and year = ?', [$phuCap["ma_nv"], $month, $year])->first();
            if ($item) {
                $item->tien_ung = $item->tien_ung + $phuCap['tien_ung'];
                $item->save();
            } else {
                $phuCapMoi = new UngLuongNhanVien();
                $phuCapMoi->tien_ung = $phuCap['tien_ung'];
                $phuCapMoi->month = $month;
                $phuCapMoi->year = $year;
                $phuCapMoi->ma_nv = $phuCap["ma_nv"];
                $phuCapMoi->save();
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
}
