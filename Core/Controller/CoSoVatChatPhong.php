<?php

if($sessionLoginError)
{
    header('Location: ' . URL_PATH . 'login');
    exit();
}

$dbSelect->reset();
$dbSelect->from('area')->where('active = 1')->order('name ASC');
$rowAreas = $dbSelect->query()->fetchAll();

$dbSelect->reset();
$dbSelect->from('room_type');
$rowRoomTypes = $dbSelect->query()->fetchAll();

$dbSelect->reset();
$dbSelect->from('room')->where('active = 1')->order('name ASC');
$rowRooms = $dbSelect->query()->fetchAll();

foreach($rowAreas as $area)
{
    $htmlArea['a_' . $area['id']]['id'] = $area['id'];
    $htmlArea['a_' . $area['id']]['name'] = $area['name'];
}

foreach($rowRoomTypes as $roomType)
{
    $htmlRoomType['rt_' . $roomType['id']]['id'] = $roomType['id'];
    $htmlRoomType['rt_' . $roomType['id']]['name'] = $roomType['name'];
    $htmlRoomType['rt_' . $roomType['id']]['max_students'] = $roomType['max_students'];
    $htmlRoomType['rt_' . $roomType['id']]['fee_day'] = $roomType['fee_day'];
    $htmlRoomType['rt_' . $roomType['id']]['fee_month'] = $roomType['fee_month'];

}

foreach($rowRooms as $room)
{
    $htmlRoom['r_' . $room['id']]['id'] = $room['id'];
    $htmlRoom['r_' . $room['id']]['name'] = $room['name'];
    $htmlRoom['r_' . $room['id']]['area'] = $room['area'];
    $htmlRoom['r_' . $room['id']]['type'] = $room['type'];
    $htmlRoom['r_' . $room['id']]['current_student'] = $room['current_student'];
    $htmlRoom['r_' . $room['id']]['gender'] = $room['gender'];

    if($room['current_student'] == 0)
    {
        $htmlRoomEmpty['a_' . $room['area']][] = $room['id'];
    }
    else
    {
        $htmlRoomUse['a_' . $room['area']][] = $room['id'];
    }
}

require CORE_PATH . '/View/' . $_ssvUri['controller'] . $_ssvUri['action'] . '.php';