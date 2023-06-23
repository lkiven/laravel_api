<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $account 账号
 * @property string $password 密码
 * @property string $register_address 注册地址
 * @property string $last_location 用户最后登录位置
 * @property int $last_ip 用户最后登录IP
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRegisterAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable  implements JWTSubject
{
    use Notifiable;

    /**自动写入时间
     * @var bool
     */
    public $timestamps = true;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', //账号
        'password', //密码
        'sex', //性别
        'register_address', //状态：1启用、0禁用
        'status', //状态
        'last_num', //用户登录次数
        'last_location', //用户最后登录位置
        'last_login_time', //用户最后登录时间
        'last_ip', //用户最后登录IP
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * 登录状态  1正常 0启用
     */
     const STATUS_FALSE= 0;
     const STATUS_TRUE = 1;

    /**
     * 性别  0女  1男  2保密
     */
    const SEX_WOMAN = 0;
    const SEX_MAN = 1;
    const SEX_HIDDEN= 2;



    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * 确认密码是否正确
     *
     * @param string $password
     * @return bool
     */
    public static function verifyPassword(string $password,string $dbPassword): bool
    {
        return Hash::check($password, $dbPassword);
    }

}
