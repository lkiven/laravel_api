<?php

namespace App\Http\Middleware;

use App\Enums\ResponseEnum;
use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Support\Facades\Cache;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtAuthApi
{
    use ApiResponse;

    public function handle($request, Closure $next)
    {
        try {
            //TODO 以下检测token是否是有效的
            //此处不会查询数据库
            if(! JWTAuth::parseToken()->check()){
                //此处token无效，不会查询数据库，
                return $this->fail(ResponseEnum::JWT_TOKEN_INVALID_ERROR);
            }
            //此处会查询一次数据库
            if(! $user = JWTAuth::parseToken()->authenticate()){
                //没有此用户
                return $this->fail(ResponseEnum::JWT_TOKEN_NOT_USER_ERROR);
            }
            //通过中间件传递用户对象，防止数据库多次查询用户(相当于用模型 select )
           $request->user = $user;

        } catch (TokenExpiredException $e) {
            //token过期
            return $this->fail(ResponseEnum::JWT_TOKEN_EXPIRED_ERROR);

        }  catch (TokenBlacklistedException $exception) {
            // token 令牌被拉黑
            return $this->fail(ResponseEnum::JWT_TOKEN_BLACK_ERROR);

        } catch (TokenInvalidException $e) {
            //token无效
            return $this->fail(ResponseEnum::JWT_TOKEN_INVALID_ERROR);

        } catch (JWTException $e) {
            //缺少token
            return $this->fail(ResponseEnum::JWT_TOKEN_NONE_ERROR);
        }
        return $next($request);
    }


}
