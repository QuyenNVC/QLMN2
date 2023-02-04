<?php
namespace App\Services;
use App\Models\DanhMucCoSoVatChat;

class DanhMucCoSoVatChatService {
    public function getData($request) {
        $items = DanhMucCoSoVatChat::whereNull('isDeleted');
        if($request->type_id) {
            $items = $items->where('type_id', $request->type_id);
        }
        if($request->id_coso) {
            $items = $items->where('id_coso', $request->id_coso);
        }
        $count = $items->count();
        if($request->page && $request->pageSize) {
            $items = $items->offset($request->pageSize*($request->page - 1))->limit($request->pageSize);
        }
        $items = $items->orderBy('id', 'desc')->get();
        return [
            'items' => $items,
            'count' => $count
        ];
    }
}