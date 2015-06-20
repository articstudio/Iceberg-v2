<?php
namespace Iceberg\Core;

use Iceberg\Core\Request;

class Security
{
    
    public static function Hash($string, $salt=null, $cost=10)
    {
        if ($salt)
        {
            return password_hash($string, PASSWORD_BCRYPT, ['salt'=>$salt, 'cost'=>  intval($cost)]);
        }
        return password_hash($string, PASSWORD_DEFAULT);
    }
    
    public static function Verify($string, $hash)
    {
        return password_verify($string , $hash);
    }
    
    public static function NONCE($string)
    {
        return static::Hash($string, ICEBERG_NONCE_SALT);
    }
    
    public static function NONCE_Input($string)
    {
        echo '<input type="hidden" name="_nonce" value="' . static::NONCE($string) . '">';
    }
    
    public static function NONCE_Verify($string)
    {
        return static::Verify($string, Request::GetValueGP('_nonce'));
    }
    
    public static function Auth($string)
    {
        return static::Hash($string, ICEBERG_AUTH_SALT);
    }
    
    public static function Session($string)
    {
        return static::Hash($string, ICEBERG_SESSION_SALT);
    }
}