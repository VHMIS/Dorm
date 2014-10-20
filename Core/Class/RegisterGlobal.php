<?php

//phpBB

class RegisterGlobal
{
    protected $_strip = true;

    public function RegisterGlobal() {
        if(version_compare(PHP_VERSION, '6.0.0-dev', '>='))
        {
            $this->_strip = false;
        }
        else
        {
            //set_magic_quotes_runtime(0);
            if(@ini_get('register_globals') == '1' || strtolower(@ini_get('register_globals')) == 'on')
            {
                $this->_deregisterGlobals();
            }
            $this->_strip = (get_magic_quotes_gpc()) ? true : false;
        }
    }
    
    protected function _deregisterGlobals()
    {
        $not_unset = array(
            'GLOBALS'	=> true,
            '_GET'		=> true,
            '_POST'		=> true,
            '_COOKIE'	=> true,
            '_REQUEST'	=> true,
            '_SERVER'	=> true,
            '_SESSION'	=> true,
            '_ENV'		=> true,
            '_FILES'	=> true,
        );

        if(!isset($_SESSION) || !is_array($_SESSION))
        {
            $_SESSION = array();
        }

        $input = array_merge(
            array_keys($_GET),
            array_keys($_POST),
            array_keys($_COOKIE),
            array_keys($_SERVER),
            array_keys($_SESSION),
            array_keys($_ENV),
            array_keys($_FILES)
        );

        foreach ($input as $varname)
        {
            if(isset($not_unset[$varname]))
            {
                echo 'Something is wrong. Closed';
                exit();
            }
            unset($GLOBALS[$varname]);
        }
        unset($input);
    }
    
    public function GetVar($var, $default, $multibyte = false)
    {
        if(!$var) $var = $default;
        
        $_type = gettype($var);
        $type = gettype($default);
        if($_type!=$type) $var = $default;
        
        $this->_setVar($var, $var, $type, $multibyte);

        return $var;
    }
    
    public function GetRaw($var)
    {
        $var = $this->_strip ? stripslashes($var) : $var;
        return $var;
    }
    
    protected function _setVar(&$result, $var, $type, $multibyte = false)
    {
        settype($var, $type);
        $result = $var;

        if($type == 'string')
        {
            $result = trim(htmlspecialchars(str_replace(array("\r\n", "\r"), array("\n", "\n"), $result), ENT_QUOTES, 'UTF-8'));
            if(!empty($result))
            {
                if($multibyte)
                {
                    if(!preg_match('/^./u', $result)) $result = '';
                }
                else
                {
                    $result = preg_replace('/[\x80-\xFF]/', '?', $result);
                }
            }
            $result = $this->_strip ? stripslashes($result) : $result;
        }
    }
}