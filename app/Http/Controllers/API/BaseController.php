<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\Request;

class BaseController extends Controller
{
    // API接口响应
    use ApiResponse;

    /**
     * @var 请求类
     */
    public $request;

    /**
     * @var 所有参数
     */
    public $params;

    public function __construct()
    {
        $this->request = request();
        $this->params = $this->getAllParams();
    }


    /**默认获取所有参数
     * @return mixed
     */
    protected function getAllParams()
    {
        $params = $this->request->all();
        $params['limit'] = $this->request->input('limit',10);
        $params['page'] = $this->request->input('page',1);
        return $params;
    }

}
