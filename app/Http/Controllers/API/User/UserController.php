<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\API\BaseController;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{
    /**用户注册
     * @return void
     */
    public function register(UserRequest $userRequest)
    {
        $params = $userRequest->all();
        //验证表单
        $userRequest->validate('register');
    }

    /**用户列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->success(User::all());
    }

}
