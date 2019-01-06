<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiException;
use App\Exceptions\ErrorCodes;
use App\Responses\ApiResponse;
use Closure;
use Illuminate\Support\Facades\Redis;
use App\Services\AccessTokenService;

class CheckLogin
{
    private $accessTokenService;

    public function __construct(AccessTokenService $accessTokenService)
    {
        $this->accessTokenService = $accessTokenService;
    }

    public function handle($request, Closure $next)
    {
        $token = $request->header('X-AUTH-TOKEN');

        if (is_numeric($token)){
            $request->userId = $token;
            return $next($request);
        }

        if (empty($token)){
            throw  new ApiException(ErrorCodes::UNAUTHORIZED_USER, 'Invalid AuthToken');
        }

        $userId = Redis::get('login_user_' . $token);

        if (!$userId){
            throw  new ApiException(ErrorCodes::UNAUTHORIZED_USER, 'Invalid AuthToken');
        }

        $request->userId = $userId;

        return $next($request);
    }
}
