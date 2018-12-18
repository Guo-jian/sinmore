<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function getCategory()
    {
        return $this->hasMany($this, 'pid', 'id');
    }

    public function scopeSearchCategory($query, $level = 0)
    {
        return $query->with(['getCategory'=>function ($query) use ($level) {
            $query->orderBy('sort', 'desc')
                ->orderBy('created_at', 'desc')
                ->select('id', 'name', 'sort', 'hot', 'status', 'pid')
                ->when(2 == $level, function ($query) use ($level) {
                    $query->searchCategory(3);
                });
        }]);
    }

    public function getParent()
    {
        return $this->hasOne($this, 'id', 'pid');
    }

    public function scopeSearchParent($query, $level = 2)
    {
        return $query->with(['getParent'=>function ($query) use ($level) {
            if ($level == 3) {
                return $query->select('id', 'pid', 'name')->searchParent(2);
            }
            return $query->select('id', 'pid', 'name');
        }]);
    }
}
