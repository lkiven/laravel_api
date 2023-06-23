<?php

namespace App\Http\Requests;

use App\Models\User;
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
            'username' => 'required|string|min:3|max:32',
            'password' => 'required|string|min:6|max:16|alpha_dash',
            'sex'      => 'required',
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
                'username' => 'required|unique:users|string|min:3|max:32', //可以重置规则
                'password',
                'sex'      => 'required|in:' . User::SEX_WOMAN . ',' . User::SEX_MAN . ',' . User::SEX_HIDDEN
            ],
            //登录
            'login'    => [
                'username',
                'password',
            ],

            //修改密码
            'update'   => [
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
            'username.required'   => '用户名不能为空',
            'password.required'   => '密码不能为空',
            'password.alpha_dash' => '密码只能是：字母和数字，以及破折号和下划线',
            'sex.required'        => '性别不能为空',
        ];
    }

}

