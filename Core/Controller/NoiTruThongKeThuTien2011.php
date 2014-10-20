<?php

//TAM THOI
function money_to_string($money)
    {
        if($money < 1 || $money > 999999999999) return $money;

        // Sử dụng phương pháp đọc theo bộ ba
        $num1 = $num2 = $num3 = '';
        $money = (string) $money;
        $string = $string2 = "";
        $count = strlen($money);

        for ($i = $count - 1; $i > -1; $i = $i - 3)
        {
            // Đọc bộ ba
            $num1 = $money[$i];
            $num2 = $i - 1 > -1 ? $money[$i - 1] : '0';
            $num3 = $i - 2 > -1 ? $money[$i - 2] : '0';

            $string2 = "";

            if($num1 == '0' && $num2 == '0' && $num3 == '0') {
                $string2 = "";
            }
            else if ($num1 == '0' && $num2 == '0' && $num3 != '0')
            {
                $string2 = NaturalNumberToText($num3) . " Trăm";
            }
            else if ($num1 == '0' && $num2 != '0' && $num3 == '0')
            {
                $string2 = NaturalNumberToText($num2) . " Mươi";
            }
            else if ($num1 != '0' && $num2 == '0' && $num3 == '0')
            {
                $string2 = NaturalNumberToText($num1);
            }
            else if ($num1 != '0' && $num2 == '0' && $num3 != '0')
            {
                $string2 = NaturalNumberToText($num3) . " Trăm Lẻ" . NaturalNumberToText($num1);
            }
            else if ($num1 != '0' && $num2 != '0' && $num3 == '0')
            {
                $string2 = NaturalNumberToText($num2) . " Mươi" . NaturalNumberToText($num1);
            }
            else if ($num1 == '0' && $num2 != '0' && $num3 != '0')
            {
                $string2 = NaturalNumberToText($num3) . " Trăm" . NaturalNumberToText($num2) . " Mươi";
            }
            else
            {
                $string2 = NaturalNumberToText($num3) . " Trăm" . NaturalNumberToText($num2) . " Mươi" . NaturalNumberToText($num1);
            }

            // Đơn vị của bộ ba
            if ($string2 != "")
            {
                if ($count - 1 - $i == 3)
                {
                    $string2 .= " Nghìn";
                }
                else if ($count - 1 - $i == 6)
                {
                    $string2 .= " Triệu";
                }
                else if ($count - 1 - $i == 9)
                {
                    $string2 .= " Tỷ";
                }
            }

            $string = $string2 . $string;
        }

        // Sửa cách đọc 55
        $string = str_replace("Mươi Năm", "Mươi Lăm", $string);
        $string = str_replace("Mươi Năm", "Mươi Lăm", $string);
        // Sua cach doc so 1 hang chuc
        $string = str_replace("Một Mươi", "Mười", $string);
        // Sua cach doc so 1 hang don vi
        $string = str_replace("Mươi Một", "Mươi Mốt", $string);

        return $string . " Đồng";
    }

function NaturalNumberToText($num)
{
    if ($num == '0') { return " Không"; }
    else if ($num == '1') { return " Một"; }
    else if ($num == '2') { return " Hai"; }
    else if ($num == '3') { return " Ba"; }
    else if ($num == '4') { return " Bốn"; }
    else if ($num == '5') { return " Năm"; }
    else if ($num == '6') { return " Sáu"; }
    else if ($num == '7') { return " Bảy"; }
    else if ($num == '8') { return " Tám"; }
    else if ($num == '9') { return " Chín"; }
    else return "";
}
///////

if($sessionLoginError)
{
    header('Location: ' . URL_PATH . 'login');
    exit();
}

$dbSelect->reset();
$dbSelect->from('room')->order('name ASC');
$rowRooms = $dbSelect->query()->fetchAll();

$dbSelect->reset();
$dbSelect->from('users')->order('realname ASC');
$rowUsers = $dbSelect->query()->fetchAll();

$dbSelect->reset();
$dbSelect->from(array('p' => 'student_payedfee_history'))
         ->join(array('sr' => 'student_in_room'), 'p.student_in_room_id = sr.id', array('room_name'))
         ->join(array('s' => 'student'), 's.id = p.student_id', array('name', 'class', 'code'))
         ->where("invoice_number like '2011-2012-1%'")
         ->order('p.id ASC');
$htmlPayment = $dbSelect->query()->fetchAll();

foreach($rowUsers as $user)
{
    $htmlUser['u_' . $user['username']]['id'] = $user['id'];
    $htmlUser['u_' . $user['username']]['username'] = $user['username'];
    $htmlUser['u_' . $user['username']]['realname'] = $user['realname'];
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
    $htmlRoomArea['a_' . $room['area']][] = $room['id'];
}

if($_ssvUri['output'][0] == 'excel')
{
    require_once EXCE_PATH . 'PHPExcel/IOFactory.php';
    $objReader = PHPExcel_IOFactory::createReader('Excel5');
    $objPHPExcel = $objReader->load(DATA_PATH . '/danhsach.xls');

    $row = 7;
    $i = 1;
    $tong = 0;
    foreach($htmlPayment as $payment)
    {
        if($payment['active'] == 0) continue;

        $infoky = explode('-', $payment['invoice_number']);
        $payment['date_payment'] = explode(' ', $payment['date_payment']);
        $payment['date_payment'] = explode('-', $payment['date_payment'][0]);

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $row, $i);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $row, $payment['name']);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $row, $payment['code']);
        //$objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $payment['class']);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $row, $payment['room_name']);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $row, $payment['payed_fee']);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $row)->getNumberFormat()->setFormatCode('#,##0');

        $objPHPExcel->getActiveSheet()->setCellValue('F' . $row, $payment['date_payment'][2] . '/' . $payment['date_payment'][1] . '/' . $payment['date_payment'][0]);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $row, $infoky[3]);
        $objPHPExcel->getActiveSheet()->mergeCells('H' . $row . ':I' . $row);
        $objPHPExcel->getActiveSheet()->setCellValue('K' . $row, $htmlUser['u_' . $payment['username']]['realname']);

        $row++;
        $i++;
        $tong += $payment['payed_fee'];
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
    $objPHPExcel->getActiveSheet()->getStyle('A7:I' . ($row-1))->applyFromArray($estyleArray);


    $row++;
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $row, 'Tien');
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $row, $tong);
    $row++;
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $row, 'Tien');
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $row, money_to_string($tong));

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $filename = '/danhsach_' . time() . '.xls';
    $objWriter->save(DATA_PATH . '/' . $filename);

    //Download
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="danhsach.xls"');
    readfile(DATA_PATH . '/' . $filename);
    exit();
}


require CORE_PATH . '/View/' . $_ssvUri['controller'] . $_ssvUri['action'] . '.php';