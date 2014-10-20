<?php

if($sessionLoginError)
{
    header('Location: ' . URL_PATH . 'login');
    exit();
}

function days_in_month($month, $year)
{
    if ($month == 2)
    {
        if ($year % 400 == 0 OR ($year % 4 == 0 AND $year % 100 != 0))
        {
            return 29;
        }
    }

    $days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    return $days_in_month[$month - 1];
}

// Lay du lieu thang
$month = explode('-', $_ssvUri['params']['month']);
if(count($month) !=2 ) exit();

// Lấy tháng hiện tại, if
$monthC = date("m");
$yearC = date("Y");
$monthC = (int) $monthC;


// Chung chung
$dbSelect->reset();
$dbSelect->from('area')->order('name ASC');
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
$dbSelect->from('room')->order('name ASC');
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

// Lay danh sach sinh vien o thang truoc
$dbSelect->reset();
$dbSelect->from(array('sr' => 'student_in_room'))
         ->join(array('s' => 'student'), 's.id = sr.student_id', array('name', 'class', 'code', 'gender'))
         ->where("date_out_real >= '" . $month[1] . '-' . $month[0] . "-01'")
         ->where("date_in < '" . $month[1] . '-' . $month[0] . "-01'")
         ->order('room_name ASC');
$rowSIRoomsLastMonth = $dbSelect->query()->fetchAll();
$htmlPrevTotal = 0;
foreach($rowSIRoomsLastMonth as $student)
{
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['id'] = $student['id'];
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['student_id'] = $student['student_id'];
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['student_code'] = $student['code'];
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['student_name'] = $student['name'];
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['class'] = $student['class'];
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['room_id'] = $student['room_id'];
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['room_name'] = $student['room_name'];
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['room_fee_month'] = $student['room_fee_month'];
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['room_fee_day'] = $student['room_fee_day'];
    $student['date_in'] = explode('-', $student['date_in']);
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['date_in']['y'] = $student['date_in'][0];
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['date_in']['m'] = $student['date_in'][1];
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['date_in']['d'] = $student['date_in'][2];
    $student['date_out'] = explode('-', $student['date_out_real']);
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['date_out']['y'] = $student['date_out'][0];
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['date_out']['m'] = $student['date_out'][1];
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['date_out']['d'] = $student['date_out'][2];
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['total_date'] = $student['total_date'];
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['total_month'] = $student['total_month'];
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['total_fee'] = $student['total_fee'];
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['payed_fee'] = $student['payed_fee'];
    $htmlStudentInRoomLastMonth['s_' . $student['id']]['debt'] = $htmlStudentInRoomLastMonth['s_' . $student['id']]['total_fee'] - $htmlStudentInRoomLastMonth['s_' . $student['id']]['payed_fee'];
    $htmlPrevTotal++;
}

// Lay danh sach sinh vien ra thang nay
$dbSelect->reset();
$dbSelect->from(array('sr' => 'student_in_room'))
         ->join(array('s' => 'student'), 's.id = sr.student_id', array('name', 'class', 'code', 'gender'))
         ->where("date_out_real <= '" . $month[1] . '-' . $month[0] . "-" . days_in_month($month[0], $month[1]) . "'")
         ->where("date_out_real >= '" . $month[1] . '-' . $month[0] . "-01'")
         ->order('room_name ASC');
