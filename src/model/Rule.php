<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    public function getRule()
    {
        return $this->hasMany($this, 'pid', 'id');
    }

    public function scopeSearchRule($query, $level = 0)
    {
        return $query->with(['getRule'=>function ($query) use ($level) {
            $query->where('type', $level)->where('status', 1)->select('id', 'pid', 'title', 'path')->when(2 == $level, function ($query) use ($level) {
                $query->searchRule(3);
            });
        }]);
    }
}
