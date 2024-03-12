<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\SystemLevel;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'unique_id',
        'username',
        'real_name',
        'email',
        'password',
        'avatar',
        'balance',
        'bonus',
        'requery',
        'score_level',
        'score_level_current',
        'level',
        'ip',
        'is_admin',
        'superadmin',
        'isDeveloper',
        'is_lowadmin',
        'is_moder',
        'is_youtuber',
        'fake',
        'time',
        'banchat',
        'banchat_reason',
        'ban',
        'ban_reason',
        'link_trans',
        'link_reg',
        'ref_id',
        'ref_money',
        'ref_money_all',
        'cpf',
        'zip_code',
        'street_namem',
        'street_number',
        'neighborhood',
        'city',
        'federal_unit',
        'slots',
        'number_phone'
    ];

    protected $hidden = ['remember_token'];

    static function getUser($id)
    {
        $user = self::select('username', 'email', 'avatar', 'unique_id')->where('id', $id)->first();
        return $user;
    }

    static function findRef($id)
    {
        $user = self::select('id', 'username', 'avatar', 'unique_id')->where('unique_id', $id)->first();
        return $user;
    }

    static function getUserScore($id)
    {
        $user = self::select('id', 'username', 'avatar', 'unique_id', 'score_level')->where('unique_id', $id)->first();
        return $user;
    }

    static function generateRef($length = 11): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    static function passEncrypt($password): string
    {
        $privateKey = 'AA74CDCC2BBRT935136HH7B63C27'; // user define key
        $secretKey = env('PASSWORD_SALT_KEY'); // user define secret key
        $encryptMethod = "AES-256-CBC";

        $key = hash('sha256', $privateKey);
        $ivalue = substr(hash('sha256', $secretKey), 0, 16); // sha256 is hash_hmac_algo
        $result = openssl_encrypt($password, $encryptMethod, $key, 0, $ivalue);
        return base64_encode($result);  // output is a encripted value
    }

    static function passDecrypt($password)
    {
        $privateKey = 'AA74CDCC2BBRT935136HH7B63C27'; // user define key
        $secretKey = env('PASSWORD_SALT_KEY'); // user define secret key
        $encryptMethod = "AES-256-CBC";

        $key = hash('sha256', $privateKey);
        $ivalue = substr(hash('sha256', $secretKey), 0, 16); // sha256 is hash_hmac_algo

        return openssl_decrypt(base64_decode($password), $encryptMethod, $key, 0, $ivalue);
        // output is a decripted value
    }

    public function userLevel() {
        return $this->hasMany('App\SystemLevel');
    }
}
