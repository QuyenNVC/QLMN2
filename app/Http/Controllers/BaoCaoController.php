<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaoCaoController extends Controller
{
     /**
     * get view bao_cao_nghi_viec
     * Author: HaoDT
     */
    public function getBaoCaoNghiViec(Request $request) {
        return view('admin.bao_cao_nghi_viec');        
    }

     /**
     * get view bao_cao_phu_cap
     * Author: HaoDT
     */
    public function getBaoCaoPhuCap(Request $request) {
        return view('admin.bao_cao_phu_cap_nhan_vien');        
    }

     /**
     * get view bao_cao_phu_cap
     * Author: HaoDT
     */
    public function getBaoCaoTamUng(Request $request) {
        return view('admin.bao_cao_tam_ung_nhan_vien');        
    }

     /**
     * get view bao_cao_phu_cap
     * Author: HaoDT
     */
    public function getBaoCaoNgayCong(Request $request) {
        return view('admin.bao_cao_ngay_cong_nhan_vien');        
    }


     /**
     * get view bao_cao_phu_cap
     * Author: HaoDT
     */
    public function getBaoCaoChiLuong(Request $request) {
        return view('admin.bao_cao_chi_luong_nhan_vien');        
    }
}
