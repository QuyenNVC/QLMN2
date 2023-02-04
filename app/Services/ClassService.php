<?php

namespace App\Services;

use App\Models\Classes;
use App\Models\ClassTeacher;


class ClassService
{
    public static function getData($ids, $excludes, $class, $departments, $pageSize, $page, $toArray = false)
    {
        $items = ClassTeacher::query();
        $items->join('class', 'class.id', '=', 'class_teacher.id_class');
        $items->join('nhan_vien', 'nhan_vien.ma_nv', '=', 'class_teacher.id_giaovien');
        $items->join('school_year', 'school_year.id', '=', 'class.school_year');
        $items->join('grade', 'grade.id', '=', 'class.grade');

        if (count($departments)) {
            $items->where(function ($query) use ($departments) {
                foreach ($departments as $departmentId) {
                    $query->orWhere('id_phong_ban', $departmentId);
                }
            });
        }

        if (count($class)) {
            $items->where(function ($query) use ($class) {
                foreach ($class as $classId) {
                    $query->orWhere('class.id', $classId);
                }
            });
        }

        if (count($ids)) {
            if (!in_array(-1, $ids)) {
                $items->where(function ($query) use ($ids) {
                    foreach ($ids as $id) {
                        $query->orWhere('class.id', $id);
                    }
                });
            }
        }
        if (count($excludes)) {
            $items->where(function ($query) use ($excludes) {
                foreach ($excludes as $id) {
                    $query->where('class.id', '!=', $id);
                }
            });
        }

        $items->select(['class.*', 'class_teacher.*', 'nhan_vien.*', 'school_year.*', 'grade.name as grade_name', 'class.name as class_name']);

        $count = 0;
        if ($toArray) {
            $items = $items->get()->toArray();
        } else {
            $count = $items->count();
            $items = $items->orderBy('class_teacher.created_at', 'desc')->offset($pageSize * ($page - 1))->limit($pageSize)->get();
        }
        return [
            'total' => $count,
            'data' => $items
        ];
    }
}
