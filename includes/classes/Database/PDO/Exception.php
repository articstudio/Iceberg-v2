<?php
namespace Iceberg\Database\PDO;

class Exception extends \Iceberg\Database\Exception
{
    /*
    public static function Report($message='')
    {
        parent::Report(
            sprintf("PDO ERROR: %s\n<br><br>\nReported by: http://%s%s\n<br><br>Refering page: %s<br><br>", $message, $_SERVER["HTTP_HOST"], $_SERVER["REQUEST_URI"], (isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : ''))
        );
    }
    */
}

