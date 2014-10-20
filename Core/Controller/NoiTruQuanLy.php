<?php

if($sessionLoginError)
{
    header('Location: ' . URL_PATH . 'login');
    exit();
}

// Chung chung
$dbSelect->reset();
$dbSelect->from('area')->where('active = 1')->order('name ASC');
$rowAreas = $dbSelect->query()->fetchAll();
$id= 0;

foreach($rowAreas as $area)
{
    if($id == 0) $id = $area['id'];
    $htmlArea['a_' . $area['id']]['id'] = $area['id'];
    $htmlArea['a_' . $area['id']]['name'] = $area['name'];
}

$htmlIdDayNha = $id;

$dbSelect->reset();
$dbSelect->from('room')->where('active = 1')->order('name ASC');
$rowRooms = $dbSelect->query()->fetchAll();

$dbSelect->reset();
$dbSelect->from(array('sr' => 'student_in_room'))
         ->join(array('s' => 'student'), 's.id = sr.student_id', array('name', 'class'))
         ->join(array('r' => 'room'), 'r.id = sr.room_id', array(''))
         ->where('r.area = ' . $id)
         ->where('sr.active = 1')
         ->order('date_in asc');

$htmlSIRooms = $dbSelect->query()->fetchAll();

foreach($rowRooms as $room)
{
    $htmlRoom['r_' . $room['id']]['id'] = $room['id'];
    $htmlRoom['r_' . $room['id']]['name'] = $room['name'];
    $htmlRoom['r_' . $room['id']]['area'] = $room['area'];
    $htmlRoom['r_' . $room['id']]['type'] = $room['type'];
    $htmlRoom['r_' . $room['id']]['current_student'] = $room['current_student'];
    $htmlRoom['r_' . $room['id']]['gender'] = $room['gender'];

    if($room['area'] == $id) $htmlRoominArea[] = $room['id'];
}

// Lay danh sach sinh vien sap het han
$dateNextWeek = date('Y-m-d', time() + 24 * 60 * 60 * 7);
$dbSelect->reset();
$dbSelect->from(array('sr' => 'student_in_room'))
         ->join(array('s' => 'student'), 's.id = sr.student_id', array('name', 'class', 'code', 'gender'))
         ->where('active = 1')
         ->where('date_out <= \'' . $dateNextWeek . '\'')
         ->order('room_name ASC');
$rowSIRoomsOutSoon = $dbSelect->query()->fetchAll();
$htmlStudentInRoomOutSoon = array();
foreach($rowSIRoomsOutSoon as $student)
{
    $htmlStudentInRoomOutSoon['s_' . $student['id']]['id'] = $student['id'];
    $htmlStudentInRoomOutSoon['s_' . $student['id']]['student_id'] = $student['student_id'];
    $htmlStudentInRoomOutSoon['s_' . $student['id']]['student_name'] = $student['name'];
    $htmlStudentInRoomOutSoon['s_' . $student['id']]['class'] = $student['class'];
    $htmlStudentInRoomOutSoon['s_' . $student['id']]['room_id'] = $student['room_id'];
    $htmlStudentInRoomOutSoon['s_' . $student['id']]['room_name'] = $student['room_name'];
    $htmlStudentInRoomOutSoon['s_' . $student['id']]['room_fee_month'] = $student['room_fee_month'];
    $htmlStudentInRoomOutSoon['s_' . $student['id']]['room_fee_day'] = $student['room_fee_day'];
    $student['date_in'] = explode('-', $student['date_in']);
    $htmlStudentInRoomOutSoon['s_' . $student['id']]['date_in']['y'] = $student['date_in'][0];
    $htmlStudentInRoomOutSoon['s_' . $student['id']]['date_in']['m'] = $student['date_in'][1];
    $htmlStudentInRoomOutSoon['s_' . $student['id']]['date_in']['d'] = $student['date_in'][2];
    $student['date_out'] = explode('-', $student['date_out']);
    $htmlStudentInRoomOutSoon['s_' . $student['id']]['date_out']['y'] = $student['date_out'][0];
    $htmlStudentInRoomOutSoon['s_' . $student['id']]['date_out']['m'] = $student['date_out'][1];
    $htmlStudentInRoomOutSoon['s_' . $student['id']]['date_out']['d'] = $student['date_out'][2];
    $htmlStudentInRoomOutSoon['s_' . $student['id']]['total_date'] = $student['total_date'];
    $htmlStudentInRoomOutSoon['s_' . $student['id']]['total_month'] = $student['total_month'];
    $htmlStudentInRoomOutSoon['s_' . $student['id']]['total_fee'] = $student['total_fee'];
    $htmlStudentInRoomOutSoon['s_' . $student['id']]['payed_fee'] = $student['payed_fee'];
    $htmlStudentInRoomOutSoon['s_' . $student['id']]['debt'] = $htmlStudentInRoomOutSoon['s_' . $student['id']]['total_fee'] - $htmlStudentInRoomOutSoon['s_' . $student['id']]['payed_fee'];
}

// Lay danh sach sinh vien con no tien
// Lay danh sach sinh vien sap het han
$dateNextWeek = date('Y-m-d', time() + 24 * 60 * 60 * 7);
$dbSelect->reset();
$dbSelect->from(array('sr' => 'student_in_room'))
         ->join(array('s' => 'student'), 's.id = sr.student_id', array('name', 'class', 'code', 'gender'))
         //->where('active = 1')
         ->where('total_fee > payed_fee')
         ->order('room_name ASC');
$rowSIRoomsDebt = $dbSelect->query()->fetchAll();
$htmlStudentInRoomDebt = array();
foreach($rowSIRoomsDebt as $student)
{
    $htmlStudentInRoomDebt['s_' . $student['id']]['id'] = $student['id'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['student_id'] = $student['student_id'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['student_name'] = $student['name'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['class'] = $student['class'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['room_id'] = $student['room_id'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['room_name'] = $student['room_name'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['room_fee_month'] = $student['room_fee_month'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['room_fee_day'] = $student['room_fee_day'];
    $student['date_in'] = explode('-', $student['date_in']);
    $htmlStudentInRoomDebt['s_' . $student['id']]['date_in']['y'] = $student['date_in'][0];
    $htmlStudentInRoomDebt['s_' . $student['id']]['date_in']['m'] = $student['date_in'][1];
    $htmlStudentInRoomDebt['s_' . $student['id']]['date_in']['d'] = $student['date_in'][2];
    $student['date_out'] = explode('-', $student['date_out']);
    $htmlStudentInRoomDebt['s_' . $student['id']]['date_out']['y'] = $student['date_out'][0];
    $htmlStudentInRoomDebt['s_' . $student['id']]['date_out']['m'] = $student['date_out'][1];
    $htmlStudentInRoomDebt['s_' . $student['id']]['date_out']['d'] = $student['date_out'][2];
    $htmlStudentInRoomDebt['s_' . $student['id']]['total_date'] = $student['total_date'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['total_month'] = $student['total_month'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['total_fee'] = $student['total_fee'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['payed_fee'] = $student['payed_fee'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['debt'] = $htmlStudentInRoomDebt['s_' . $student['id']]['total_fee'] - $htmlStudentInRoomDebt['s_' . $student['id']]['payed_fee'];
}

require CORE_PATH . '/View/' . $_ssvUri['controller'] . $_ssvUri['action'] . '.php';