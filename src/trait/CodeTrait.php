<?php

namespace App\Traits;

use Overtrue\EasySms\EasySms;

trait CodeTrait
{
    public static function setCodeApp()
    {
        return new EasySms(config('code'));
    }
}