$rowSIRoomsOutMonth = $dbSelect->query()->fetchAll();
$htmlOutTotal = 0;
$outStudentId = array();
$htmlOutandIn = 0;
foreach($rowSIRoomsOutMonth as $student)
{
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['id'] = $student['id'];
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['student_id'] = $student['student_id'];
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['student_name'] = $student['name'];
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['student_code'] = $student['code'];
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['class'] = $student['class'];
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['room_id'] = $student['room_id'];
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['room_name'] = $student['room_name'];
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['room_fee_month'] = $student['room_fee_month'];
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['room_fee_day'] = $student['room_fee_day'];
    $student['date_in'] = explode('-', $student['date_in']);
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['date_in']['y'] = $student['date_in'][0];
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['date_in']['m'] = $student['date_in'][1];
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['date_in']['d'] = $student['date_in'][2];
    if($student['date_out_real'] != $student['date_out']) $htmlStudentInRoomOutMonth['s_' . $student['id']]['out_soon'] = true;
    else $htmlStudentInRoomOutMonth['s_' . $student['id']]['out_soon'] = false;
    $student['date_out'] = explode('-', $student['date_out_real']);
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['date_out']['y'] = $student['date_out'][0];
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['date_out']['m'] = $student['date_out'][1];
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['date_out']['d'] = $student['date_out'][2];
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['total_date'] = $student['total_date'];
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['total_month'] = $student['total_month'];
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['total_fee'] = $student['total_fee'];
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['payed_fee'] = $student['payed_fee'];
    $htmlStudentInRoomOutMonth['s_' . $student['id']]['debt'] = $htmlStudentInRoomOutMonth['s_' . $student['id']]['total_fee'] - $htmlStudentInRoomOutMonth['s_' . $student['id']]['payed_fee'];
    $htmlOutTotal++;
}

if($_ssvUri['output'][0] == 'excel' && $_ssvUri['params']['type'] == 'out')
{
    if($htmlOutTotal == 0) {
        echo 'Ko co sinh vien nao';
        exit();
    }

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
    foreach($htmlStudentInRoomOutMonth as $student)
    {
        $thango = $student['total_month'] == 0 ? '' : $student['total_month'] . 't ';
        $thango .= $student['total_date'] == 0 ? '' : $student['total_date'] . 'n';
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $nextrow, $current)
                                      ->setCellValue('E' . $nextrow, $student['student_name'])
                                      ->setCellValue('D' . $nextrow, $student['student_code'])
                                      ->setCellValue('C' . $nextrow, $student['class'])
                                      ->setCellValue('J' . $nextrow, $student['date_in']['d'] . '-' . $student['date_in']['m'] . '-' . $student['date_in']['y'])
                                      ->setCellValue('K' . $nextrow, $student['date_out']['d'] . '-' . $student['date_out']['m'] . '-' . $student['date_out']['y'])
                                      ->setCellValue('I' . $nextrow, $thango)
                                      ->setCellValue('H' . $nextrow, $student['room_name'])
                                      ->setCellValue('L' . $nextrow, $student['total_fee'])
                                      ->setCellValue('M' . $nextrow, $student['payed_fee'])
                                      ->setCellValue('N' . $nextrow, $student['debt']);
        if($student['out_soon'] == true) $objPHPExcel->getActiveSheet()->setCellValue('O' . $nextrow, 'Ra trước/sau thời hạn');
        $nextrow++;
        $current++;
    }

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="sinhvien-ra-kytucxa-' . $_ssvUri['params']['month'] . '.xls"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

// Lay danh sach sinh vien vao thang nay
$dbSelect->reset();
$dbSelect->from(array('sr' => 'student_in_room'))
         ->join(array('s' => 'student'), 's.id = sr.student_id', array('name', 'class', 'code', 'gender'))
         ->where("date_in <= '" . $month[1] . '-' . $month[0] . "-" . days_in_month($month[0], $month[1]) . "'")
         ->where("date_in >= '" . $month[1] . '-' . $month[0] . "-01'")
         ->order('room_name ASC');
