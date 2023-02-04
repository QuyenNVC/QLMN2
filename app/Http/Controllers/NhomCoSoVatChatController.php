<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NhomCoSoVatChat;

class NhomCoSoVatChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.nhom_co_so_vat_chat');
    }

    public function getList() {
        try {
            $items = NhomCoSoVatChat::whereNull('isDeleted')->orderBy('id', 'desc')->get();
            $data = [
                'status'    =>  true,
                'items'     =>  $items
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
            $item = NhomCoSoVatChat::find($request->id);
            if(!$item) {
                $item = new NhomCoSoVatChat();
            }
            $item->name = $request->name;
            $item->note = $request->note;
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
            foreach($request->array as $item_id) {
                $item = NhomCoSoVatChat::find($item_id);
                if($item) {
                    $item->isDeleted = 1;
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
