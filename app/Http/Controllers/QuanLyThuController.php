<?php

namespace App\Http\Controllers;
use App\Models\QuanLyThu;
use App\Http\Controllers\helpers\HelperController;
use App\Services\QuanLyThuService;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class QuanLyThuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.quan_ly_thu');
    }

    public function getList(Request $request) {
        try {
            $result = QuanLyThuService::getData($request);
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
            $item = QuanLyThu::find($request->id);
            if(!$item) {
                $item = new QuanLyThu();
                $item->stt = QuanLyThuController::getSTT() + 1;
                $item->so_chung_tu  = QuanLyThuController::generateSoChungTu();
                $item->isEditable = 1;
            }
            if($item->isEditable) {
                $item->ngay_chung_tu = $request->ngayChungTu;
                $item->month = $request->month;
                $item->year = $request->year;
                $item->isFromStudent = $request->tuHocSinh;
                $item->ma_khach_hang = $request->maKhachHang;
                $item->ten_khach_hang = $request->tenKhachHang;
                $item->dia_chi = $request->diaChi;
                $item->noi_dung_phieu_thu = $request->noiDungPhieuThu;
                $item->thanh_tien = $request->thanhTien;
                $item->da_thu = $request->daThu;
                $item->con_no = $item->thanh_tien - $item->da_thu > 0 ? $item->thanh_tien - $item->da_thu : null;
                $item->ghi_chu = $request->ghiChu;
                $item->isPaid = $item->con_no == null ? 1 : null;
                $item->id_coso = $request->id_coso;
                if(Auth::user()->cannot('save', $item)) {
                    abort(403);
                }
                $item->save();
                $data = [
                    'status'    =>  true
                ];
            } else {
                $data = [
                    'status'    =>  false,
                    'message'   =>  'Phiếu thu này không được chỉnh sửa'
                ];
            }
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
        $lastPhieuThu = QuanLyThu::whereBetween('created_at', [$from,$to])->whereNull('isDeleted')->orderBy('stt', 'desc')->get()->first();
        return $lastPhieuThu ? (int)($lastPhieuThu->stt) : 0;
    }

    public function generateSoChungTu() {
        $stt = QuanLyThuController::getSTT()+1;
        $stringPT = 'PT'.(string)date('dmy').sprintf("%06d", $stt);
        return $stringPT;
    }
}
