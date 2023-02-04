<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NhuCauNangLuong;

class NhuCauNangLuongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.nhu_cau_nang_luong');
    }

    public function getList(Request $request) {
        try {
            $items = NhuCauNangLuong::whereNull('isDeleted')->get();
            $data = [
                'status'    =>  true,
                'items'     =>  $items,
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
        try {
            foreach($request->rowsChanged as $row) {
            $item = NhuCauNangLuong::find($row['id']);
            if(!$item) {
                $item = new NhuCauNangLuong();
            }
            $item->age = $row['age'];
            $item->loai_suat_an = $row['loai_suat_an'];
            $item->name = $row['name'];
            $item->nang_luong_ca_ngay = $row['nang_luong_ca_ngay'];
            $item->nang_luong_o_truong = $row['nang_luong_o_truong'];
            $item->protid_ca_ngay = $row['protid_ca_ngay'];
            $item->protid_o_truong = $row['protid_o_truong'];
            $item->lipid_ca_ngay = $row['lipid_ca_ngay'];
            $item->lipid_o_truong = $row['lipid_o_truong'];
            $item->glucid_ca_ngay = $row['glucid_ca_ngay'];
            $item->glucid_o_truong = $row['glucid_o_truong'];
            $item->note = $row['note'];
            $item->save();
        }
                $data = [
                    'status'    =>  true
                ];
            
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   => $e->getMessage()
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
    public function destroy(Request $request)
    {
        try {
            foreach($request->arr as $row) {
                $item = NhuCauNangLuong::find($row['id']);
                if($item) {
                    $item->isDeleted = 1;
                    $item->save();
                }
            }
            $data = [
                'status'    =>  true,
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
}
