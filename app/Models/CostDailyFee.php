<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CostDailyFee extends Model
{
    use HasFactory;
    protected $table = 'class_daily_fee';
    protected $primaryKey = ['id_class', 'id_type'];
    public $timestamps = false;
    public $incrementing = false;
    
    protected function setKeysForSaveQuery($query) {
        $keys = $this->getKeyName();
        if(!is_array($keys)) {
            return parent::setKeysForSaveQuery($query);
        }

        foreach($keys as $keyName) {
            $query->where($keyName, '=', $this->getKeysForSaveQuery($keyName));
        }

        return $query;
    }

    protected function getKeyForSaveQuery($keyName = null) {
        if(is_null($keyName)) {
            $keyName = $this->getKeyName();;
        }

        if (isset($this->original[$keyName])) {
            return $this->original[$keyName];
        }
     
        return $this->getAttribute($keyName);
    }
}
