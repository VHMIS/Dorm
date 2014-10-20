<?php

if($sessionLoginError)
{
    header('Location: ' . URL_PATH . 'login');
    exit();
}

$htmlIdDayNha = $id = $_ssvUri['params']['id'];

$dbSelect->reset();
$dbSelect->from('area')->where('active = 1')->order('name ASC');
$rowAreas = $dbSelect->query()->fetchAll();

foreach($rowAreas as $area)
{
    $htmlArea['a_' . $area['id']]['id'] = $area['id'];
    $htmlArea['a_' . $area['id']]['name'] = $area['name'];
}

if(!isset($htmlArea['a_' . $id]))
{
    echo 'Page not found';
    exit();
}

$dbSelect->reset();
$dbSelect->from('room_type');
$rowRoomTypes = $dbSelect->query()->fetchAll();

foreach($rowRoomTypes as $roomType)
{
    $htmlRoomType['rt_' . $roomType['id']]['id'] = $roomType['id'];
    $htmlRoomType['rt_' . $roomType['id']]['name'] = $roomType['name'];
    $htmlRoomType['rt_' . $roomType['id']]['max_students'] = $roomType['max_students'];
    $htmlRoomType['rt_' . $roomType['id']]['fee_day'] = $roomType['fee_day'];
    $htmlRoomType['rt_' . $roomType['id']]['fee_month'] = $roomType['fee_month'];

}

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

foreach($htmlSIRooms as $student)
{
    $htmlStudentInRoom['s_' . $student['id']]['id'] = $student['id'];
    $htmlStudentInRoom['s_' . $student['id']]['student_id'] = $student['student_id'];
    $htmlStudentInRoom['s_' . $student['id']]['student_name'] = $student['name'];
    $htmlStudentInRoom['s_' . $student['id']]['class'] = $student['class'];
    $htmlStudentInRoom['s_' . $student['id']]['room_id'] = $student['room_id'];
    $htmlStudentInRoom['s_' . $student['id']]['room_name'] = $student['room_name'];
    $htmlStudentInRoom['s_' . $student['id']]['room_fee_month'] = $student['room_fee_month'];
    $htmlStudentInRoom['s_' . $student['id']]['room_fee_day'] = $student['room_fee_day'];
    $student['date_in'] = explode('-', $student['date_in']);
    $htmlStudentInRoom['s_' . $student['id']]['date_in']['y'] = $student['date_in'][0];
    $htmlStudentInRoom['s_' . $student['id']]['date_in']['m'] = $student['date_in'][1];
    $htmlStudentInRoom['s_' . $student['id']]['date_in']['d'] = $student['date_in'][2];
    $student['date_out'] = explode('-', $student['date_out']);
    $htmlStudentInRoom['s_' . $student['id']]['date_out']['y'] = $student['date_out'][0];
    $htmlStudentInRoom['s_' . $student['id']]['date_out']['m'] = $student['date_out'][1];
    $htmlStudentInRoom['s_' . $student['id']]['date_out']['d'] = $student['date_out'][2];
    $htmlStudentInRoom['s_' . $student['id']]['total_date'] = $student['total_date'];
    $htmlStudentInRoom['s_' . $student['id']]['total_month'] = $student['total_month'];
    $htmlStudentInRoom['s_' . $student['id']]['total_fee'] = $student['total_fee'];
    $htmlStudentInRoom['s_' . $student['id']]['payed_fee'] = $student['payed_fee'];
    $htmlStudentInRoom['s_' . $student['id']]['debt'] = $htmlStudentInRoom['s_' . $student['id']]['total_fee'] - $htmlStudentInRoom['s_' . $student['id']]['payed_fee'];

    $htmlStudentInRoom['r_' . $student['room_id']][] = $student['id'];

}

if($_ssvUri['output'][0] == 'excel')
{
    require_once EXCE_PATH . 'PHPExcel.php';
    $objPHPExcel = new PHPExcel();

    $objPHPExcel->setActiveSheetIndex(0);

    //Tiêu đề bảng
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'TT')
                                  ->setCellValue('B1', 'TT phòng')
                                  ->setCellValue('C1', 'Lớp')
                                  ->setCellValue('D1', 'Mã SV')
                                  ->setCellValue('E1', 'Họ tên')
                                  ->setCellValue('F1', 'Ngày sinh')
                                  ->setCellValue('G1', 'Phòng cũ')
                                  ->setCellValue('H1', 'Phòng mới')
                                  ->setCellValue('I1', 'Số tháng ĐK')
                                  ->setCellValue('J1', 'Ngày vào')
                                  ->setCellValue('K1', 'Ngày ra')
                                  ->setCellValue('L1', 'Số tiền phải nộp')
                                  ->setCellValue('M1', 'Đã nộp')
                                  ->setCellValue('N1', 'Còn lại')
                                  ->setCellValue('O1', 'Ghi chú');

    $nextrow = 2;
    $current = 1;
    foreach($htmlStudentInRoom as $student)
    {
        $thango = $student['total_month'] == 0 ? '' : $student['total_month'] . 't ';
        $thango .= $student['total_date'] == 0 ? '' : $student['total_date'] . 'n';
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $nextrow, $current)
                                      ->setCellValue('E' . $nextrow, $student['student_name'])
                                      ->setCellValue('D' . $nextrow, $student['student_code'])
                                      ->setCellValue('C' . $nextrow, $student['class'])
                                      ->setCellValue('J' . $nextrow, $student['date_in']['d'] . '/' . $student['date_in']['m'] . '/' . $student['date_in']['y'])
                                      ->setCellValue('K' . $nextrow, $student['date_out']['d'] . '/' . $student['date_out']['m'] . '/' . $student['date_out']['y'])
                                      ->setCellValue('I' . $nextrow, $thango)
                                      ->setCellValue('H' . $nextrow, $student['room_name'])
                                      ->setCellValue('L' . $nextrow, $student['total_fee'])
                                      ->setCellValue('M' . $nextrow, $student['payed_fee'])
                                      ->setCellValue('N' . $nextrow, $student['debt']);
        $nextrow++;
        $current++;
    }

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="sinhvienokytucxa.xls"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

require CORE_PATH . '/View/' . $_ssvUri['controller'] . $_ssvUri['action'] . '.php';