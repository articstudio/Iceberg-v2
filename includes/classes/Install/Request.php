<?php
namespace Iceberg\Install;

class Request extends \Iceberg\Core\Request
{
    const KEY_ACTION_INSTALL = 'iceberg-install';
    const KEY_ACTION_REINSTALL = 'iceberg-reinstall';
    const KEY_TIMEZONE = 'timezone';
    const KEY_STEP = 'step';
    
    protected static $_DEFAULT_VARS = [
       'timezone' => null,
       'username' => null,
       'email' => null,
       'password' => null
    ];
    
}