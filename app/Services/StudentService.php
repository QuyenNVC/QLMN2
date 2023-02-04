<?php

namespace App\Services;

use App\Models\DiemDanh;
use App\Models\HinhThucDiemDanh;
use App\Models\Record;


class StudentService
{
    // months => birthday
    public static function getData($ids, $excludes, $schoolYears, $grades, $class, $months, $status, $pageSize, $page, $toArray = false)
    {
        $items = Record::query();
        $items->with('class');

        if (count($class)) {
            $items->where(function ($query) use ($class) {
                foreach ($class as $classItem) {
                    $query->orWhere('id_class', $classItem);
                }
            });
        }
        if (count($grades)) {
            $items->whereHas('class', function ($query) use ($grades) {
                return $query->where(function ($query2) use ($grades) {
                    foreach ($grades as $gradeItem) {
                        $query2->orWhere('grade', $gradeItem);
                    }
                    return $query2;
                });
            });
        }

        if (count($schoolYears)) {
            $items->whereHas('class', function ($query) use ($schoolYears) {
                return $query->where(function ($query2) use ($schoolYears) {
                    foreach ($schoolYears as $schoolYearItem) {
                        $query2->orWhere('school_year', $schoolYearItem);
                    }
                    return $query2;
                });
            });
        }

        if (count($months)) {
            $items->where(function ($query) use ($months) {
                foreach ($months as $month) {
                    $query->orWhereMonth('birth_date', $month);
                }
            });
        }

        if ($status && $status != -1 && $status != '') {
            $items->where('status', $status);
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

    public static function getDataTimekeeping($class, $grades, $month, $year, $status, $page, $pageSize)
    {
        $items = DiemDanh::whereRaw('month = ? and year = ?', [$month, $year]);
        $items->join('enrollment_records', 'enrollment_records.id', '=', 'diem_danh.ma_hs');
        $items->join('class', 'enrollment_records.id_class', '=', 'class.id');

        if (count($class)) {
            $items->where(function ($query) use ($class) {
                foreach ($class as $classItem) {
                    $query->orWhere('enrollment_records.id_class', $classItem);
                }
            });
        }

        if (count($grades)) {
            $items->where(function ($query) use ($grades) {
                foreach ($grades as $gradesItem) {
                    $query->orWhere('class.grade', $gradesItem);
                }
            });
        }

        if ($status == 3) {
            $items->where('enrollment_records.status', 2);
        }
        $items->select(['diem_danh.*', 'enrollment_records.*', 'class.*', 'enrollment_records.name as name_student', 'enrollment_records.status as enrollment_records_status']);
        $count = $items->get()->count();
        $items = $items->offset($pageSize * ($page - 1))->limit($pageSize)->get()->toArray();
        $attendance = [];
        for ($i = 0; $i < count($items); $i++) {
            $arrayInList = [];
            foreach ((array)json_decode($items[$i]["data"]) as $key => $item) {
                if ($key != 'ma_hs') {
                    $arrayInList[$item][] = $key;
                }
            }

            $goToSchool = '';
            $dontGoToSchool = '';
            $goToSchoolId = [1, 5, 6, 7, 8, 9];

            $haveGoToSchool = false;
            $haveDontGoToSchool = false;

            foreach ($arrayInList as $key => $item) {
                if (in_array($key, $goToSchoolId)) {
                    $haveGoToSchool = true;
                    $goToSchool .= StudentService::getNameAttendanceForm($key) . "(" . count($item) . "): [" . implode(", ", $item) . ']' . PHP_EOL;
                } else {
                    $haveDontGoToSchool = true;
                    $dontGoToSchool .= StudentService::getNameAttendanceForm($key) . "(" . count($item) . "): [" . implode(", ", $item) . ']' . PHP_EOL;
                }
            }

            $goToSchool = trim($goToSchool, PHP_EOL);
            $dontGoToSchool = trim($dontGoToSchool, PHP_EOL);

            $addSub = true;
            if ($status == 1 && !$haveGoToSchool) {
                $addSub = false;
            } else if ($status == 2 && !$haveDontGoToSchool) {
                $addSub = false;
            }
            if ($addSub) {
                $attendance[] = [
                    'student_id' => $items[$i]['ma_hs'],
                    'name' => $items[$i]['name_student'],
                    'data' => (array)json_decode($items[$i]["data"]),
                    'go_to_school' => $goToSchool,
                    'dont_go_to_school' => $dontGoToSchool,
                    'reserve' => $items[$i]["enrollment_records_status"] == 2 ? 'Có' : '',
                ];
            }
        }
        return [
            'total' => $count,
            'data' => $attendance
        ];
    }

    public static function getNameAttendanceForm($id)
    {
        $name = '';
        $item = HinhThucDiemDanh::where('id', $id)->first();
        if ($item) {
            $name = $item->name;
        }
        return $name;
    }
}
