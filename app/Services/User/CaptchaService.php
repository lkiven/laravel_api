<?php

namespace App\Services\User;

use App\Services\BaseService;

class CaptchaService extends BaseService
{
    /**验证验证码
     * @param $captcha   验证码
     * @param $key    key值
     * @return void
     */
    public function validateCode(string $captcha, string $key)
    {
        if(!captcha_api_check($captcha,$key)){
            $this->error('验证码输入错误或已过期');
        }
        return true;
    }
}
