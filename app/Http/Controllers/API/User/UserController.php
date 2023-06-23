<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Cache;

class UserController extends BaseController
{
    /**用户服务类
     * @var UserService
     */
    public $userService;


    /**用户请求验证类
     * @var UserRequest
     */
    public $userRequest;


    public function __construct(
        UserRequest $userRequest,
        UserService $userService
    ){
        parent::__construct();
        $this->userRequest = $userRequest;
        $this->userService = $userService;
    }

    /**用户登录
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        //验证参数
        $this->userRequest->validate('login');
        //获取需要的参数
        $params = $this->params;
        $params['ip'] = $this->request->ip();
        //调用userService的登录方法
        $data = $this->userService->login($params);
        return $this->success($data);
    }


    /**用户注册
     * @return void
     */
    public function register()
    {
        //验证表单
        $this->userRequest->validate('register');
        //获取需要的参数
        $params = $this->params;
        $params['ip'] = $this->request->ip();
        $this->userService->register($params);
        return $this->success();

    }

    /**退出登录
     * @return void
     */
    public function loginOut()
    {
        auth('api')->logout();
        return $this->success();
    }

    /**用户信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfo()
    {
        $user = $this->userService->getInfo($this->request->user->id);
        return $this->success($user);
    }


}
