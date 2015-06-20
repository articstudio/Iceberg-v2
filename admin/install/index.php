<?php
if (!defined('ICEBERG_INSTALL'))
{
    die(':)');
}

use Iceberg\Install\Install;
use Iceberg\Install\Theme;
use Iceberg\Install\Request;
use Iceberg\I18N\I18N;
use Iceberg\Core\Bootstrap;
use Iceberg\Template\Template;
use Iceberg\Date\TimeZone;
use Iceberg\Core\Security;

$messages = Install::GetMessages();
$locales = I18N::GetDefaultLocales();
$active_locale = I18N::GetLocale();
$errors_count = 0;
$timezones = TimeZone::GetList();
$active_timezone = TimeZone::Get();
?>
<!DOCTYPE html>
<html lang="<?php echo I18N::GetLang(); ?>">
    <head>
        <title>Iceberg - <?php I18N::E('Installation'); ?></title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <link rel="shortcut icon" href="<?php echo Theme::GetURL(); ?>assets/img/iceberg.ico">
        <link rel="stylesheet" href="<?php echo Theme::GetVendorURL(); ?>bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo Theme::GetURL(); ?>assets/css/style.css" />
        <!--[if lt IE 9]><script src="<?php echo Theme::GetVendorURL(); ?>html5shiv/html5shiv.min.js"></script><![endif]-->
    </head>
    <body>
        <header id="header" class="navbar navbar-fixed-top" role="navigation">
            <img src="<?php echo Theme::GetURL(); ?>assets/img/iceberg_logo.png" class="brand" alt="Iceberg">
            <img src="<?php echo Theme::GetURL(); ?>assets/img/iceberg_header.jpg" class="brand" alt="Iceberg">
        </header>
        <div id="wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-offset-2 col-sm-8">
                        <?php if (!empty($messages)): ?>
                        <?php foreach ($messages AS $message): ?>
                        <?php if ($message['type']==='success'): ?>
                        <p class="alert alert-success"><span class="glyphicon glyphicon-ok"></span> <?php echo $message['message']; ?></p>
                        <?php elseif ($message['type']==='error'): ?>
                        <p class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> <?php echo $message['message']; ?></p>
                        <?php else: ?>
                        <p class="alert alert-info"><span class="glyphicon glyphicon-info-sign"></span> <?php echo $message['message']; ?></p>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <br>
                        <?php endif; ?>
                        
                        <?php if (Install::IsFinished()): /**** FINISHED ****/ ?>
                        
                        <p class="text-center">
                            <a href="#" class="btn btn-default"><span class="glyphicon glyphicon-user"></span> <?php I18N::E('LOGIN'); ?></a>
                        </p>
                        <form action="" method="post" id="form-reinstall" role="form">
                            <?php Security::NONCE_Input(Request::KEY_ACTION_REINSTALL); ?>
                            <input type="hidden" name="<?php echo Request::KEY_ACTION; ?>" value="<?php echo Request::KEY_ACTION_REINSTALL; ?>">
                            <input type="hidden" name="<?php echo Request::KEY_LANGUAGE; ?>" value="<?php echo Template::EscAttr($active_locale); ?>">
                            <p class="form-group text-center">
                                <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-fire"></span> <?php I18N::E('REINSTALL'); ?></button>
                            </p>
                        </form>
                        
                        <?php /**** / FINISHED ****/ else: /**** NOT FINISHED ****/ ?>
                        
                        <p><?php I18N::E('The Iceberg installation is very simple. You just need to follow the steps.'); ?></p>
                        <p><?php I18N::E('First you have to choose the language of the installer.'); ?></p>
                        
                        <form action="" method="get" id="form-language" role="form">
                            <div class="form-group">
                                <label for="language-select" class="control-label"><?php I18N::E('Choose your language'); ?></label>
                                <select name="<?php echo Request::KEY_LANGUAGE; ?>" id="language-select" class="form-control">
                                    <?php foreach ($locales AS $name => $locale ): ?>
                                    <option value="<?php echo Template::EscAttr($locale); ?>" <?php echo $active_locale===$locale ? 'selected' : ''; ?>><?php echo $name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                        
                        <hr>
                        <p><?php I18N::E('To install Iceberg is necessary that the system meets a set of requirements. Until we meet can not be installed. If you have any questions please contact your administrator.'); ?></p>
                        
                        <br>
                        <h4><?php printf(I18N::T('PHP version required is %s and you are using the %s'), Bootstrap::$_PHP_VERSION_REQUIRED, phpversion()); ?></h4>
                        <?php if (strnatcmp(phpversion(), Bootstrap::$_PHP_VERSION_REQUIRED) >= 0): ?>
                        <p class="alert alert-success"><span class="glyphicon glyphicon-ok"></span> <?php I18N::E('PHP Version is compatible'); ?></p>
                        <?php else: ++$errors_count; ?>
                        <p class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> <?php I18N::E('PHP Version is incompatible'); ?></p>
                        <?php endif; ?>
                        
                        <br>
                        <h4><?php printf(I18N::T('The Uploads Directory path is: %s'), Theme::GetUploadsDIR()); ?></h4>
                        <?php if (is_dir(Theme::GetUploadsDIR()) && is_writable(Theme::GetUploadsDIR())): ?>
                        <p class="alert alert-success"><span class="glyphicon glyphicon-ok"></span> <?php I18N::E('The Uploads Directory is writable'); ?></p>
                        <?php else: ++$errors_count; ?>
                        <p class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> <?php I18N::E('The Uploads Directory must not writable'); ?></p>
                        <?php endif; ?>

                        <br>
                        <h4><?php printf(I18N::T('The Temporary Directory path is: %s'), Theme::GetTmpDIR()); ?></h4>
                        <?php if (is_dir(Theme::GetTmpDIR()) && is_writable(Theme::GetTmpDIR())): ?>
                        <p class="alert alert-success"><span class="glyphicon glyphicon-ok"></span> <?php I18N::E('The Temporary Directory is writable'); ?></p>
                        <?php else: ++$errors_count; ?>
                        <p class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> <?php I18N::E('The Temporary Directory must not writable'); ?></p>
                        <?php endif; ?>
                        
                        <?php if ($errors_count === 0): ?>
                        <form action="" method="post" id="form-install" role="form">
                            <?php Security::NONCE_Input(Request::KEY_ACTION_INSTALL); ?>
                            <input type="hidden" name="action" value="<?php echo Request::KEY_ACTION_INSTALL; ?>">
                            <input type="hidden" name="<?php echo Request::KEY_LANGUAGE; ?>" value="<?php echo Template::EscAttr($active_locale); ?>">
                            
                            <hr>
                            <h4><?php I18N::E('Time Zone'); ?></h4>
                            <div class="form-group">
                                <label for="timezone" class="control-label"><?php I18N::E('Choose your timezone'); ?></label>
                                <select name="timezone" id="timezone" class="form-control">
                                    <?php foreach ($timezones AS $key => $value ): ?>
                                    <option value="<?php echo Template::EscAttr($key); ?>" <?php echo ($active_timezone===$key) ? 'selected' : '' ; ?>><?php echo $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <br>
                            <h4><?php I18N::E('Root access'); ?></h4>
                            <p class="form-group">
                                <label for="username" class="control-label"><?php I18N::E('User'); ?></label>
                                <input type="text" name="username" id="username" class="form-control" placeholder="<?php echo Template::EscAttr(I18N::T('User')); ?>">
                            </p>
                            <p class="form-group">
                                <label for="email" class="control-label"><?php I18N::E('E-mail'); ?></label>
                                <input type="text" name="email" id="email" class="form-control" placeholder="<?php echo Template::EscAttr(I18N::T('E-mail')); ?>">
                            </p>
                            <p class="form-group">
                                <label for="password" class="control-label"><?php I18N::E('Password'); ?></label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="<?php echo Template::EscAttr(I18N::T('Password')); ?>">
                            </p>
                            
                            <br>
                            <hr>
                            <p class="form-group text-center">
                                <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-fire"></span> <?php I18N::E('INSTALL'); ?></button>
                            </p>
                            
                        </form>
                        <?php endif; ?>
                        
                        <?php endif; /**** / NOT FINISHED ****/ ?>
                        
                    </div>
                </div>
            </div>
        </div>
        <footer id="footer">
            Iceberg v<?php echo Bootstrap::$_VERSION; ?>  &copy; <a href="http://www.articstudio.com" title="Developed by Artic Studio" target="_blank">Artic Studio</a>
        </footer>
        <script src="<?php echo Theme::GetVendorURL(); ?>jquery/jquery.min.js"></script>
        <script src="<?php echo Theme::GetVendorURL(); ?>bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo Theme::GetVendorURL(); ?>jquery-validate/jquery.validate.min.js"></script>
        <script src="<?php echo Theme::GetURL(); ?>assets/js/main.js"></script>
    </body>
</html>
