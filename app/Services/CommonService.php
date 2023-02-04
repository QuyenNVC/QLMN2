<?php

namespace App\Services;


class CommonService
{
    public static function convertNumberToAlphabet($number) {
        $alphabet = range('A', 'Z');
        $alphabetTemp = $alphabet;
        for ($i=0; $i < count($alphabetTemp) ; $i++) { 
            $alphabet[] = 'A'.$alphabetTemp[$i];
        }
        return $alphabet[$number];
    }
 }