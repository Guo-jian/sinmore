<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    public function getGroup()
    {
        return $this->hasOne('App\Models\Group', 'id', 'group_id');
    }

    public function scopeSearchGroup($query,$select)
    {
        return $query->with(['getGroup'=>function ($query) use($select) {
            $query->select($select);
        }]);
    }
}
