<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NhatKySucKhoe;
use App\Models\Record;

class NhatKySucKhoeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // var_dump(Auth::user());
        return view('admin.nhat_ky_suc_khoe');
    }

    public function getList(Request $request) {
        try {
            $monthRequest = explode('-', $request->date)[1];
            $yearRequest = explode('-', $request->date)[0];
            $students = Record::where('id_class', $request->class)->whereNull('isDeleted')->get();
            $dataNhatKySucKhoes = [];
            if($students) {
                foreach($students as $item) {
                    $data = NhatKySucKhoe::where('month', $monthRequest)->where('year', $yearRequest)->where('ma_hs', $item->id)->get()->first();
                    if(!$data) {
                        $data = new NhatKySucKhoe();
                        $data->month = $monthRequest;
                        $data->year = $yearRequest;
                        $data->ma_hs = $item->id;
                        $data->save();
                    }
                    $nhat_ky_suc_khoe_i = [
                        'ma_hs' => $item->id,
                        'name'  =>  $item->name,
                        'data'  => $data->data
                    ];
                    $dataNhatKySucKhoes[] = $nhat_ky_suc_khoe_i;
                }
            }
            $data = [
                'status'    =>  true,
                'nhatKySucKhoes'    => $dataNhatKySucKhoes
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
    public function update(Request $request)
    {
        try {
            $yearRequest = explode('-', $request->date)[0];
            $monthRequest = explode('-', $request->date)[1];
            $dateRequest = explode('-', $request->date)[2];
            $nhatKy = NhatKySucKhoe::where('year', $yearRequest)->where('month', $monthRequest)->where('ma_hs', $request->ma_hs)->get()->first();
            $dataNhatKy = json_decode($nhatKy->data, true);
            $dataNhatKy[$dateRequest] = [
                'tinh_trang'    =>  $request->tinh_trang,
                'note'  =>  $request->note
            ];
            $nhatKy->data = json_encode($dataNhatKy);
            $nhatKy->save();
            $data = [
                'status'    => true
            ];
        } catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   => $e
            ];
        }
        return response()->json($data);
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
