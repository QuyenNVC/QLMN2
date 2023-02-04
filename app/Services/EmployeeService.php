<?php

namespace App\Services;

use App\Models\NhanVien;
use App\Models\ChamCong;
use App\Models\HinhThucChamCong;
use App\Models\PhuCap;
use App\Models\TangGiamLuong;

class EmployeeService
{
    public static function getData($ids, $excludes, $name, $departments, $workingStatus, $pageSize, $page, $toArray = false)
    {
        $items = NhanVien::query();
        $items->with('department');
        if ($name && $name != '') {
            $items->where('fullname', 'like', "%$name%");
        }

        if (count($departments)) {
            $items->where(function ($query) use ($departments) {
                foreach ($departments as $departmentId) {
                    $query->orWhere('id_phong_ban', $departmentId);
                }
            });
        }

        if ($workingStatus && $workingStatus != -1 && $workingStatus !== '') {
            $items->where('status_nghi_viec', $workingStatus);
        }

        if (count($ids)) {
            if (!in_array(-1, $ids)) {
                $items->where(function ($query) use ($ids) {
                    foreach ($ids as $id) {
                        $query->orWhere('ma_nv', $id);
                    }
                });
            }
        }
        if (count($excludes)) {
            $items->where(function ($query) use ($excludes) {
                foreach ($excludes as $id) {
                    $query->where('ma_nv', '!=', $id);
                }
            });
        }

        $count = 0;
        if ($toArray) {
            $items = $items->get()->toArray();
        } else {
            $count = $items->count();
            $items = $items->orderBy('created_at', 'desc')->offset($pageSize * ($page - 1))->limit($pageSize)->get();
        }
        return [
            'total' => $count,
            'data' => $items
        ];
    }

    public static function getDataTimekeeping($department, $month, $year)
    {
        $items = ChamCong::whereRaw('month = ? and year = ?', [$month, $year]);
        $items->join('nhan_vien', 'nhan_vien.ma_nv', '=', 'cham_cong.ma_nv');
        $items->where('nhan_vien.status_nghi_viec', '!=', 1);
        if ($department && $department != -1) {
            $items->where('nhan_vien.id_phong_ban', $department);
        }


        $items = $items->get()->toArray();
        $chamCong = [];
        for ($i = 0; $i < count($items); $i++) {
            $chamCong[] = [
                'ma_nv' => $items[$i]['ma_nv'],
                'fullname' => $items[$i]['fullname'],
                'data' => (array)json_decode($items[$i]["data"]),
            ];
        }
        return $chamCong;
    }

    public static function getDataSalary($ids, $excludes, $departments, $month, $year, $pageSize, $page)
    {
        $employees = NhanVien::query();
        $employees->leftJoin('phu_cap_nhan_vien', function ($join) use ($month, $year) {
            $join->on('phu_cap_nhan_vien.ma_nv', '=', 'nhan_vien.ma_nv');
            $join->where('phu_cap_nhan_vien.month', '=', $month);
            $join->where('phu_cap_nhan_vien.year', '=', $year);
        });
        $employees->leftJoin('ung_luong_nhan_vien', function ($join) use ($month, $year) {
            $join->on('ung_luong_nhan_vien.ma_nv', '=', 'nhan_vien.ma_nv');
            $join->where('ung_luong_nhan_vien.month', '=', $month);
            $join->where('ung_luong_nhan_vien.year', '=', $year);
        });
        $employees->leftJoin('cham_cong', function ($join) use ($month, $year) {
            $join->on('cham_cong.ma_nv', '=', 'nhan_vien.ma_nv');
            $join->where('cham_cong.month', '=', $month);
            $join->where('cham_cong.year', '=', $year);
        });
        $employees->leftJoin('tang_giam_luong_nhan_vien', function ($join) use ($month, $year) {
            $join->on('tang_giam_luong_nhan_vien.ma_nv', '=', 'nhan_vien.ma_nv');
            $join->where('tang_giam_luong_nhan_vien.month', '=', $month);
            $join->where('tang_giam_luong_nhan_vien.year', '=', $year);
        });
        $employees->leftJoin('phong_ban', 'phong_ban.id', '=', 'nhan_vien.id_phong_ban');
        $employees->where('nhan_vien.status_nghi_viec', '!=', 1);
        if (count($departments)) {
            $employees->where(function ($query) use ($departments) {
                foreach ($departments as $departmentId) {
                    $query->orWhere('nhan_vien.id_phong_ban', $departmentId);
                }
            });
        }
        if (count($ids)) {
            if (!in_array(-1, $ids)) {
                $employees->where(function ($query) use ($ids) {
                    foreach ($ids as $id) {
                        $query->orWhere('nhan_vien.ma_nv', $id);
                    }
                });
            }
        }
        if (count($excludes)) {
            $employees->where(function ($query) use ($excludes) {
                foreach ($excludes as $id) {
                    $query->where('nhan_vien.ma_nv', '!=', $id);
                }
            });
        }
        $employees->select(['nhan_vien.*', 'phu_cap_nhan_vien.data as data_allowance', 'ung_luong_nhan_vien.tien_ung', 'cham_cong.data as timekeeping', 'tang_giam_luong_nhan_vien.data as data_fluctuate', 'phong_ban.name as department']);

        $count = $employees->get()->count();

        $employees->offset($pageSize * ($page - 1))->limit($pageSize);
        $employees = $employees->get();

        $salaryData = [];
        foreach ($employees as $item) {
            $salaryEmployee = $item->luong_ngay;

            $allowance = 0;
            $fluctuate = 0;
            $salaryAdvance = $item->tien_ung ? $item->tien_ung : 0;
            $work_day = 0;

            $dataTimeKeeping = (array)json_decode($item->timekeeping);
            foreach (HinhThucChamCong::get() as $itemTimeKeeping) {
                if (isset($dataTimeKeeping['tong_' . $itemTimeKeeping->id])) {
                    $work_day += $dataTimeKeeping['tong_' . $itemTimeKeeping->id] * $itemTimeKeeping->ngay_cong;
                }
            }

            $dataAllowance = (array)json_decode($item->data_allowance);
            foreach (PhuCap::get() as $itemAllowance) {
                if (isset($dataAllowance[$itemAllowance->id]) && $dataAllowance[$itemAllowance->id]) {
                    if ($itemAllowance->don_vi_tinh == '%') {
                        $allowance += ($itemAllowance->phu_cap * $salaryEmployee) / 100;
                    } else {
                        $allowance += $itemAllowance->phu_cap;
                    }
                }
            }

            $datafluctuate = (array)json_decode($item->data_fluctuate);
            foreach (TangGiamLuong::get() as $itemfluctuate) {
                if (isset($datafluctuate[$itemfluctuate->id]) && $datafluctuate[$itemfluctuate->id]) {
                    $fluctuate += $itemfluctuate->tang_giam;
                }
            }

            $salaryData[] = [
                'ma_nv' => $item->ma_nv,
                'fullname' => $item->fullname,
                'department' => $item->department,
                'work_day' => $work_day,
                'salary_day' => $salaryEmployee,
                'salary_advance' => $salaryAdvance,
                'allowance' => $allowance,
                'fluctuate' => $fluctuate,
                'salary' => ($work_day * $salaryEmployee) - $salaryAdvance + $allowance + $fluctuate,
            ];
        }
        return [
            'total' => $count,
            'data' => $salaryData,
        ];
    }
}
