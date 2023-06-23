<?php

namespace App\Services\User;

use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService extends BaseService
{
    /**用户登录
     * @param $params
     * @return array|void
     */
    public function login($params)
    {
        $user = User::query()->select(['id', 'username', 'password', 'status', 'login_num'])->where('username', $params['username'])->first();
        //验证账户
        if (!$user) {
            $this->error('用户不存在');
        }
        //验证密码
        if (!User::verifyPassword($params['password'], $user->password)) {
            $this->error('用户密码错误');
        }

        //验证登录状态
        if (empty($user->status)) {
            $this->error('该账户已被禁用');
        }

        //记录最后一次登录的时间和ip地址
        User::query()->where('id', $user->id)->update([
            'login_num'       => $user->login_num + 1, //登录次数
            'last_login_time' => date_customize_format(),  //最后登录时间
            'last_location'   => ip_change_location($params['ip'], true), //最后登录地址
            'last_ip'         => $params['ip'] //最后登录ip
        ]);

        return [
            'token'      => auth('api')->login($user),
            'token_type' => 'Bearer', //这个拼接上token就是（中间有空格） header中的Authorization
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ];
    }

    /**用户注册
     * @param $params
     * @return User|\Illuminate\Database\Eloquent\Model
     */
    public function register($params)
    {
        $insertData = [
            'username'         => $params['username'],
            'password'         => Hash::make($params['password']),
            'sex'              => $params['sex'],
            'register_address' => ip_change_location($params['ip'], true),
        ];
        return User::create($insertData);
    }

    /**
     * 用户基本信息
     */
    public function getInfo($userId)
    {
        $user = User::query()->where('id', $userId)->first();
        return $user;
    }

}
