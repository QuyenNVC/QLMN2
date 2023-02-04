<?php

namespace App\Http\Controllers\helpers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HelperController extends Controller
{
    public function string_filter_helper ($str){
        $str = trim(strtolower($str));
        $unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
			// 'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            // 'D'=>'Đ',
            // 'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            // 'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            // 'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            // 'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            // 'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        
       foreach($unicode as $nonUnicode=>$uni){
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
       }
		return $str;
    }

    public function format_month_year_helper($date) {
        // var_dump($date);
        // var_dump(Carbon::parse($date));
        return [Carbon::parse($date)->addDays(1)->format('m'), Carbon::parse($date)->addDays(1)->format('Y')];
    }

    public static function arraySelected($items = [], $selected = [], $excludes = []) {
        $selectAll = array_search(-1, $selected);
        $arrays = [];
        if($selectAll !== false) {
            foreach($items as $item) {
                if(array_search($item['id'], $excludes) === false) {
                    $arrays[] = $item['id'];
                }
            }
        } else {
            $arrays = $selected;
        }
        return $arrays;
    }
}
