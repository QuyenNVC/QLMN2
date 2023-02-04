<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ThanhPhanDinhDuong;
use Illuminate\Support\Facades\Auth;

class ThanhPhanDinhDuongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.thanh_phan_dinh_duong');
    }

    public function getList(Request $request) {
        try {
            $items = ThanhPhanDinhDuong::whereNull('isDeleted');
            if($request->nhom_thuc_pham) {
                $items = $items->where('nhom_thuc_pham', $request->nhom_thuc_pham);
            }
            if($request->id_coso) {
                $items = $items->where('id_coso', $request->id_coso);
            }
            $total = $items->count();
            if(isset($request->page) && isset($request->pageSize)) {
                $items = $items->offset($request->pageSize*($request->page -1))->limit($request->pageSize)->get();
            } else {
                $items = $items->get();
            }
            $data = [
                'status'    =>  true,
                'items'     =>  $items,
                'total'     =>  $total
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
                $item = ThanhPhanDinhDuong::find($row['id']);
                if(!$item) {
                    $item = new ThanhPhanDinhDuong();
                }
                foreach($row as $key => $value) {
                    $item[$key] = $value;
                }
                unset($item['newRow'], $item['nhom_thuc_pham_name']);
                $item->id_coso = $request->id_coso;
                if(Auth::user()->cannot('save', $item)) {
                    abort(403);
                }
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
                $item = ThanhPhanDinhDuong::find($row['id']);
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
