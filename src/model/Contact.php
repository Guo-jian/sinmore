<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    public function getUser()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public function getAdmin()
    {
        return $this->hasOne('App\Models\Admin','id','admin_id');
    }

    public function scopeSearchUser($query)
    {
        return $query->with(['getUser'=>function($query){
            $query->select('id','name');
        }]);
    }

    public function scopeSearchAdmin($query)
    {
        return $query->with(['getAdmin'=>function($query){
            $query->select('id','name');
        }]);
    }
}
