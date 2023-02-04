<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NangKhieu;

class NangKhieuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.nang_khieu');
    }

    public function getList(Request $request) {
        try {
            $nangkhieus = NangKhieu::orderBy('id', 'desc')->get();
            $data = [
                'nangkhieus'  => $nangkhieus,
                'status' => true
            ];
        }
        catch (Exception $e) {
            $data = [
                status => false
            ];
        };
        return json_encode($data);
    }

    public function create(Request $request) {
        try {
            $nangkhieu = new NangKhieu();
            $nangkhieu->name = $request->name;
            $nangkhieu->note = $request->note;
            $nangkhieu->save();
            $data = [
                'status' => true
            ];
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => "Lỗi Server"
            ];    
        }
        return json_encode($data);
    }

    public function update(Request $request) {
        try {
            $nangkhieu = NangKhieu::where('id', $request->id)->first();
            if ( $nangkhieu ) {
                $nangkhieu->name = $request->name;
                $nangkhieu->note = $request->note;
                $nangkhieu->save();
                $data = [
                    'status' => true,
                    'nangkhieu' => $nangkhieu
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delete(Request $request, $id) {
        try {
            NangKhieu::where("id", $id)->delete();
            $data = [
                'status' => true
            ];
        }
        catch(Exception $e) {
            $data = [
                'status' => false,
                'message' => "Lỗi Server"
            ]; 
        }
        return json_encode($data);
    }
}
