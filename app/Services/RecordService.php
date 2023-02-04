<?php

namespace App\Services;

use App\Models\Record;


class RecordService
{
    public static function getData($request, $id_enrollment = null) {
        $items = Record::query();
        if($id_enrollment != 'undefined' && $id_enrollment !== null) {
            $items = $items->where('id_enrollment', $id_enrollment);
        }
        if($request->id_coso) {
            $items = $items->where('id_coso', $request->id_coso); 
        }
        if(isset($request->id_class)) {
            $items = $items->where('id_class', $request->id_class);
        }
        $items = $items->whereNull('isDeleted');
        $total = $items->count();
        $items = $items->orderByDesc('id');
        if(isset($request->page)) {
            $items = $items->offset($request->pageSize*($request->page - 1))->limit($request->pageSize);
        }
        $items = $items->get();
        return [
            'items' => $items,
            'total' => $total
        ];
    }
}
