<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Service\AddLog;

class BaseModel extends Model
{
    use HasFactory;
    protected $table;
    public static function booted() {
        parent::booted();
        static::saved(function($item) {
            AddLog::addLog('updated', self::$table, $item->id, $item->name, Auth::id());
        });
    }
}
