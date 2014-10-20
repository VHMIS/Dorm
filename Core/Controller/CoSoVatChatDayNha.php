<?php

if($sessionLoginError)
{
    header('Location: ' . URL_PATH . 'login');
    exit();
}

$dbSelect->reset();
$dbSelect->from('area')->where('active = 1');
$htmlArea = $dbSelect->query()->fetchAll();

require CORE_PATH . '/View/' . $_ssvUri['controller'] . $_ssvUri['action'] . '.php';