<?php

//error_reporting(E_ALL | E_NOTICE);

define('ROOT_PATH', dirname(__FILE__));
define('ZEND_PATH', '/WebServer/Zend');
define('EXCE_PATH', '/WebServer/www/PHPExcel/');
define('CORE_PATH', ROOT_PATH . '/Core');
define('DATA_PATH', ROOT_PATH . '/Data');

// Set Zend Include Path
set_include_path('/WebServer/' . PATH_SEPARATOR . '/WebServer/www/PHPExcel/' . PATH_SEPARATOR. get_include_path());
define('URL'                , 'http://localhost');
define('URL_DOMAIN'         , '');
define('URL_PATH'           , '/kytucxa/');
define('URL_STATIC_CONTENT' , URL_PATH . 'static/');

require_once CORE_PATH . '/Class/URI.php';
require_once CORE_PATH . '/Class/Validate.php';
require_once CORE_PATH . '/Class/RegisterGlobal.php';
require_once ZEND_PATH . '/Db.php';
require_once ZEND_PATH . '/Db/Table.php';
require_once ZEND_PATH . '/Session.php';
require_once DATA_PATH . '/Config.php';

// DATABASE
try
{
    $db = Zend_Db::factory('Pdo_Mysql', $ssvConfigDb);
    $db->getConnection();
    $db->query("SET NAMES 'utf8'");
    $dbSelect = $db->select();
    Zend_Db_Table_Abstract::setDefaultAdapter($db);
    $dbError = false;
}
catch (Zend_Db_Adapter_Exception $e)
{
    $dbError = true;
}
catch (Zend_Exception $e)
{
    $dbError = true;
}

if($dbError)
{
    echo 'DB Error';
    exit();
}
else
{
    require_once CORE_PATH . '/Class/Database.php';

    $dbUsers = new dbUsers();
    $dbRoomType = new dbRoomType();
    $dbArea = new dbArea();
    $dbRoom = new dbRoom();
    $dbStudent = new dbStudent();
    $dbStudentInRoom = new dbStudentInRoom();
    $dbStudentPayment = new dbStudentPayment();
}

// Get session
Zend_Session::start();
$sessionLogin = new Zend_Session_Namespace('Login');

// Check login
if(!isset($sessionLogin->isLogin) || !isset($sessionLogin->pass) || !isset($sessionLogin->user))
{
    $sessionLoginError = true;
}
else if($sessionLogin->isLogin != 'ok')
{
    $sessionLoginError = true;
}
else
{
    if(!$dbUsers->checkLogin($sessionLogin->user, $sessionLogin->pass))
    {
        $sessionLoginError = true;
    }
    else
    {
        $sessionLoginError = false;
    }
}

// Register Global control
$rg = new RegisterGlobal();
$validate = new MCFValidate();

// Get route
$uri = explode('?', $_SERVER['REQUEST_URI']);
if(URL_PATH == '/')
{
    $uri = substr($uri[0], 1);
}
else
{
    $uri = str_replace(URL_PATH, '', $uri[0]);
}

$ssvUri = new MCFUri($uri, $configUriRules);

if ($ssvUri->isValid())
{
    $_ssvUri = $ssvUri->getResult();

    if($_ssvUri['redirect'] != '')
    {
        header('Location: ' . $_ssvUri['redirect']);
        exit();
    }

    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    require CORE_PATH . '/Controller/' . $_ssvUri['controller'] . $_ssvUri['action'] . '.php';
}
else
{
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    require CORE_PATH . '/Controller/404.php';
}
