<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuatAn;
use App\Services\SuatAnService;
use App\Models\NhuCauNangLuong;
use App\Models\ThanhPhanDinhDuong;
use App\Http\Controllers\helpers\HelperController;
use Illuminate\Support\Facades\Auth;

class SuatAnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.suat_an');
    }

    public function getList(Request $request) {
        try {
            $result = SuatAnService::getData($request);
            $data = [
                'status'    =>  true,
                'items'     =>  $result['items'],
                'total'     =>  $result['count']
            ];
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   =>$e->getMessage()
            ];
        }
        return response()->json($data, 200);
    }

    public function getTieuChuanSuatAn(Request $request) {
        try {
            $item = NhuCauNangLuong::whereNull('isDeleted')->where('age', $request->age)->where('loai_suat_an', $request->loai_suat_an)->first();
            if($item) {
                $data = [
                    'status' => true,
                    'item'  =>  $item
                ];
            } else {
                $data = [
                    'status'    =>  false,
                    'message'   =>  'Không có dữ liệu'
                ];
            }
        }
        catch(Exception $e) {
            $data = [
                'status'    =>  false,
                'message'   =>$e->getMessage()
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
            $item = SuatAn::find($request->id);
            if(!$item) {
                $item = new SuatAn();
            }
            $item->name = $request->name;
            $item->age = $request->age;
            $item->content = $request->content;
            $item->loai_suat_an = $request->loai_suat_an;
            $item->id_coso = $request->id_coso;
            $arr = [];
            foreach($request->data as $thuc_pham) {
                $arr_i = [];
                $arr_i[] = $thuc_pham['id'];
                $arr_i[] = $thuc_pham['so_luong'];
                $arr_i[] = $thuc_pham['lay_tu_kho']; 
                $arr[] = $arr_i;
            }
            $item->data = serialize($arr);
            $item->tong_tien= $request->tong_tien;
            if(Auth::user()->cannot('save', $item)) {
                abort(403);
            }
            $item->save();
            $data = [
                'status' => true
            ];
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => $e
            ];    
        }
        return json_encode($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $item = SuatAn::find($id);
            if($item) {
                if($item->data) {
                    $thuc_phams = unserialize($item->data);
                    $items = array_map(function($thuc_pham_data) {
                        $thuc_pham = ThanhPhanDinhDuong::find($thuc_pham_data[0]);
                        return [
                            'thuc_pham' =>  $thuc_pham,
                            'so_luong'  =>  $thuc_pham_data[1],
                            'lay_tu_kho'    =>  $thuc_pham_data[2]
                        ];
                    }, $thuc_phams);
                }
                $data = [
                    'status' => true,
                    'items'  =>  $items
                ];
            } else {
                $data = [
                    'status'    =>  false,
                    'message'   =>  'Không có dữ liệu'
                ];
            }
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => $e
            ];    
        }
        return json_encode($data);
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
            if(empty($request->selected)) {
                $data = [
                    'status'    =>  false,
                    'message'   => 'Chưa có danh mục cơ sở vật chất nào được chọn!'
                ];
                return json_encode($data);
            }
            $items = SuatAnService::getData($request)["items"];
            $selected = $request->selected;
            $excludes = $request->excludes;
            $arrays = HelperController::arraySelected($items, $selected, $excludes);
            foreach($arrays as $item_id) {
                $item = SuatAn::find($item_id);
                if($item) {
                    $item->isDeleted = 1;
                    if(Auth::user()->cannot('save', $item)) {
                        abort(403);
                    }
                    $item->save();
                }
            }
            $data = [
                'status' => true
            ];
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => $e
            ];    
        }
        return json_encode($data);
    }
}
