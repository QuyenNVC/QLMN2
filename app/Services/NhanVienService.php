<?php

namespace App\Services;

use App\Models\NhanVien;


class NhanVienService extends BaseService
{
    protected static $model = NhanVien::class;
    protected static $tbl = 'nhan_vien';
    protected static $primakyKey = 'ma_nv';

    public static function getData($request) {
        $items = NhanVien::join('phong_ban', 'nhan_vien.id_phong_ban', '=', 'phong_ban.id');
        foreach($request->all() as $key => $value) {
            if($value && $key != 'page' && $key != 'pageSize') {
                $items = $items->where($key, $value);
            }
        }
        $total = 0;
        if(isset($request->pageSize)) {
            $total = $items->count();
            $items = $items->offset($request->pageSize*($request->page - 1))->limit($request->pageSize);
        }
        $items = $items->orderBy(static::$primakyKey, 'desc')->get()->toArray();
        return [
            'items' =>  $items,
            'total' =>  $total ? $total : count($items)
        ];
    }
}
