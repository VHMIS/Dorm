<?php

if(!$sessionLoginError)
{
    header('Location: ' . URL_PATH);
    exit();
}

if(isset($_POST['login']) and $_POST['login'] == 'ok')
{
    if(!isset($_POST['pass']) || !isset($_POST['user']))
    {
        $htmlWarning = 'Vui lòng nhập đủ thông tin';
    }
    else
    {
        $pass = trim($_POST['pass']);
        $user = strtolower(trim($_POST['user']));

        if($pass == '' || $user == '')
        {
            $htmlWarning = 'Vui lòng nhập đủ thông tin';
        }
        else
        {
            $pass = sha1($pass);

            if($userInfo = $dbUsers->checkLogin($user, $pass))
            {
                $sessionLogin->isLogin = 'ok';
                $sessionLogin->user = $user;
                $sessionLogin->pass = $pass;
                $sessionLogin->name = $userInfo['realname'];
                header('Location: ' . URL_PATH);
                exit();
            }
            else
            {
                $htmlWarning = 'Thông tin đăng nhập không chính xác';
            }
        }
    }
}

require CORE_PATH . '/View/' . $_ssvUri['controller'] . $_ssvUri['action'] . '.php';