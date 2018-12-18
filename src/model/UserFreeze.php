<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFreeze extends Model
{
    public function getAdmin()
    {
        return $this->hasOne('App\Models\Admin', 'id', 'admin_id');
    }

    public function scopeSearchAdmin($query)
    {
        return $query->with(['getAdmin'=>function ($query) {
            return $query->select('id', 'name');
        }]);
    }
}
