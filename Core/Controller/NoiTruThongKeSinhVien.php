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

function findSchoolYear($scode, $ccode, $sid)
{
    if(strpos($scode, 'CCCT01') !== false) return 3;
    if(strpos($scode, 'CCVT01') !== false) return 2;
    if(strpos($scode, 'CCVT02') !== false) return 3;
    if(strpos($scode, 'CCVT03') !== false) return 4;
    if(strpos($scode, 'CCVT04') !== false) return 5;

    //if($sid > 3712) return 5;

    if(strlen($scode) != 10 || $scode[0] != 'C' || $scode[1] != 'C') return 6;
    //if($ccode == 'CHV' || $ccode == 'DHN' || $ccode == 'DTT') return 5;



    return $scode[5];
}

function countStudentInMonth($month, $year)
{
    global $dbSelect;

    $days = days_in_month($month, $year);
    if($month < 10) $month = '0' . $month;

    $dbSelect->reset();

    $dbSelect->from('student_in_room', array('count(id) as total'))
             ->where("date_in <= '" . $year . '-' . $month . '-' . $days . "'")
             ->where("date_out_real >= '" . $year . '-' . $month . "-01'");

    $row = $dbSelect->query()->fetchAll();
    if(isset($row[0])) return $row[0]['total'];
    else 0;
}

//GHI CHU
// Load thong tin phong, day nha ra truoc
$dbSelect->reset();
$dbSelect->from('area')->where('active = 1')->order('name ASC');
$rowAreas = $dbSelect->query()->fetchAll();

$dbSelect->reset();
$dbSelect->from('room_type')->where('id > 7');
$rowRoomTypes = $dbSelect->query()->fetchAll();

$dbSelect->reset();
$dbSelect->from('room')->where('active = 1')->order('name ASC');
$rowRooms = $dbSelect->query()->fetchAll();

$dbSelect->reset();
$dbSelect->from(array('sr' => 'student_in_room'))
         ->join(array('s' => 'student'), 's.id = sr.student_id', array('name', 'class', 'code', 'gender'))
         ->where('active = 1')
         ->order('room_name ASC');
$rowSIRooms = $dbSelect->query()->fetchAll();

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

    if($roomType['max_students'] > 0)
    {
        $htmlStats['rooms']['type']['rt_' . $roomType['id']] = 0;
        $htmlStats['beds']['type']['rt_' . $roomType['id']] = 0;
        $htmlStats['students']['type']['rt_' . $roomType['id']] = 0;
        $htmlStats['rooms']['type_gender']['rt_' . $roomType['id']]['female'] = 0;
        $htmlStats['rooms']['type_gender']['rt_' . $roomType['id']]['male'] = 0;
        $htmlStats['beds']['type_gender']['rt_' . $roomType['id']]['male'] = 0;
        $htmlStats['beds']['type_gender']['rt_' . $roomType['id']]['female'] = 0;
        $htmlStats['students']['type_gender']['rt_' . $roomType['id']]['female'] = 0;
        $htmlStats['students']['type_gender']['rt_' . $roomType['id']]['male'] = 0;
    }
}

$htmlStats['rooms']['genre'] = array('male' => 0, 'female' => 0);
$htmlStats['beds']['genre'] = array('male' => 0, 'female' => 0);
$htmlStats['rooms']['total'] = 0;
$htmlStats['unstats_rooms']['total'] = 0;

foreach($rowRooms as $room)
{
    $htmlRoom['r_' . $room['id']]['id'] = $room['id'];
    $htmlRoom['r_' . $room['id']]['name'] = $room['name'];
    $htmlRoom['r_' . $room['id']]['area'] = $room['area'];
    $htmlRoom['r_' . $room['id']]['type'] = $room['type'];
    $htmlRoom['r_' . $room['id']]['current_student'] = $room['current_student'];
    $htmlRoom['r_' . $room['id']]['gender'] = $room['gender'];
    $htmlRoom['r_' . $room['id']]['schoolyear'] = array();

    $htmlRoomArea['a_' . $room['area']][] = $room['id'];

    if($htmlRoomType['rt_' . $room['type']]['max_students'] > 0)
    {
        if($room['gender'] == 1)
        {
            $htmlStats['rooms']['genre']['male']++;
            $htmlStats['beds']['genre']['male'] += $htmlRoomType['rt_' . $room['type']]['max_students'];
            $htmlStats['rooms']['type_gender']['rt_' . $room['type']]['male']++;
            $htmlStats['beds']['type_gender']['rt_' . $room['type']]['male'] += $htmlRoomType['rt_' . $room['type']]['max_students'];
        }
        else
        {
            $htmlStats['rooms']['genre']['female']++;
            $htmlStats['beds']['genre']['female'] += $htmlRoomType['rt_' . $room['type']]['max_students'];
            $htmlStats['rooms']['type_gender']['rt_' . $room['type']]['female']++;
            $htmlStats['beds']['type_gender']['rt_' . $room['type']]['female'] += $htmlRoomType['rt_' . $room['type']]['max_students'];
        }
        $htmlStats['rooms']['type']['rt_' . $room['type']]++;
        $htmlStats['beds']['type']['rt_' . $room['type']] += $htmlRoomType['rt_' . $room['type']]['max_students'];
    }
    else
    {
        $htmlStats['unstats_rooms']['total']++;
    }
    $htmlStats['rooms']['total']++;
}

