<?php

namespace App\Traits\Wechat;

use EasyWeChat\Factory;

trait MiniTrait
{
    public static function setMiniApp()
    {
        return Factory::miniProgram(array_merge(config('wechat.mini_program.default'),config('wechat.defaults')));
    }
}
