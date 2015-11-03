<?php
namespace Linroid\Wechat;

use Eloquent;
/**
 * WechatUser
 *
 * @property string $openid
 * @property string $token
 * @property boolean $subscribe
 * @property string $nickname
 * @property integer $sex
 * @property string $language
 * @property string $city
 * @property string $province
 * @property string $country
 * @property string $unionid
 * @property string $headimgurl
 * @property integer $subscribe_time
 * @method static \Illuminate\Database\Query\Builder|WechatUser whereOpenid($value)
 * @method static \Illuminate\Database\Query\Builder|WechatUser whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|WechatUser whereSubscribe($value)
 * @method static \Illuminate\Database\Query\Builder|WechatUser whereUid($value)
 * @method static \Illuminate\Database\Query\Builder|WechatUser whereNickname($value)
 * @method static \Illuminate\Database\Query\Builder|WechatUser whereSex($value)
 * @method static \Illuminate\Database\Query\Builder|WechatUser whereLanguage($value)
 * @method static \Illuminate\Database\Query\Builder|WechatUser whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|WechatUser whereProvince($value)
 * @method static \Illuminate\Database\Query\Builder|WechatUser whereCountry($value)
 * @method static \Illuminate\Database\Query\Builder|WechatUser whereHeadimgurl($value)
 * @method static \Illuminate\Database\Query\Builder|WechatUser whereSubscribeTime($value)
 */
class WechatUser extends Eloquent{
    protected $fillable =
        ['province', 'openid', 'subscribe', 'nickname', 'sex', 'language', 'city', 'province','country', 'unionid', 'headimgurl', 'subscribe_time'];
    protected $primaryKey = 'openid';
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'wechat_users';

    public static function findOrCreate($openid){
        $wechatUser = WechatUser::find($openid);
        if(!$wechatUser){
            $api = WechatApi::create();
            $info = $api->getUserInfo($openid);
            if(!$info){
                throw new \Exception("用户不存在");
            }
            $wechatUser = new WechatUser($info);
            $wechatUser->token = str_random(32);
            $wechatUser->save();
        }
        return $wechatUser;
    }
    public function isSubscribed(){
        return $this->subscribe == 1;
    }
}
