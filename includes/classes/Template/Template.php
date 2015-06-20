<?php
namespace Iceberg\Template;

use Iceberg\Core\Extend;

class Template
{
    private $_dir;
    private $_file;
    private $_callback;
    private $_content = '';
    
    public function __construct($dir, $file, $callback=null)
    {
        $this->_dir = $dir;
        $this->_file = $file;
        $this->_callback = $callback;
    }
    
    public function get_path()
    {
        return realpath($this->_dir . DIRECTORY_SEPARATOR . $this->_file);
    }
    
    public function generate_content()
    {
        $path = $this->get_path();
        if ($path && is_readable($path))
        {
            ob_start($this->_callback);
            include $path;
            //$content = ob_get_clean();
            $this->_content = Extend::ApplyFilters('template_generate_content', ob_get_clean(), $this->_file, $path);
        }
        return $this;
    }
    
    public function print_content()
    {
        echo $this->get_content();
        return $this;
    }
    
    public function get_content()
    {
        return sprintf('%s', $this->_content);
    }
    
    public static function EscAttr($str, $strip_tags=true)
    {
        $str = sprintf( '%s', $str);
        $str = $strip_tags ? strip_tags( $str ) : $str;
        $str = addcslashes($str, '"');
        $str = str_replace('<br />', '', nl2br($str));
        $str = str_replace(["\n","\r"], '', $str);
        return $str;
    }
}