$rowSIRoomsInMonth = $dbSelect->query()->fetchAll();
$htmlInTotal = 0;
foreach($rowSIRoomsInMonth as $student)
{
    $htmlStudentInRoomInMonth['s_' . $student['id']]['id'] = $student['id'];
    $htmlStudentInRoomInMonth['s_' . $student['id']]['student_id'] = $student['student_id'];
    $htmlStudentInRoomInMonth['s_' . $student['id']]['student_code'] = $student['code'];
    $htmlStudentInRoomInMonth['s_' . $student['id']]['student_name'] = $student['name'];
    $htmlStudentInRoomInMonth['s_' . $student['id']]['class'] = $student['class'];
    $htmlStudentInRoomInMonth['s_' . $student['id']]['room_id'] = $student['room_id'];
    $htmlStudentInRoomInMonth['s_' . $student['id']]['room_name'] = $student['room_name'];
    $htmlStudentInRoomInMonth['s_' . $student['id']]['room_fee_month'] = $student['room_fee_month'];
    $htmlStudentInRoomInMonth['s_' . $student['id']]['room_fee_day'] = $student['room_fee_day'];
    $student['date_in'] = explode('-', $student['date_in']);
    $htmlStudentInRoomInMonth['s_' . $student['id']]['date_in']['y'] = $student['date_in'][0];
    $htmlStudentInRoomInMonth['s_' . $student['id']]['date_in']['m'] = $student['date_in'][1];
    $htmlStudentInRoomInMonth['s_' . $student['id']]['date_in']['d'] = $student['date_in'][2];
    $student['date_out'] = explode('-', $student['date_out_real']);
    $htmlStudentInRoomInMonth['s_' . $student['id']]['date_out']['y'] = $student['date_out'][0];
    $htmlStudentInRoomInMonth['s_' . $student['id']]['date_out']['m'] = $student['date_out'][1];
    $htmlStudentInRoomInMonth['s_' . $student['id']]['date_out']['d'] = $student['date_out'][2];
    $htmlStudentInRoomInMonth['s_' . $student['id']]['total_date'] = $student['total_date'];
    $htmlStudentInRoomInMonth['s_' . $student['id']]['total_month'] = $student['total_month'];
    $htmlStudentInRoomInMonth['s_' . $student['id']]['total_fee'] = $student['total_fee'];
    $htmlStudentInRoomInMonth['s_' . $student['id']]['payed_fee'] = $student['payed_fee'];
    $htmlStudentInRoomInMonth['s_' . $student['id']]['debt'] = $htmlStudentInRoomInMonth['s_' . $student['id']]['total_fee'] - $htmlStudentInRoomInMonth['s_' . $student['id']]['payed_fee'];
    $htmlInTotal++;
    //if(in_array($student['student_id'], $outStudentId)) $htmlOutandIn++;
}

if($_ssvUri['output'][0] == 'excel' && $_ssvUri['params']['type'] == 'in')
{
    if($htmlInTotal == 0) {
        echo 'Ko co sinh vien nao dd';
        exit();
    }

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
    foreach($htmlStudentInRoomInMonth as $student)
    {
        $thango = $student['total_month'] == 0 ? '' : $student['total_month'] . 't ';
        $thango .= $student['total_date'] == 0 ? '' : $student['total_date'] . 'n';
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $nextrow, $current)
                                      ->setCellValue('E' . $nextrow, $student['student_name'])
                                      ->setCellValue('D' . $nextrow, $student['student_code'])
                                      ->setCellValue('C' . $nextrow, $student['class'])
                                      ->setCellValue('J' . $nextrow, $student['date_in']['d'] . '-' . $student['date_in']['m'] . '-' . $student['date_in']['y'])
                                      ->setCellValue('K' . $nextrow, $student['date_out']['d'] . '-' . $student['date_out']['m'] . '-' . $student['date_out']['y'])
                                      ->setCellValue('I' . $nextrow, $thango)
                                      ->setCellValue('H' . $nextrow, $student['room_name'])
                                      ->setCellValue('L' . $nextrow, $student['total_fee'])
                                      ->setCellValue('M' . $nextrow, $student['payed_fee'])
                                      ->setCellValue('N' . $nextrow, $student['debt']);
        $nextrow++;
        $current++;
    }

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="sinhvien-vao-kytucxa-' . $_ssvUri['params']['month'] . '.xls"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

require CORE_PATH . '/View/' . $_ssvUri['controller'] . $_ssvUri['action'] . '.php';