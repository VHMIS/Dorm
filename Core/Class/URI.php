<?php

class MCFUriRule
{
    protected $_rule;
    protected $_controller;
    protected $_action;
    protected $_redirect;
    protected $_output;
    protected $_params;

    public function __construct($rule, $controller, $action, $output, $params, $redirect = false)
    {
        $this->_rule         = $rule;
        $this->_controller   = $controller;
        $this->_action       = $action;
        $this->_output       = $output;
        $this->_params       = $params;
        $this->_redirect     = $redirect;
    }

    /**
     * Ham kiem tra xem mot dia chi uri co hop le hay khong
     *
     * @param $uri Dia chi can kiem tra
     * @param $validate Doi tuong thuoc lop ssvValidate, dung de kiem tra du lieu
     */
    public function validate($uri, $validate)
    {
        $segment    = explode("/", $this->_rule);
        $total      = count($segment);
        $uriSegment = explode("/", $uri, $total);
        $redirect   = !$this->_redirect ? '' : $this->_redirect;

        // Ket qua mac dinh, sai
        $result['valid']      = false;
        $result['controller'] = '';
        $result['action']     = '';
        $result['params']     = '';
        $result['output']     = '';
        $result['redirect']   = '';

        // Truong hop khong du so luong segment
        if ($total > count($uriSegment))
        {
            return $result;
        }

        // Kiem tra segment cuoi cung, chi hop le neu khong co ky tu '/' hoac co 1 ky tu '/' nam cuoi
        $lastSegment = explode('/', $uriSegment[$total - 1], 2);

        if (isset($lastSegment[1]) and $lastSegment[1] != '')
        {
            return $result;
        }
        else
        {
            // Xoa bo ky tu '/' nam cuoi
            $uriSegment[$total - 1] = $lastSegment[0];
        }

        // Kiem tra tung segment de xem dia chi co hop le khong
        for ($i = 0; $i < $total; $i++)
        {
            if ($segment[$i] != $uriSegment[$i])
            {
                // Kiem tra xem co phai la param dang name:type
                $paramInfo = explode(':', $segment[$i], 2);

                // Neu dung, them param nay vao
                if (isset($paramInfo[1]) && $validate->check($paramInfo[1], $uriSegment[$i]))
                {
                    $params[$paramInfo[0]] = $uriSegment[$i];

                    // Neu co dia chi redirect, thu thay the param nay
                    if ($redirect != '')
                    {
                        $redirect = str_replace($paramInfo[0] . ':' . $paramInfo[1], $uriSegment[$i], $redirect);
                    }
                }
                // Sai, tra ve ket qua sai mac dinh
                else
                {
                    return $result;
                }
            }
        }

        // Dia chi hop le, tra ve ket qua
        $result['valid']      = true;
        $result['controller'] = $this->_controller;
        $result['action']     = $this->_action;
        $result['output']     = $this->_output;
        $result['redirect']   = $redirect;
        if (isset($params) && is_array($params))
        {
            if (is_array($this->_params))
            {
                $result['params'] = array_merge($this->_params, $params);
            }
            else
            {
                $result['params'] = $params;
            }
        }
        else
        {
            $result['params'] = $this->_params;
        }

        return $result;
    }
}

class MCFUri
{
    protected $_uri;
    protected $_uriRules;
    protected $_result;

    public function __construct($uri, $uriRules)
    {
        $this->_uri      = $uri;
        $this->_uriRules = $uriRules;

        $this->_result = $this->_analy();
    }

    public function isValid()
    {
        return !$this->_result ? false : true;
    }

    public function getResult()
    {
        return $this->_result;
    }

    private function _analy()
    {
        // Khoi tao doi tuong thuoc lop Validate
        $validate = new MCFValidate();

        // Kiem tra uri voi tap hop cac uriRules nhap vao
        // neu trung hop voi mot rule bat ky thi dung lai
        foreach ($this->_uriRules as $uriRule)
        {
            $result = $uriRule->validate($this->_uri, $validate);

            if ($result['valid'])
            {
                return $result;
            }
        }

        // Khong phat hien trung hop
        return false;
    }
}