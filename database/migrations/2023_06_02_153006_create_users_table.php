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
            $table->string('account', 64)->comment('账号');
            $table->string('password')->comment('密码');
            $table->string('register_address')->comment('注册地址');
            $table->string('last_location')->default('')->comment('用户最后登录位置');
            $table->unsignedInteger('last_ip')->comment('用户最后登录IP');
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
