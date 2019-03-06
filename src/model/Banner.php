<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    public function getType()
    {
        return $this->hasMany('App\Models\BannerType');
    }

    public function scopeSearchType($query)
    {
        return $query->with(['getType'=>function ($query) {
            return $query->select('banner_id', 'type');
        }]);
    }

    public function scopeSearchHasType($query, $type)
    {
        return $query->whereHas('getType', function ($query) use ($type) {
            $query->where('type', $type);
        });
    }
}
