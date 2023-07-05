<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use Mews\Captcha\Facades\Captcha;

class CaptchaController extends BaseController
{
    /**API生成验证码
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateVerifyCode()
    {
        return $this->success(Captcha::create('default',true));
    }

}
