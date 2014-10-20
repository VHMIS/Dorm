<?php

class MCFValidate
{
    public function check($type, $string)
    {
        switch ($type)
        {
            case 'digit'       : return $this->isDigit($string); break;
            case 'string'      : return $this->isString($string); break;
            case 'string-slug' : return $this->isStringSlug($string); break;
            default            : return false;
        }
    }

    public function isDigit($string)
    {
        return (is_numeric($string) && $string > 0);
    }

    public function isString($string)
    {
        return preg_match("/[ ]/", $string) ? false : true;
    }

    public function isStringSlug($string)
    {
        return false;
    }
}