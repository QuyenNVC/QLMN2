<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuanLyChi;
use App\Services\QuanLyChiService;
use App\Http\Controllers\helpers\HelperController;
use Illuminate\Support\Facades\Auth;

class QuanLyChiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.quan_ly_chi');
    }

    public function getList(Request $request) {
        try {
            $result = QuanLyChiService::getData($request);
            $data = [
                'status'    =>  true,
                'items'     =>  $result['items'],
                'count'     =>  $result['count'],
            ];
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   =>  $e->getMessage()
            ];
        }
        return response()->json($data, 200);
    }

    public function submit(Request $request) {
        try {
            $date = $request->month;
            $request->year = HelperController::format_month_year_helper($date)[1];
            $request->month = HelperController::format_month_year_helper($date)[0];
            $item = QuanLyChi::find($request->id);
            if(!$item) {
                $item = new QuanLyChi();
                $item->stt = QuanLyChiController::getSTT() + 1;
                $item->so_chung_tu  = QuanLyChiController::generateSoChungTu();
                // $item->isEditable = 1;
            }
            $item->ngay_chung_tu = $request->ngay_chung_tu;
            $item->month = $request->month;
            $item->year = $request->year;
            $item->nha_cung_cap = $request->nha_cung_cap ? $request->nha_cung_cap : null;
            $item->dai_dien = $request->dai_dien ? $request->dai_dien : null;
            $item->dia_chi = $request->dia_chi ? $request->dia_chi : null;
            $item->loai_chi_phi = $request->loai_chi_phi;
            $item->noi_dung_phieu_chi = $request->noi_dung_phieu_chi ? $request->noi_dung_phieu_chi : null;
            $item->thanh_tien = $request->thanh_tien;
            $item->da_chi = $request->da_chi;
            $item->con_no = $item->thanh_tien - $item->da_chi > 0 ? $item->thanh_tien - $item->da_chi : null;
            $item->ghi_chu = $request->ghi_chu;
            $item->id_coso = $request->id_coso;
            // $item->isPaid = $item->con_no == null ? 1 : null;
            if(Auth::user()->cannot('save', $item)) {
                abort(403);
            }
            $item->save();
            $data = [
                'status'    =>  true
            ];
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   =>  $e->getMessage()
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

    public function getSTT() {
        $year = date('Y');
        $from = date($year.'-01-01');
        $to = date($year.'-12-31');
        $lastPhieuThu = QuanLyChi::whereBetween('created_at', [$from,$to])->whereNull('isDeleted')->orderBy('stt', 'desc')->get()->first();
        return $lastPhieuThu ? (int)($lastPhieuThu->stt) : 0;
    }

    public function generateSoChungTu() {
        $stt = QuanLyChiController::getSTT()+1;
        $stringPC = 'PC'.(string)date('dmy').sprintf("%06d", $stt);
        return $stringPC;
    }
}
