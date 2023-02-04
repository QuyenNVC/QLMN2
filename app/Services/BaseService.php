<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Service\AddLog;

class BaseService
{
    protected static $model;
    protected static $tbl;
    protected static $primakyKey = 'id';

    public static function getData($request) {
        $items = static::$model::query();
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

    public static function store($request) {
        $item = new static::$model();
        foreach($request->all() as $key => $value) {
            $item->$key = $value;
        }
        if(Auth::user()->cannot('save', $item)) {
            abort(403);
        }
        $item->save();
        AddLog::addLog('create', static::$tbl, $item->{static::$primakyKey}, $item->name, Auth::id());
        return $item->id;
    }

    public static function update($request) {
        $item = static::$model::find($request->{static::$primakyKey});
        if(!$item) {
            Abort(404);
        }
        foreach($request->all() as $key => $value) {
            $item->$key = $value;
        }
        $item->save();
        AddLog::addLog('update', static::$tbl, $item->{static::$primakyKey}, $item->name, Auth::id());
        return $item->id;
    }

    public static function destroy($item) {
        if(Auth::user()->cannot('save', $item)) {
            abort(403);
        }
        AddLog::addLog('delete', static::$tbl, $item->{static::$primakyKey}, $item->name, Auth::id());
        $item->delete();
    }
}
