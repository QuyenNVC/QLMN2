<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service\AddLog;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    protected static $service;
    protected static $keyItems = 'items';
    protected static $model;
    public static function getList(Request $request) {
        try {
            $result = static::$service::getData($request);
            $data = [
                'status' => true,
                static::$keyItems => $result['items'],
                'total' =>  $result['total']
            ];
            return json_encode($data);
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => $e->getMessage()
            ];
            return json_encode($data);
        }
    }

    public static function store(Request $request) {
        try {
            static::$service::store($request);
            $data = [
                'status' => true,
            ];
            return json_encode($data);
        
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => 'Lỗi server'
            ];
            return json_encode($data);
        }
    }

    public static function update(Request $request) {
        try {
            static::$service::update($request);
            $data = [
                'status' => true,
            ];
            return json_encode($data);
        }
        catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => 'Lỗi server'
            ];
            return json_encode($data);
        }
        
    }

    public static function destroy($item) {
        
        try {
            $item = static::$model::findOrFail($item);
            static::$service::destroy($item);
            $data = [
                'status' => true,
            ];
            return json_encode($data);
        } catch (Exception $e) {
            $data = [
                'status' => false,
                'message' => 'Lỗi server'
            ];
            return json_encode($data);
        }
    }
}
