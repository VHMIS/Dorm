<?php

if($sessionLoginError)
{
    header('Location: ' . URL_PATH . 'login');
    exit();
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

// Sinh viên còn nợ
$dbSelect->reset();
$dbSelect->from(array('sr' => 'student_in_room'))
         ->join(array('s' => 'student'), 's.id = sr.student_id', array('name', 'class', 'code', 'gender'))
         //->where('active = 1')
         ->where('total_fee > payed_fee')
         ->order(array('school_year ASC', 'school_year_period ASC'));
$rowSIRoomsDebt = $dbSelect->query()->fetchAll();
$htmlStudentInRoomDebt = array();
foreach($rowSIRoomsDebt as $student)
{
    $htmlStudentInRoomDebt['s_' . $student['id']]['id'] = $student['id'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['student_id'] = $student['student_id'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['student_name'] = $student['name'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['code'] = $student['code'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['room_id'] = $student['room_id'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['room_name'] = $student['room_name'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['room_fee_month'] = $student['room_fee_month'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['room_fee_day'] = $student['room_fee_day'];
    /*$student['date_in'] = explode('-', $student['date_in']);
    $htmlStudentInRoomDebt['s_' . $student['id']]['date_in']['y'] = $student['date_in'][0];
    $htmlStudentInRoomDebt['s_' . $student['id']]['date_in']['m'] = $student['date_in'][1];
    $htmlStudentInRoomDebt['s_' . $student['id']]['date_in']['d'] = $student['date_in'][2];
    $student['date_out'] = explode('-', $student['date_out']);
    $htmlStudentInRoomDebt['s_' . $student['id']]['date_out']['y'] = $student['date_out'][0];
    $htmlStudentInRoomDebt['s_' . $student['id']]['date_out']['m'] = $student['date_out'][1];
    $htmlStudentInRoomDebt['s_' . $student['id']]['date_out']['d'] = $student['date_out'][2];*/
    $htmlStudentInRoomDebt['s_' . $student['id']]['school_year'] = $student['school_year'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['school_year_period'] = $student['school_year_period'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['total_date'] = $student['total_date'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['total_month'] = $student['total_month'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['total_fee'] = $student['total_fee'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['payed_fee'] = $student['payed_fee'];
    $htmlStudentInRoomDebt['s_' . $student['id']]['debt'] = $htmlStudentInRoomDebt['s_' . $student['id']]['total_fee'] - $htmlStudentInRoomDebt['s_' . $student['id']]['payed_fee'];
}

require_once EXCE_PATH . 'PHPExcel/IOFactory.php';
$objReader = PHPExcel_IOFactory::createReader('Excel5');
$objPHPExcel = $objReader->load(DATA_PATH . '/danhsachno.xls');

$row = 7;
$i = 1;
$tong = 0;
foreach($htmlStudentInRoomDebt as $student)
{
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $i);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $student['student_name']);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $student['code']);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, findSchoolYear($student['code'], '', $student['student_id']));
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $student['total_fee']);
    $objPHPExcel->getActiveSheet()->getStyle('E' . $row)->getNumberFormat()->setFormatCode('#,##0');
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $student['payed_fee']);
    $objPHPExcel->getActiveSheet()->getStyle('F' . $row)->getNumberFormat()->setFormatCode('#,##0');
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $student['debt']);
    $objPHPExcel->getActiveSheet()->getStyle('G' . $row)->getNumberFormat()->setFormatCode('#,##0');
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $row, $student['room_name'] . ' - HK ' . $student['school_year_period'] . ' ' . $student['school_year']);

    $row++;
    $i++;
}

// style
$estyleArray = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
			'color' => array('argb' => '00000000'),
		),
		'vertical' => array(
			'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
			'color' => array('argb' => '00000000'),
		),
		'horizontal' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => '00000000'),
		),
	),
);
$objPHPExcel->getActiveSheet()->getStyle('A7:H' . ($row-1))->applyFromArray($estyleArray);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="sinhviennotienktx.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;