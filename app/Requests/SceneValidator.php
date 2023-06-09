<?php

namespace App\Requests;

use App\Enums\ResponseEnum;
use App\Helpers\ApiResponse;

trait SceneValidator
{
    use ApiResponse;
    //场景
    protected $scene = null;

    //是否自动验证
    protected $autoValidate = true;

    protected $onlyRule=[];


    /**
     *  覆盖 ValidatesWhenResolvedTrait 下 validateResolved 自动验证
     */
    public function validateResolved()
    {
        if(method_exists($this,'autoValidate')){
            $this->autoValidate = $this->container->call([$this, 'autoValidate']);
        }
        if ($this->autoValidate) {
            $this->handleValidate();
        }
    }

    /**
     * 复制 ValidatesWhenResolvedTrait -> validateResolved 自动验证
     */
    protected function handleValidate($allErrorMessage = false)
    {
        $this->prepareForValidation();

        if (! $this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        $instance = $this->getValidatorInstance();

        //验证错误
        if ($instance->fails()) {
            $errorMessage = '';
            if($allErrorMessage === true){
                foreach ($instance->errors()->all() as $key => $message){
                    $errorMessage .= ($key +1) .':' .$message  . ' ';
                }
                $errorMessage = rtrim($errorMessage);

            }else{
                $errorMessage = $instance->errors()->first();
            }
            //默认返回第一条错误
            $this->throwBusinessException(ResponseEnum::CLIENT_PARAMETER_ERROR,$errorMessage);
        }
    }


    /**
     * 定义 getValidatorInstance 下 validator 验证器
     * @param $factory
     * @return mixed
     */
    public function validator($factory)
    {
        return $factory->make($this->validationData(), $this->getRules(), $this->messages(), $this->attributes());
    }


    /**验证方法（关闭自动验证时控制器调用）
     * @param string $scene 场景名称 或 验证规则
     * @param int $allErrorMessage  是否显示全部错误信息
     * @return void
     */
    public function validate($scene='',$allErrorMessage=false)
    {
        if(!$this->autoValidate){
            if(is_array($scene)){
                $this->onlyRule = $scene;
            }else{
                $this->scene = $scene;
            }
            $this->handleValidate($allErrorMessage);
        }
    }

    /**
     * 获取 rules
     * @return array
     */
    protected function getRules()
    {
        return $this->handleScene($this->container->call([$this, 'rules']));
    }

    /**
     * 场景验证
     * @param array $rule
     * @return array
     */
    protected function handleScene(array $rule)
    {
        if($this->onlyRule){
            return $this->handleRule($this->onlyRule,$rule);
        }
        $sceneName = $this->getSceneName();
        if($sceneName && method_exists($this,'scene')){
            $scene = $this->container->call([$this, 'scene']);
            if(array_key_exists($sceneName,$scene)) {
                return $this->handleRule($scene[$sceneName],$rule);
            }
        }
        return  $rule;
    }

    /**
     * 处理Rule
     * @param $sceneRule
     * @param $rule
     * @return array
     */
    private function handleRule(array $sceneRule,array $rule)
    {
        $rules = [];
        foreach ($sceneRule as $key => $value) {
            if (is_numeric($key) && array_key_exists($value,$rule)) {
                $rules[$value] = $rule[$value];
            } else {
                $rules[$key] = $value;
            }
        }
        return $rules;
    }

    /**
     * 获取场景名称
     *
     * @return string
     */
    protected function getSceneName()
    {
        return is_null($this->scene) ? $this->route()->getAction('_scene') : $this->scene;
    }
}
