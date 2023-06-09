<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;

class BaseController extends Controller
{
    // API接口响应
    use ApiResponse;
}
