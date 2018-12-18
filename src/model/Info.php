<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    public function getCategory()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }

    public function scopeSearchCategory($query)
    {
        return $query->with(['getCategory'=>function ($query) {
            $query->select('id', 'pid', 'name')->searchParent(3);
        }]);
    }
}
