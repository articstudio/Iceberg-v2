<?php
namespace Iceberg\Core;

class Exception extends \Exception
{
    const STRICT = 'E_STRICT';
    const NOTICE = 'E_NOTICE';
    const WARNING = 'E_WARNING';
    const ERROR = 'E_ERROR';
    
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
    
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n" . $this->getTraceAsString();
    }
}

