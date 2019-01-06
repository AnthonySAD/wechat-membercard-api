<?php
/**
 * Created by PhpStorm.
 * User: Pengjisoft-0032
 * Date: 2018/11/28
 * Time: 16:03
 */

namespace App\Services;

use Illuminate\Support\Str;
use Redis;

class AccessTokenService
{
    public function addRedisWechatUserLoginRecord($userId, $token)
    {
        $oldToken = Redis::hget('login_wechat_users_records',$userId);
        if ($oldToken){
            Redis::del('login_user_'. $oldToken);
        }
        Redis::hset('login_wechat_users_records', $userId, $token);
    }

}