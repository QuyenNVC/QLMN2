<?php
namespace App\Services;
use App\Models\SuatAn;
use App\Models\NhuCauNangLuong;
use App\Models\ThanhPhanDinhDuong;
use App\Http\Controllers\helpers\HelperController;

class SuatAnService {
    public function getData($request) {
        $name =  HelperController::string_filter_helper($request->name);
        $items = SuatAn::whereNull('isDeleted')->where('name', 'like', "%$name%");
        if($request->loai_suat_an) {
            $items = $items->where('loai_suat_an', $request->loai_suat_an);
        }
        if($request->age) {
            $items = $items->where('age', $request->age);
        }
        if($request->id_coso) {
            $items = $items->where('id_coso', $request->id_coso);
        }
        $count = $items->count();
        if($request->pageSize && $request->page) {
            $items = $items->offset($request->pageSize*($request->page -1))->limit($request->pageSize)->orderBy('id', 'desc')->get();
        } else {
            $items = $items->orderBy('id', 'desc')->get();
        }
        for($i=0;$i<count($items->toArray()); $i++) {
            if($items[$i]->data) {
                $items[$i]->data = array_map(function($item) {
                    $thuc_pham = ThanhPhanDinhDuong::find($item[0]);
                    return $thuc_pham->name;
                },unserialize($items[$i]->data));
                if(count($items[$i]->data) > 1) {
                    $items[$i]->data = implode(', ',$items[$i]->data);
                } elseif(count($items[$i]->data) === 1) {
                    $items[$i]->data = $items[$i]->data[0];
                } else {
                    $items[$i]->data = '';
                }
            }
            
        }
        return [
            'items' => $items,
            'count' => $count
        ];
    }
}