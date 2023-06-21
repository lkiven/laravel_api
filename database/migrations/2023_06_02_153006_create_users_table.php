<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUsersTable extends Migration
{
    private const TABLE = 'users';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::TABLE, function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('username', 32)->comment('账号');
            $table->string('password',128)->comment('密码');
            $table->tinyInteger('sex')->default(2)->comment('性别：0女、1男、2保密');
            $table->string('register_address',100)->default('')->comment('注册地址');
            $table->tinyInteger('status')->default(1)->comment('状态：1启用、0禁用');
            $table->unsignedInteger('login_num')->default(0)->default(0)->comment('用户登录次数');
            $table->string('last_location',100)->default('')->comment('用户最后登录位置');
            $table->dateTime('last_login_time')->default(null)->nullable()->comment('用户最后登录时间');
            $table->string('last_ip',15)->default('')->comment('用户最后登录IP');
            $table->timestamps();
        });
        DB::statement('ALTER TABLE ' . self::TABLE . ' COMMENT "用户表"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE);
    }
}
