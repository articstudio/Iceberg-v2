<?php
namespace Iceberg\Install;

use Iceberg\Core\Domain;
use Iceberg\Install\Request;
use Iceberg\Core\Session;
use Iceberg\I18N\I18N;
use Iceberg\Install\Theme;
use Iceberg\Date\TimeZone;
use Iceberg\Core\Security;
use Iceberg\Abstracts\ObjectRelation;
use Iceberg\Configuration\Configuration;
use Iceberg\User\User;
use Iceberg\User\Meta AS UserMeta;
use Iceberg\Taxonomy\Taxonomy;

class Install
{
    const MESSAGE_TYPE_SUCCESS = 1;
    const MESSAGE_TYPE_INFO = 2;
    const MESSAGE_TYPE_ERROR = 3;
    
    private static $_MESSAGES = [];
    
    private static $_FINISHED = false;
    
    public static function Initialize()
    {
        if (static::IsInstallationProcess())
        {
            define('ICEBERG_INSTALL', true);
            if (static::IsReInstallationProcess())
            {
                define('ICEBERG_REINSTALL', true);
            }
            
            static::_Load();
            static::_Action();
            
            Theme::Initialize();
            Theme::Template('index.php');
            
            return true;
        }
        return false;
    }
    
    private static function _Load()
    {
        // Request
        Request::Parse();

        // TimeZone
        $timezone = Request::GetVar(Request::KEY_TIMEZONE);
        if ($timezone)
        {
            TimeZone::Set($timezone);
        }

        // Session
        Session::Start('ICEBERG_INSTALL');

        // I18N
        $locale = Request::GetVar(Request::KEY_LANGUAGE);
        if ($locale && I18N::LoadLanguage($locale))
        {
            Session::SetValue(Request::KEY_LANGUAGE, $locale);
        }
    }
    
    private static function _ActionReinstall()
    {
        if (Security::NONCE_Verify(Request::KEY_ACTION_REINSTALL))
        {
            static::$_FINISHED = !static::_UnInstallation();
            (static::$_FINISHED ? static::AddMessage('Uninstall not finished.', 'error') : static::AddMessage('Uninstall finished correctly.', 'success'));
        }
        else
        {
            static::AddMessage('UnInstall security error', 'error');
        }
    }
    
    private static function _ActionInstall()
    {
        if (Security::NONCE_Verify(Request::KEY_ACTION_INSTALL))
        {
            static::$_FINISHED = static::_Installation();
            if (static::$_FINISHED)
            {
                static::AddMessage('Install finished correctly.', 'success');
            }
            else
            {
                static::AddMessage('Install not finished.', 'error');
                (static::_UnInstallation() ? static::AddMessage('Uninstall finished correctly.', 'success') : static::AddMessage('Uninstall not finished.', 'error'));
            }
        }
        else
        {
            static::AddMessage('Install security error', 'error');
        }
    }
    
    private static function _Action()
    {
        $action = Request::GetVar(Request::KEY_ACTION);
        if ($action === Request::KEY_ACTION_REINSTALL)
        {
            static::_ActionReinstall();
        }
        else if ($action === Request::KEY_ACTION_INSTALL)
        {
            static::_ActionInstall();
        }
    }
    
    public static function IsFinished()
    {
        return static::$_FINISHED;
    }
    
    public static function IsInstalled()
    {
        return Domain::DB_TableExists();
    }
    
    public static function IsReInstallationProcess()
    {
        return (defined('ICEBERG_REINSTALL') || Request::GetVar(Request::KEY_ACTION)===Request::KEY_ACTION_REINSTALL);
    }
    
    public static function IsInstallationProcess()
    {
        return (defined('ICEBERG_INSTALL') || static::IsReInstallationProcess() || !static::IsInstalled());
    }
    
    public static function AddMessage($message, $type)
    {
        static::$_MESSAGES[] = [
            'message' => $message,
            'type' => $type
        ];
    }
    
    public static function GetMessages()
    {
        return static::$_MESSAGES;
    }
    
    private static function _Installation()
    {
        $done = false;
        if (static::_CreateDBTables())
        {
            $domain_id = Domain::Insert(Request::GetBaseURL(false));
            if ($domain_id)
            {
                Domain::ResetConfiguration($domain_id);
                $done = true;
            }
        }
        return $done;
    }
    
    private static function _UnInstallation()
    {
        $done = true;
        if (!Domain::DB_DropTable())
        {
            $done = false;
            static::AddMessage('Drop DOMAINS table.', 'error');
        }
        if ($done && !ObjectRelation::DB_DropTable())
        {
            $done = false;
            static::AddMessage('Drop DOMAINS table.', 'error');
        }
        if ($done && !Configuration::DB_DropTable())
        {
            $done = false;
            static::AddMessage('Drop CONFIGURATION table.', 'error');
        }
        if ($done && !User::DB_DropTable())
        {
            $done = false;
            static::AddMessage('Drop USERS table.', 'error');
        }
        if ($done && !UserMeta::DB_DropTable())
        {
            $done = false;
            static::AddMessage('Drop USERS METAS table.', 'error');
        }
        if ($done && !Taxonomy::DB_DropTable())
        {
            $done = false;
            static::AddMessage('Drop TAXONOMIES table.', 'error');
        }
        return $done;
    }
    
    private static function _CreateDBTables()
    {
        $done = true;
        if (!Domain::DB_CreateTable())
        {
            $done = false;
            static::AddMessage('Create DOMAINS table.', 'error');
        }
        if ($done && !ObjectRelation::DB_CreateTable())
        {
            $done = false;
            static::AddMessage('Create OBJECTS RELATIONS table.', 'error');
        }
        if ($done && !Configuration::DB_CreateTable())
        {
            $done = false;
            static::AddMessage('Create CONFIGURATIONS table.', 'error');
        }
        if ($done && !User::DB_CreateTable())
        {
            $done = false;
            static::AddMessage('Create USERS table.', 'error');
        }
        if ($done && !UserMeta::DB_CreateTable())
        {
            $done = false;
            static::AddMessage('Create USERS METAS table.', 'error');
        }
        if ($done && !Taxonomy::DB_CreateTable())
        {
            $done = false;
            static::AddMessage('Create TAXONOMIES table.', 'error');
        }
        return $done;
    }
}