$htmlStats['beds']['total'] = $htmlStats['beds']['genre']['female'] + $htmlStats['beds']['genre']['male'];

// Thong kê sinh viên
$htmlStats['students']['total'] = 0;
$htmlStats['students']['gender']['male'] = 0;
$htmlStats['students']['gender']['female'] = 0;

// Thong ke khoa
for($i = 1; $i <= 6; $i++)
{
    $htmlStats['school_year']['k_' . $i]['total'] = 0;
    $htmlStats['school_year']['k_' . $i]['male'] = 0;
    $htmlStats['school_year']['k_' . $i]['female'] = 0;
}

foreach($rowSIRooms as $SIR)
{
    // CHuan bi
    $roomtype = 'rt_' . $htmlRoom['r_' . $SIR['room_id']]['type'];
    $schoolyear = findSchoolYear($SIR['code'], $SIR['class'], $SIR['student_id']);

    // Tinh toan du lieu
    $htmlStats['students']['total']++;
    $htmlStats['students']['type'][$roomtype]++;
    $htmlStats['school_year']['k_' . $schoolyear]['total']++;
    if($SIR['gender'] == 1)
    {
        $htmlStats['students']['gender']['male']++;
        $htmlStats['students']['type_gender'][$roomtype]['male']++;
        $htmlStats['school_year']['k_' . $schoolyear]['male']++;
    }
    else
    {
        $htmlStats['students']['gender']['female']++;
        $htmlStats['students']['type_gender'][$roomtype]['female']++;
        $htmlStats['school_year']['k_' . $schoolyear]['female']++;
    }

    // Thong ke sinh o trong phong
    if(isset($htmlRoom['r_' . $SIR['room_id']]['schoolyear']['k_' . $schoolyear])) $htmlRoom['r_' . $SIR['room_id']]['schoolyear']['k_' . $schoolyear]++;
    else $htmlRoom['r_' . $SIR['room_id']]['schoolyear']['k_' . $schoolyear] = 1;

    // Du lieu sinh vien
    $htmlStudentInRoom['s_' . $SIR['id']]['id'] = $SIR['id'];
    $htmlStudentInRoom['s_' . $SIR['id']]['student_id'] = $SIR['student_id'];
    $htmlStudentInRoom['s_' . $SIR['id']]['student_code'] = $SIR['code'];
    $htmlStudentInRoom['s_' . $SIR['id']]['student_name'] = $SIR['name'];
    $htmlStudentInRoom['s_' . $SIR['id']]['class'] = $SIR['class'];
    $htmlStudentInRoom['s_' . $SIR['id']]['room_id'] = $SIR['room_id'];
    $htmlStudentInRoom['s_' . $SIR['id']]['room_name'] = $SIR['room_name'];
    $htmlStudentInRoom['s_' . $SIR['id']]['room_fee_month'] = $SIR['room_fee_month'];
    $htmlStudentInRoom['s_' . $SIR['id']]['room_fee_day'] = $SIR['room_fee_day'];
    $SIR['date_in'] = explode('-', $SIR['date_in']);
    $htmlStudentInRoom['s_' . $SIR['id']]['date_in']['y'] = $SIR['date_in'][0];
    $htmlStudentInRoom['s_' . $SIR['id']]['date_in']['m'] = $SIR['date_in'][1];
    $htmlStudentInRoom['s_' . $SIR['id']]['date_in']['d'] = $SIR['date_in'][2];
    $SIR['date_out'] = explode('-', $SIR['date_out']);
    $htmlStudentInRoom['s_' . $SIR['id']]['date_out']['y'] = $SIR['date_out'][0];
    $htmlStudentInRoom['s_' . $SIR['id']]['date_out']['m'] = $SIR['date_out'][1];
    $htmlStudentInRoom['s_' . $SIR['id']]['date_out']['d'] = $SIR['date_out'][2];
    $htmlStudentInRoom['s_' . $SIR['id']]['total_date'] = $SIR['total_date'];
    $htmlStudentInRoom['s_' . $SIR['id']]['total_month'] = $SIR['total_month'];
    $htmlStudentInRoom['s_' . $SIR['id']]['total_fee'] = $SIR['total_fee'];
    $htmlStudentInRoom['s_' . $SIR['id']]['payed_fee'] = $SIR['payed_fee'];
    $htmlStudentInRoom['s_' . $SIR['id']]['debt'] = $htmlStudentInRoom['s_' . $SIR['id']]['total_fee'] - $htmlStudentInRoom['s_' . $SIR['id']]['payed_fee'];
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

// Thống kê tăng giảm
// Lấy tháng hiện tại, if
$month = date("m");
$year = date("Y");
$monthNum = (int) $month;
$monthNum -= 1;
if($monthNum == 1 || $monthNum >= 8) {
    if($monthNum == 1) {
        $passMonth = array(array(8, $year-1),array(9, $year-1),array(10, $year-1),array(11, $year-1),array(12, $year-1),array(1, $year));
    }
    else
    {
        for($i = 8; $i <= $monthNum; $i++) $passMonth[] = array($i, $year);
        for($j = $i; $j <=12; $j++) $nextMonth[] = array($j, $year);
        $nextMonth[] = array(1, $year+1);
        $futureMonth = array(array(2, $year+1),array(3, $year+1),array(4, $year+1),array(5, $year+1),array(6, $year+1),array(7, $year+1));
    }
}
elseif($monthNum <= 6)
{
    $passMonth = array(array(8, $year-1),array(9, $year-1),array(10, $year-1),array(11, $year-1),array(12, $year-1),array(1, $year));
    for($i = 2; $i <= $monthNum; $i++) $passMonth[] = array($i, $year);
    for($j = $i; $j <7; $j++) $nextMonth[] = array($j, $year);
    $futureMonth = array(array(7, $year));
}
else
{
    $passMonth = array(array(8, $year-1),array(9, $year-1),array(10, $year-1),array(11, $year-1),array(12, $year-1),array(1, $year),array(2, $year),array(3, $year),array(4, $year),array(5, $year),array(6, $year),array(7, $year));
}

$last = 0;

if(isset($passMonth))
{
    foreach($passMonth as $month)
    {
        $htmlStudentInRoomPassMonth[$month[0] . '-' . $month[1]]['total'] = countStudentInMonth($month[0], $month[1]);
        $htmlStudentInRoomPassMonth[$month[0] . '-' . $month[1]]['diff'] = $htmlStudentInRoomPassMonth[$month[0] . '-' . $month[1]]['total'] - $last;
        $last = $htmlStudentInRoomPassMonth[$month[0] . '-' . $month[1]]['total'];
    }
}

if(isset($nextMonth))
{
    foreach($nextMonth as $month)
    {
        $htmlStudentInRoomNextMonth[$month[0] . '-' . $month[1]]['total'] = countStudentInMonth($month[0], $month[1]);
        $htmlStudentInRoomNextMonth[$month[0] . '-' . $month[1]]['diff'] = $htmlStudentInRoomNextMonth[$month[0] . '-' . $month[1]]['total'] - $last;
        $last = $htmlStudentInRoomNextMonth[$month[0] . '-' . $month[1]]['total'];
    }
}

if(isset($futureMonth))
{
    foreach($futureMonth as $month)
    {
        if($month[0] != 7)
        {
            $htmlStudentInRoomFutureMonth[$month[0] . '-' . $month[1]]['total'] = $htmlStats['beds']['total'];
            $htmlStudentInRoomFutureMonth[$month[0] . '-' . $month[1]]['diff'] = $htmlStudentInRoomFutureMonth[$month[0] . '-' . $month[1]]['total'] - $last;
            $last = $htmlStudentInRoomFutureMonth[$month[0] . '-' . $month[1]]['total'];
        }
        else
        {
            $htmlStudentInRoomFutureMonth[$month[0] . '-' . $month[1]]['total'] = 400;
            $htmlStudentInRoomFutureMonth[$month[0] . '-' . $month[1]]['diff'] = $htmlStudentInRoomFutureMonth[$month[0] . '-' . $month[1]]['total'] - $last;
            $last = $htmlStudentInRoomFutureMonth[$month[0] . '-' . $month[1]]['total'];
        }
    }
}

require CORE_PATH . '/View/' . $_ssvUri['controller'] . $_ssvUri['action'] . '.php';