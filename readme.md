### 版本 ###
* laravel 6.0
* Nginx 1.8+
* PHP 7.2.5+
* Mysql 5.7+

### 环境要求 ###
* PHP >= 7.2.5
* BCMath PHP 拓展
* Ctype PHP 拓展
* JSON PHP 拓展
* Mbstring PHP 拓展
* OpenSSL PHP 拓展
* PDO PHP 拓展
* Tokenizer PHP 拓展
* XML PHP 拓展

### 项目部署 ###

1. git clone https://github.com/lkiven/laravel_api.git
2. composer install &emsp;  (注意：线上环境 composer install --no-dev)
3. cp .env.example .env
4. php artisan key:generate
5. php artisan migrate




### 目录结构 ###
* app 目录
* &emsp;  Console - 自定义的 Artisan 命令目录
* &emsp;  Enums - 枚举目录
* &emsp;  Exceptions - 异常处理目录
* &emsp;  Helpers - 自定义组手或者函数类
* &emsp;  Http - 包含控制器、中间件以及表单请求等目录
* &emsp;  Jobs - 队列任务目录
* &emsp;  Listeners - 事件监听目录
* &emsp;  Models - 模型目录
* &emsp;  Observers - 模型观察者目录
* &emsp;  Providers - 服务提供者目录
* &emsp;  Requests - 表单验证目录
* &emsp;  Services - 业务服务类目录
* config 目录
* &emsp;  cors.php - 跨域请求文件配置
* &emsp;  logging.php - 日志文件生成配置

* route 目录
* &emsp; api - 路由目录
* &emsp;&emsp; *.php - 包含所有php文件的路由


### 项目说明 ###
