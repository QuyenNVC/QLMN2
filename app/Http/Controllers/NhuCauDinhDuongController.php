<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NhuCauDinhDuong;

class NhuCauDinhDuongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.nhu_cau_dinh_duong');
    }

    public function getList() {
        try {
            $items = NhuCauDinhDuong::all();
            $data = [
                'status'    =>  true,
                'items'     =>  $items
            ];
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  true,
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
    public function update(Request $request) {
        try {
            $item = NhuCauDinhDuong::find($request->id);
            if($item) {
                $item->nang_luong = $request->nang_luong;
                $item->protein = $request->protein;
                $item->can_xi = $request->can_xi;
                $item->sat = $request->sat;
                $item->vitammin_a = $request->vitammin_a;
                $item->vitamin_b1 = $request->vitamin_b1;
                $item->vitamin_b2 = $request->vitamin_b2;
                $item->vitamin_b3 = $request->vitamin_b3;
                $item->vitamin_c = $request->vitamin_c;
                $item->note = $request->note;
                $item->save();
                $data = [
                    'status'    =>  true,
                ];
            } else {
                $data = [
                    'status'    =>  false,
                    'message'   =>  'Nhu cầu dinh dưỡng cần cập nhật không tồn tại'
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
