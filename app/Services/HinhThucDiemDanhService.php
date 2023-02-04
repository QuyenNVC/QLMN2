<?php

namespace App\Services;

use App\Models\HinhThucDiemDanh;


class HinhThucDiemDanhService
{
    public static function getData($id_coso = null)
    {
        $hinhThucs = HinhThucDiemDanh::whereNull('isDeleted');
        if($id_coso) {
            $hinhThucs = $hinhThucs->where('id_coso', $id_coso);
        }
        $hinhThucs = $hinhThucs->get();
        return $hinhThucs;
    }
}
