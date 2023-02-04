<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChamCong;

class ChamCongController extends Controller
{
    /**
     * get Form cham cong
     * Author: HaoDT
     */
    public function getForm() {
        return view('admin.cham_cong');
    }

    /**
     * api get list chamcong
     * Author: HaoDT
     */
    public function getList(Request $request, $month, $year) {
        try {
            $user = ChamCong::whereRaw('month = ? and year = ?', [$month, $year])->get();
            $chamCong = [];
            for($i = 0; $i < count($user); $i++) {
                $chamCong[] = $user[$i]["data"];
            }
            $data = [
                'status' => true,
                'cham_cong' => $chamCong
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


    /**
     * update cham cong
     * Author: HaoDT
     */
    public function updateChamCong(Request $request) {
        try {
            $month = $request->thang;
            $year = $request->nam;
            $chamCong = $request->chamCong;

            for($i = 0; $i < count($chamCong); $i++) {
                $item = ChamCong::whereRaw('ma_nv = ? and month = ? and year = ?', [$chamCong[$i]["ma_nv"], $month, $year])->first();
                if ($item) {
                    $item->data = json_encode($chamCong[$i]);
                    $item->save();
                } else {
                    $ChamCongMoi = new ChamCong();
                    $ChamCongMoi->data = json_encode($chamCong[$i]);
                    $ChamCongMoi->month = $month;
                    $ChamCongMoi->year = $year;
                    $ChamCongMoi->ma_nv = $chamCong[$i]["ma_nv"];
                    $ChamCongMoi->save();
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
}
