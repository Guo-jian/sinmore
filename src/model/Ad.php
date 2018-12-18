<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    public function getType()
    {
        return $this->hasMany('App\Models\AdType');
    }

    public function scopeSearchType($query)
    {
        return $query->with(['getType'=>function ($query) {
            return $query->select('ad_id', 'type');
        }]);
    }

    public function scopeHasType($query, $type)
    {
        return $query->whereHas('getType', function ($query) use ($type) {
            return $query->where('type', $type);
        });
    }
}
