<?php

namespace App\Services;

use App\Models\TheoDoiSucKhoe;


class MonitorPhysicalService
{
    public static function getData($ids, $excludes, $class, $grades, $month, $year, $certification, $pageSize, $page, $toArray = false)
    {
        $items = TheoDoiSucKhoe::query();
        $items->join('enrollment_records', 'enrollment_records.id', '=', 'theo_doi_suc_khoe.ma_hs');
        $items->join('class', 'enrollment_records.id_class', '=', 'class.id');
        
        if (count($class)) {
            $items->where(function ($query) use ($class) {
                foreach ($class as $classId) {
                    $query->orWhere('enrollment_records.id_class', $classId);
                }
            });
        }

        if (count($grades)) {
            $items->where(function ($query) use ($grades) {
                foreach ($grades as $gradeId) {
                    $query->orWhere('class.grade', $gradeId);
                }
            });
        }


        if (count($ids)) {
            if (!in_array(-1, $ids)) {
                $items->where(function ($query) use ($ids) {
                    foreach ($ids as $id) {
                        $query->orWhere('theo_doi_suc_khoe.id', $id);
                    }
                });
            }
        }
        if (count($excludes)) {
            $items->where(function ($query) use ($excludes) {
                foreach ($excludes as $id) {
                    $query->where('theo_doi_suc_khoe.id', '!=', $id);
                }
            });
        }

        if (count($certification)) {
            $items->where(function ($query) use ($certification) {
                foreach ($certification as $id) {
                    if ($id == 1) {
                        $query->orWhere('theo_doi_suc_khoe.be_khoe', 1);
                    }
                    if ($id == 2) {
                        $query->orWhere('theo_doi_suc_khoe.be_ngoan', 1);
                    }
                }
            });
        }

        if ($month && $month != -1 && $month !== '') {
            $items->where('theo_doi_suc_khoe.month', $month);
        }

        if ($year && $year != -1 && $year !== '') {
            $items->where('theo_doi_suc_khoe.year', $year);
        }


        $items->select(['theo_doi_suc_khoe.*', 'enrollment_records.name', 'enrollment_records.birth_date']);
        $count = 0;
        if ($toArray) {
            $items = $items->get()->toArray();
        } else {
            $count = $items->count();
            $items = $items->orderBy('theo_doi_suc_khoe.created_at', 'desc')->offset($pageSize * ($page - 1))->limit($pageSize)->get();
        }
        return [
            'total' => $count,
            'data' => $items
        ];
    }
}
