<?php

namespace App\Service;
use App\Models\NhatKyHoatDong;

class AddLog {
    public static function addLog($action, $tableName, $id_table, $value_name_table, $id_user) {
        $logItem = new NhatKyHoatDong();
        $logItem->action = $action;
        $logItem->table_name = $tableName;
        $logItem->id_table = $id_table;
        $logItem->value_name_table = $value_name_table;
        $logItem->id_user = $id_user;
        $logItem->save();
    }
}