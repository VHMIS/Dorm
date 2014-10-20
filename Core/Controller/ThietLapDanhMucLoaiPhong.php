<?php

if($sessionLoginError)
{
    header('Location: ' . URL_PATH . 'login');
    exit();
}

$dbSelect->reset();
$dbSelect->from('room_type');
$htmlRoomType = $dbSelect->query()->fetchAll();

require CORE_PATH . '/View/' . $_ssvUri['controller'] . $_ssvUri['action'] . '.php';