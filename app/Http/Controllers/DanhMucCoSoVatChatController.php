<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DanhMucCoSoVatChat;
use Illuminate\Support\Facades\Auth;
use App\Services\DanhMucCoSoVatChatService;
use App\Http\Controllers\helpers\HelperController;

class DanhMucCoSoVatChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.danh_muc_co_so_vat_chat');
    }

    public function getList(Request $request) {
        try {
            $result = DanhMucCoSoVatChatService::getData($request);
            $data = [
                'status'    =>  true,
                'items'     =>  $result['items'],
                'count'     =>  $result['count']
            ];
        }
        catch(Excepion $e) {
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
            $item = DanhMucCoSoVatChat::find($request->id);
            if(!$item) {
                $item = new DanhMucCoSoVatChat();
            }
            $item->name = $request->name;
            $item->type_id = $request->type_id;
            $item->unit = $request->unit;
            $item->note = $request->note;
            $item->id_coso = $request->id_coso;
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
            if(empty($request->selected)) {
                $data = [
                    'status'    =>  false,
                    'message'   => 'Chưa có danh mục cơ sở vật chất nào được chọn!'
                ];
                return json_encode($data);
            }
            $items = DanhMucCoSoVatChatService::getData($request)['items'];
            $selected = $request->selected;
            $excludes = $request->excludes;
            $arrays = HelperController::arraySelected($items, $selected, $excludes);
            foreach($arrays as $item_id) {
                $item = DanhMucCoSoVatChat::find($item_id);
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
