<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HinhThucDiemDanh;
use App\Service\AddLog;
use App\Services\HinhThucDiemDanhService;
use Illuminate\Support\Facades\Auth;

class HinhThucDiemDanhController extends Controller
{
    public function quanLy(Request $request) {
        return view('admin.hinh_thuc_diem_danh');        
    }

    /**
     * api get list hinh_thuc_cham_cong
     * Author: HaoDT
     */
    public function getList(Request $request) {
        try {
            $hinhThucs = HinhThucDiemDanhService::getData($request->id_coso);
            $data = [
                'status' => true,
                'hinhThucs' => $hinhThucs
            ];
            return json_encode($data);
        }
        catch (Exception $e) {
            $data = [
                'status' => false
            ];
            return json_encode($data);
        }
        
    }

    // /**
    //  * api create 
    //  * Author: HaoDT
    //  */
    public function createHinhThucDiemDanh(Request $request) {
        try {

            $hinhThuc = new HinhThucDiemDanh();
            $hinhThuc->name = $request->name;
            $hinhThuc->id_coso = $request->id_coso;
            $arrItem = [];
            foreach($request->daily as $item) {
                $arrItem[] = $item['id'];
            }
            $hinhThuc->daily = implode(',', $arrItem);

            $hinhThuc->khau_tru_hoc_phi_thang = $request->khau_tru_hoc_phi_thang ? 1 : null;
            $hinhThuc->note = $request->note;
            if(Auth::user()->cannot('save', $hinhThuc)) {
                abort(403);
            }
            $hinhThuc->save();
            $data = [
                'status' => true,
            ];
            return json_encode($data);
        
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => 'Lỗi server'
            ];
            return json_encode($data);
        }
        
    }

    // /**
    //  * api create phu_cap
    //  * Author: HaoDT
    //  */
    public function deleteHinhThucDiemDanh(Request $request, $id) {
        try {
            $hinhThuc = HinhThucDiemDanh::where('id', $id)->first();
            $data = [
                'status' => true
            ];
            $hinhThuc->isDeleted = 1;
            if(Auth::user()->cannot('save', $hinhThuc)) {
                abort(403);
            }
            $hinhThuc->save();
            return json_encode($data);
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => 'Lỗi server'
            ];
            return json_encode($data);
        }
        
    }

    // /**
    //  * api update hinhthucchamcong
    //  * Author: HaoDT
    //  */
    public function updateHinhThucDiemDanh(Request $request) {
        try {
            $hinhThuc = HinhThucDiemDanh::where('id', $request->id)->first();
            if ( $hinhThuc ) {
                $hinhThuc->name = $request->name;
                $hinhThuc->id_coso = $request->id_coso;


                $arrItem = [];
                foreach($request->daily as $item) {
                    $arrItem[] = $item['id'];
                }
                $hinhThuc->daily = implode(',', $arrItem);

                $hinhThuc->khau_tru_hoc_phi_thang = $request->khau_tru_hoc_phi_thang ? 1 : null;
                $hinhThuc->note = $request->note;
                if(Auth::user()->cannot('save', $hinhThuc)) {
                    abort(403);
                }
                $hinhThuc->save();

                $data = [
                    'status' => true,
                ];
                return json_encode($data);
            } else {
                $data = [
                    'status' => false,
                    'message' => 'Lỗi dữ liệu'
                ];
                return json_encode($data);
            }
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => 'Lỗi server'
            ];
            return json_encode($data);
        }
        
    }
}
