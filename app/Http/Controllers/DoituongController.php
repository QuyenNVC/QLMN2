<?php

namespace App\Http\Controllers;
use App\Models\Doituong;

use Illuminate\Http\Request;

class DoituongController extends Controller
{
    public function index() {
        return view('admin.doi_tuong');
    }

    public function getList(Request $request) {
        try {
            $doituongs = Doituong::whereNull('isDeleted')->orderBy('id', 'desc')->get();
            $data = [
                'doituongs'  => $doituongs,
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
            $doituong = new Doituong();
            $doituong->name = $request->name;
            $doituong->dinh_muc_giam = (float)$request->dinh_muc_giam;
            $doituong->dinh_muc_giam_tien = (int)$request->dinh_muc_giam_tien;
            $doituong->note = $request->note;
            $doituong->save();
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
            $doituong = Doituong::where('id', $request->id)->first();
            if ( $doituong ) {
                $doituong->name = $request->name;
                $doituong->dinh_muc_giam = (float)$request->dinh_muc_giam;
                $doituong->dinh_muc_giam_tien = (int)$request->dinh_muc_giam_tien;
                $doituong->note = $request->note;
                $doituong->save();
                $data = [
                    'status' => true,
                    'doituong' => $doituong
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

    public function delete(Request $request, $id) {
        try {
            $doiTuong = Doituong::where("id", $id)->get()->first();
            $doiTuong->isDeleted = '1';
            $doiTuong->save();
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
