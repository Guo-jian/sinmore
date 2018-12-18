<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    public function scopeSearchUser($query)
    {
        return $query->with(['getUser'=>function ($query) {
            $query->select('id', 'name', 'mobile', 'password', 'expired_at', 'status', 'froze_at', 'froze_days', 'token');
        }]);
    }

    public function getUser()
    {
        return $this->hasOne('App\Models\User', 'mobile', 'mobile');
    }
}
