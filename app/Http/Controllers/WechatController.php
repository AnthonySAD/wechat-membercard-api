<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Exceptions\ErrorCodes;
use App\Services\AccessTokenService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;


class WechatController extends Controller
{
    public $expires = 86400;
    private $accessTokenService;

    public function __construct(AccessTokenService $accessTokenService)
    {
        parent::__construct();
        $this->accessTokenService = $accessTokenService;
    }

    public function login(Request $request)
    {
        if (!$request->input('code')){
            throw new ApiException(ErrorCodes::BAD_REQUEST, 'invalid code');
        }

        $userDate = app('wechat.mini_program')->auth->session($request->input('code'));
        if (!isset($userDate['openid'])){
            throw new ApiException(ErrorCodes::BAD_REQUEST);
        }

        $user = User::where('openid', $userDate['openid'])->first();
        if (!$user){
            $user = User::create(['openid'=>$userDate['openid']]);
        }

        Redis::setex('login_user_key_' . $user->id, 600, $userDate['session_key']);
        $token = (string) Str::uuid();
        $this->accessTokenService->addRedisWechatUserLoginRecord($user->id, $token);
        Redis::setex('login_user_' . $token, $this->expires, $user->id);
        return $this->ok(['auth_token'=>$token]);
    }

    public function userInfoUpdate(Request $request)
    {
        $iv = $request->input('iv');
        if (!$iv){
            throw new ApiException(ErrorCodes::BAD_REQUEST, 'invalid iv');
        }
        $encryptData = $request->input('encryptdata');
        $session_key = Redis::get('login_user_key_'. 1);
        $userInfo = app('wechat.mini_program')->encryptor->decryptData($session_key, $iv, $encryptData);
        $user = User::where('openid',$userInfo['openId'])->first();
        $user->name = $userInfo['nickName'];
        $user->avatar = $userInfo['avatarUrl'];

        $user->language = $userInfo['language'];
        $user->city = $userInfo['city'];
        $user->province = $userInfo['province'];
        $user->country = $userInfo['country'];
        $user->gender = $userInfo['gender'];
        $user->save();

        return $this->ok(['nickname'=>$userInfo['nickName'], 'avatar'=>$userInfo['avatarUrl']]);
    }
}
