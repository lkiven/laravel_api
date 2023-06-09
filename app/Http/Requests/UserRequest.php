<?php

namespace App\Http\Requests;
use App\Requests\FormRequest;

class UserRequest extends FormRequest
{
    protected $autoValidate = false;

    /**验证规则
     * @return string[]
     */
    public function rules()
    {
        return [
            'account' => 'required|string',
            'password' => 'required|string|min:6|max:16|alpha_dash',
        ];
    }

    /**场景验证需要验证的字段
     * @return \string[][]
     */
    public function scene()
    {
        return [
            //注册
            'register' => [
                'account'=>'required|unique:users', //可以重置规则
                'password',
            ],
            //修改密码
            'update' => [
                'password'
            ],
        ];
    }

    /**
     * 获取已定义验证规则的错误消息。
     *
     * @return array
     */
    public function messages()
    {
        return [
            'account.required' => '账号不能为空',
            'password.required'  => '密码不能为空',
            'password.alpha_dash'  => '密码只能是：字母和数字，以及破折号和下划线',
        ];
    }

}

