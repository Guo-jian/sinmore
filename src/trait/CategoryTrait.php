<?php

namespace App\Traits;

use App\Models\Category;

trait CategoryTrait
{
    public static function getAllCategory($data)
    {
        if (1 == $data->level) {
            $cate = Category::where('pid', $data->id)->searchCategory(3)->get(['id']);
            $data = [$data->id];
            if ($cate->isNotEmpty()) {
                $cate = $cate->toArray();
                foreach ($cate as $key => $value) {
                    array_push($data, $value['id']);
                    if (false == empty($value['get_category'])) {
                        $data = array_merge($data, array_column($value['get_category'], 'id'));
                    }
                }
            }
        } elseif (2 == $data->level) {
            $cate = Category::where('pid', $data->id)->get(['id']);
            $data = [$data->id];
            if ($cate->isNotEmpty()) {
                array_push($data, array_column($cate->toArray(), 'id'));
            }
        } else {
            $data = [$data->id];
        }
        return $data;
    }
}
