<?php

namespace App\Traits\Wechat;

use EasyWeChat\Factory;

trait OfficialAccountTrait
{
    public static function setOfficialAccountApp()
    {
        return Factory::officialAccount(config('wechat.official_account.default'));
    }
}
