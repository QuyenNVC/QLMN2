<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThucDon;
use DateTime;
use App\Models\QuanLyChi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ThucDonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.thuc_don');
    }

    public function getList(Request $request) {
        try {
            $time = strtotime($request->ngay);
        $week = date("W",$time);
        $year = date("Y",$time);
        $date = new DateTime($request->ngay);
        $date = $date->setISODate($year, $week);
        date_modify($date,"-1 days");
        $items = [];
        $nguoi_nau = '';
        $nguoi_duyet = '';
        for($i = 0 ; $i<env('AMOUNT_OF_DATE'); $i++) {
            date_modify($date, "+1 day");
            $item = ThucDon::where('date',  $date->format("Y-m-d"));
            if($request->cap_duong_nau_chinh) {
                $item = $item->where('nguoi_nau', $request->cap_duong_nau_chinh);
            }
            if($request->age) {
                $item = $item->where('grade', $request->age);
            }
            if($request->id_coso) {
                $item = $item->where('id_coso', $request->id_coso);
            }
            $item = $item->get()->first();
            if(!$item) {
                $item = new ThucDon();
                $item->grade = $request->age ? $request->age : null;
                $item->date = $date->format("Y-m-d");
                $item->bua_sang = null;
                $item->bua_trua = null;
                $item->bua_xe = null;
                $item->bua_chieu = null;
                $item->so_luong = null;
                $item->da_chi = null;
            } else {
                $phieu_chi = QuanLyChi::find($item->id_phieu_chi);
                // var_dump($phieu_chi->da_chi);
                $item['da_chi'] = $phieu_chi->da_chi;
                $nguoi_nau = $item->nguoi_nau ? $item->nguoi_nau : $nguoi_nau;
                $nguoi_duyet = $item->nguoi_duyet ? $item->nguoi_duyet : $nguoi_duyet;
            }
            $items[] = $item;
        }
        $nguoi_duyet = User::find($nguoi_duyet);
            $nguoi_duyet = $nguoi_duyet ? $nguoi_duyet->name : '';
        $data = [
            'status'    =>  true,
            'items'     =>  $items,
            'amount_of_date'    => env('AMOUNT_OF_DATE'),
            'nguoi_nau' =>  $nguoi_nau,
            'nguoi_duyet'   =>  $nguoi_duyet
        ];
        } catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'     =>  $e->getMessage
            ];
        }
        return response()->json($data, 200);
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
        try {
            $array = $request->array;
            // var_dump($array);
            foreach($array as $thuc_don) {
                $thuc_don['id'] = isset($thuc_don['id']) ? $thuc_don['id'] : null;
                if($thuc_don['id'] || $thuc_don["bua_sang"] || $thuc_don["bua_trua"] || $thuc_don["bua_xe"]  || $thuc_don["so_luong"] ) {
                    $item = ThucDon::find($thuc_don['id']);
                    if(!$item) {
                        $item = new ThucDon();
                        $item->id_coso = $request->id_coso;
                        $phieu_chi = new QuanLyChi();
                        $phieu_chi->so_chung_tu = QuanLyChiController::generateSoChungTu();
                        $phieu_chi->stt = QuanLyChiController::getSTT() + 1;
                        $phieu_chi->ngay_chung_tu = $thuc_don["ngay"];
                        $ngay_chi = new DateTime($thuc_don["ngay"]);
                        $phieu_chi->month = $ngay_chi->format("m");
                        $phieu_chi->year = $ngay_chi->format("Y");
                        $phieu_chi->loai_chi_phi = env('LOAI_CHI_PHI_TIEN_AN_HANG_NGAY');
                        $phieu_chi->thanh_tien = $thuc_don["tc_chi"];
                        $phieu_chi->da_chi = 0;
                        $phieu_chi->noi_dung_phieu_chi = "Chi tiền ăn ngày ". $ngay_chi->format("d/m/Y");
                        $phieu_chi->id_coso = $request->id_coso;
                        if(Auth::user()->cannot('save', $item)) {
                            abort(403);
                        }
                        $phieu_chi->save();
                        $item->id_phieu_chi = $phieu_chi->id;
                        $item->nguoi_nau = $request->nguoi_nau;
                        $item->nguoi_duyet = Auth::id();
                    } else {
                        $phieu_chi = QuanLyChi::find($item["id_phieu_chi"]);
                        if($phieu_chi) {
                            $phieu_chi->thanh_tien = $thuc_don["tc_chi"];
                            $phieu_chi->save();
                        }
                        $item->nguoi_nau = $request->nguoi_nau;
                        $item->nguoi_duyet = Auth::id();
                    }
                    // var_dump($item);
                    $item->date = $thuc_don["ngay"];
                    $item->grade = $request->grade ? $request->grade : null;
                    $item->bua_sang = $thuc_don["bua_sang"];
                    $item->bua_trua = $thuc_don["bua_trua"];
                    $item->bua_xe = $thuc_don["bua_xe"];
                    $item->bua_chieu = $thuc_don["bua_chieu"];
                    $item->so_luong = $thuc_don["so_luong"];
                    $item->id_coso = $request->id_coso;
                    if(Auth::user()->cannot('save', $item)) {
                        abort(403);
                    }
                    $item->save();
                }
            }
            $data = [
                'status'    =>  true
            ];
        } catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'     =>  $e->getMessage
            ];
        }
        return response()->json($data, 200);
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

    public function getInfoThucDon(Request $request) {
        try {
            $nguoi_duyet = Auth::id();
            $nguoi_nau = 
            $data = [
                'status'    =>  true,
                'nguoi_duyet'   =>  '',
                'nguoi_nau' =>  ''
            ];
        } catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'     =>  $e->getMessage
            ];
        }
        return response()->json($data, 200);
    }

    public function getNhanVienNauAn(Request $request) {
        try {
            $items = User::join('user_role', 'users.id', '=', 'user_role.user_id')->where('user_role.role_id', 10)->whereNull('isDeleted');
            if($request->id_coso) {
                $items = $items->where('id_coso', $request->id_coso);
            }
            $items = $items->select('users.id', 'name')->get();
            $data = [
                'status'    =>  true,
                'items' =>  $items
            ];
        } catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'     =>  $e->getMessage
            ];
        }
        return response()->json($data, 200);
    }
}
