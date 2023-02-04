<?php
namespace App\Services;
use Carbon\Carbon;
use App\Models\SchoolYear;

class DateService {
    public static function monthBetweenTwoDay($dateStart, $dateEnd) {
        $dateStart = strtotime($dateStart);
        $dateEnd = strtotime($dateEnd);

        $year1 = date('Y', $dateStart);
        $year2 = date('Y', $dateEnd);

        $month1 = date('m', $dateStart);
        $month2 = date('m', $dateEnd);

        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
        return $diff;
    }

    public static function defineSchoolYear($date) {
        $date = Carbon::parse($date);
        $month = $date->format('m');
        $year = $date->format('Y');
        if($month <= 6) {
            $year = $year - 1;
        }
        $schoolYear = SchoolYear::where('name', (string)$year)->first();
        return $schoolYear;
    }
}