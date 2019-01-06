<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckLogin;
use App\Http\Responses\ApiResponse;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="会员卡包API文档",
 *     description="该项目基于laravel5.6开发",
 *     @OA\Contact(
 *         name="Anthony",
 *         email="adshen@google.com"
 *     ),
 * )
 *
 * @OA\Server(
 *     url="http://card.test/api",
 *     description="本地服务器"
 * )
 *
 * @OA\Server(
 *     url="https://membercard.adshen.top/api",
 *     description="线上服务器"
 * )
 *
 */

class Controller extends BaseController
{
    use ApiResponse;
    public function __construct()
    {
        $except = ['login'];
        $this->middleware(CheckLogin::class, ['except' => $except]);
    }
}
