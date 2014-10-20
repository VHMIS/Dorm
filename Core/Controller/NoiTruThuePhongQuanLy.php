<?php
//UPDATE `student_in_room` SET `date_out_real`= `date_out` WHERE  `date_out_real` = '0000-00-00'
if($sessionLoginError)
{
    echo '-99999';
    exit();
}

if($_ssvUri['params']['action'] == 'studentgoroom')
{
    $sid = $rg->GetVar($_POST['sid'], '');
    $rid = $rg->GetVar($_POST['rid'], '');
    $date_in = $rg->GetVar($_POST['date_in'], '');
    $date_out = $rg->GetVar($_POST['date_out'], '');

    if($sid == '' || $rid == '')
    {
        echo '-88888';
        exit();
    }

    //IN room?
    if($dbStudentInRoom->checkSIRoom($sid))
    {
        echo '-777777';
        exit();
    }

    //Get row
    if($student = $dbStudent->find($sid))
    {
        $student = $student[0];
    }
    else
    {
        echo '-666666';
        exit();
    }

    if($room = $dbRoom->find($rid))
    {
        $room = $room[0];
    }
    else
    {
        echo '-666665';
        exit();
    }

    //Get row
    if($roomtype = $dbRoomType->find($room['type']))
    {
        $roomtype = $roomtype[0];
    }
    else
    {
        echo '-666664';
        exit();
    }

    if($room->current_student >= $roomtype->max_students)
    {
        echo '-666660';
        exit();
    }

    if($room->gender != $student->gender)
    {
        echo '-666650';
        exit();
    }

    //Check date
    $date_in = explode('/', $date_in);
    if(count($date_in) != 3 || !checkdate($date_in[1], $date_in[0], $date_in[2]))
    {
        echo '-555556';
        exit();
    }

    $date_out = explode('/', $date_out);
    if(count($date_out) != 3 || !checkdate($date_out[1], $date_out[0], $date_out[2]))
    {
        echo '-555555';
        exit();
    }

    $day1 = (int) $date_in[0];
    $day2 = (int) $date_out[0];
    $month1 = (int) $date_in[1];
    $month2 = (int) $date_out[1];
    $year1 = $date_in[2];
    $year2 = $date_out[2];

    if($year1 == $year2 && $month1 == $month2)
    {
        if(days_in_month($month1, $year1) == $day2 && $day1 = 1)
        {
            $totalDay = 0;
            $totalMonth = 1;
        }
        else
        {
            $totalDay = $day2 - $day1 + 1;
            $totalMonth = 0;
        }
    }
    else
    {
        $totalDay = $totalMonth = 0;

        if($day1 != 1)
        {
            $totalDay += days_in_month($month1, $year1) - $day1 + 1;
            $month1++;
        }

        if(($day2 < 30 && $month2 != 2) || ($day2 < 28 && $month2 == 2))
        {
            $totalDay += $day2;
            $month2--;
        }

        if($year1 == $year2)
        {
            $totalMonth += $month2 - $month1 + 1;
        }
        else
        {
            $totalMonth += 12 - $month1 + 1 + ($year2 - $year1 - 1) * 12 + $month2;
        }
    }

    if($row = $dbStudentInRoom->fetchNew())
    {
        $row->student_id = $sid;
        $row->room_id = $rid;
        $row->room_name = $room->name;
        $row->room_fee_month = $roomtype->fee_month;
        $row->room_fee_day = $roomtype->fee_day;
        $row->date_in = $date_in[2] . '-' . $date_in[1] . '-' . $date_in[0];
        $row->date_out = $date_out[2] . '-' . $date_out[1] . '-' . $date_out[0];
        $row->date_out_real = $row->date_out;
        $row->total_date = $totalDay;
        $row->total_month = $totalMonth;
        $row->total_fee = $totalDay * $roomtype->fee_day + $totalMonth * $roomtype->fee_month;
        $row->payed_fee = 0;
        $row->active = 1;
        $row->user_add = $sessionLogin->user;
        // Dành cho kỳ 2
        $row->school_year = '2012-2013';
        $row->school_year_period = 2;
        $row->save();
        echo $row->id;

        $room->current_student++;
        $room->save();
    }
}
elseif($_ssvUri['params']['action'] == 'changeroom')
{
    $sid = $rg->GetVar($_POST['sid'], '');
    $nrid = $rg->GetVar($_POST['nrid'], '');

    if($sid == '' || $nrid == '')
    {
        echo '-88888';
        exit();
    }

    if($inroom = $dbStudentInRoom->find($sid))
    {
        $inroom = $inroom[0];
    }
    else
    {
        echo '-666664';
        exit();
    }

    $orid = $inroom->room_id;

    if($oRoom = $dbRoom->find($orid))
    {
        $oRoom = $oRoom[0];
    }
    else
    {
        echo '-555555';
        exit();
    }

    if($nRoom = $dbRoom->find($nrid))
    {
        $nRoom = $nRoom[0];
    }
    else
    {
        echo '-555554';
        exit();
    }

    // Kiểm tra 2 phòng cùng type, cùng giới tính
    if($nRoom->type != $oRoom->type)
    {
        echo '-333339';
        exit();
    }

    if($nRoom->gender != $oRoom->gender)
    {
        echo '-333338';
        exit();
    }

    //Kiểm tra phòng cần chuyển còn active
    if($nrid->active == '0')
    {
        echo '-333337';
        exit();
    }

    if($type = $dbRoomType->find($nRoom->type))
    {
        $type = $type[0];
    }
    else
    {
        echo '-444444';
        exit();
    }

    // Kiểm tra phòng mới xem còn chổ trống
    if($nRoom->current_student >= $type->max_students)
    {
        echo '-333336';
    }

    // Cho chuyển
    $nRoom->current_student++;
    $nRoom->save();
    $oRoom->current_student--;
    $oRoom->save();
    $inroom->room_id = $nRoom->id;
    $inroom->room_name = $nRoom->name;
    $inroom->save();
    echo '1';
    exit();
}
elseif($_ssvUri['params']['action'] == 'payment')
{
    $id = $rg->GetVar($_POST['id'], '');
    $fee = $rg->GetVar($_POST['fee'], '');

    if($id == '' || $fee == '')
    {
        echo '-88888';
        exit();
    }

    if($inroom = $dbStudentInRoom->find($id))
    {
        $inroom = $inroom[0];
    }
    else
    {
        echo '-666664';
        exit();
    }

    if($fee < 0 || $fee > ($inroom->total_fee - $inroom->payed_fee))
    {
        echo '-555555';
    }

    if($row = $dbStudentPayment->fetchNew())
    {
        $row->student_id = $inroom->student_id;
        $row->student_in_room_id = $id;
        $row->payed_fee = $fee;
        $row->date_payment = date('Y-m-d H:i:s');
        $row->username = $sessionLogin->user;
        $row->active = 1;

        //tìm id lớn nhất
        $dbSelect->reset();

        $dbSelect->from('student_payedfee_history', array('invoice_number'))
                 ->where("invoice_number LIKE '" . $inroom->school_year . '-' . $inroom->school_year_period . "-%'")
                 ->order("id DESC")
                 ->limit(1,0);
        $paymentResult = $dbSelect->query()->fetchAll();
        $nextId = 1;
        if(isset($paymentResult[0])) {
            $paymentResult = $paymentResult[0];
            $paymentResult = explode('-', $paymentResult['invoice_number']);
            $nextId = $paymentResult[count($paymentResult) - 1] + 1;
        }
        $row->invoice_number = $inroom->school_year . '-' . $inroom->school_year_period . '-' . $nextId;

        while(!$row->save())
        {
            $nextId++;
            $row->invoice_number = $inroom->school_year . '-' . $inroom->school_year_period . '-' . $nextId;
        }
        echo $row->id;

        $inroom->payed_fee += $row->payed_fee;
        $inroom->save();
        exit();
    }
    else
    {
        echo '-111111';
        exit();
    }
}
elseif($_ssvUri['params']['action'] == 'cancelpayment')
{
    $id = $rg->GetVar($_POST['id'], '');

    if($id == '')
    {
        echo '-88888';
        exit();
    }

    if($payment = $dbStudentPayment->find($id))
    {
        $payment = $payment[0];
    }
    else
    {
        echo '-666664';
        exit();
    }

    if($inroom = $dbStudentInRoom->find($payment->student_in_room_id))
    {
        $inroom = $inroom[0];
    }
    else
    {
        echo '-666664';
        exit();
    }

    if($payment->username != $sessionLogin->user)
    {
        echo '-567676';
        exit();
    }

    $inroom->payed_fee -= $payment->payed_fee;
    $inroom->save();
    // inactive
    $payment->active = 0;
    $payment->save();
    echo '0';
}

elseif($_ssvUri['params']['action'] == 'cancelroom')
{
    $id = $rg->GetVar($_POST['id'], '');

    if($id == '')
    {
        echo '-88888';
        exit();
    }

    if($inroom = $dbStudentInRoom->find($id))
    {
        $inroom = $inroom[0];
    }
    else
    {
        echo '-777777';
        exit();
    }

    if($payment = $dbStudentPayment->checkStudentPayment($id))
    {
        if(count($payment) > 0)
        {
            foreach($payment as $pay)
            {
                if($pay->active == 1)
                {
                    echo '-666666';
                    exit();
                }
            }
        }
    }

    // Lay thong tin phong
    if($room = $dbRoom->find($inroom->room_id))
    {
        $room = $room[0];
    }
    else
    {
        echo '-555555';
        exit();
    }

    $room->current_student--;
    $room->save();
    $inroom->delete();
    // DELETE RELATIVE
    echo '0';
}
// Ra khoi phong
elseif($_ssvUri['params']['action'] == 'outroom')
{
    $id = $rg->GetVar($_POST['id'], '');

    if($id == '')
    {
        echo '-88888';
        exit();
    }

    if($inroom = $dbStudentInRoom->find($id))
    {
        $inroom = $inroom[0];
    }
    else
    {
        echo '-777777';
        exit();
    }

    // Còn nợ tiền thì ko ra =))
    if($inroom->payed_fee < $inroom->total_fee)
    {
        echo '-666666';
        exit();
    }

    // Lay thong tin phong
    if($room = $dbRoom->find($inroom->room_id))
    {
        $room = $room[0];
    }
    else
    {
        echo '-555555';
        exit();
    }

    $room->current_student--;
    $room->save();
    $inroom->date_out_real = date('Y-m-d');
    $inroom->user_out = $sessionLogin->user;
    $inroom->active = 0;
    $inroom->save();
    // DELETE RELATIVE
    echo '0';
}
elseif($_ssvUri['params']['action'] == 'printinvoice')
{
    $id = $_ssvUri['params']['id'];
    if($payment = $dbStudentPayment->find($id))
    {
        $payment = $payment[0];
    }
    else
    {
        echo '-666664';
        exit();
    }

    if($student = $dbStudent->find($payment->student_id))
    {
        $student = $student[0];
    }
    else
    {
        echo '-666664';
        exit();
    }

    if($inroom = $dbStudentInRoom->find($payment->student_in_room_id))
    {
        $inroom = $inroom[0];
    }
    else
    {
        echo '-777777';
        exit();
    }

    if(!$user = $dbUsers->checkUsername($payment->username))
    {
        echo '-777777';
        exit();
    }

    //Make excel
    require_once EXCE_PATH . 'PHPExcel/IOFactory.php';
    $objReader = PHPExcel_IOFactory::createReader('Excel5');
    $objPHPExcel = $objReader->load(DATA_PATH . '/hoadon.xls');

    $realId = str_replace($inroom->school_year . '-' . $inroom->school_year_period . '-', '', $payment->invoice_number);

    $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Năm học : ' . $inroom->school_year . ' / Kỳ : ' . $inroom->school_year_period . ' / Số phiếu thu : ' . $realId);
    $objPHPExcel->getActiveSheet()->setCellValue('F20', 'Năm học : ' . $inroom->school_year . ' / Kỳ : ' . $inroom->school_year_period . ' / Số phiếu thu : ' . $realId);
    $objPHPExcel->getActiveSheet()->setCellValue('F39', 'Năm học : ' . $inroom->school_year . ' / Kỳ : ' . $inroom->school_year_period . ' / Số phiếu thu : ' . $realId);
    $objPHPExcel->getActiveSheet()->setCellValue('B6', 'Sinh viên : ' . $student->name . ' - Lớp : ' . $student->class);
    $objPHPExcel->getActiveSheet()->setCellValue('B25', 'Sinh viên : ' . $student->name . ' - Lớp : ' . $student->class);
    $objPHPExcel->getActiveSheet()->setCellValue('B44', 'Sinh viên : ' . $student->name . ' - Lớp : ' . $student->class);
    $objPHPExcel->getActiveSheet()->setCellValue('B7', 'Mã SV : ' . $student->code . ' : Phòng ' . $inroom->room_name);
    $objPHPExcel->getActiveSheet()->setCellValue('B26', 'Mã SV : ' . $student->code . ' : Phòng ' . $inroom->room_name);
    $objPHPExcel->getActiveSheet()->setCellValue('B45', 'Mã SV : ' . $student->code . ' : Phòng ' . $inroom->room_name);
    $objPHPExcel->getActiveSheet()->setCellValue('B8', 'Số tiền nộp : ' . $payment->payed_fee);
    $objPHPExcel->getActiveSheet()->setCellValue('B27', 'Số tiền nộp : ' . $payment->payed_fee);
    $objPHPExcel->getActiveSheet()->setCellValue('B46', 'Số tiền nộp : ' . $payment->payed_fee);
    $objPHPExcel->getActiveSheet()->setCellValue('B9', 'Bằng chữ : ' . money_to_string($payment->payed_fee));
    $objPHPExcel->getActiveSheet()->setCellValue('B28', 'Bằng chữ : ' . money_to_string($payment->payed_fee));
    $objPHPExcel->getActiveSheet()->setCellValue('B47', 'Bằng chữ : ' . money_to_string($payment->payed_fee));

    $date = explode(' ', $payment->date_payment);
    $date = explode('-', $date[0]);

    $objPHPExcel->getActiveSheet()->setCellValue('F11', 'Đà Nẵng, ngày ' . $date[2] . ' tháng ' . $date[1] . ', năm ' . $date[0]);
    $objPHPExcel->getActiveSheet()->setCellValue('F30', 'Đà Nẵng, ngày ' . $date[2] . ' tháng ' . $date[1] . ', năm ' . $date[0]);
    $objPHPExcel->getActiveSheet()->setCellValue('F49', 'Đà Nẵng, ngày ' . $date[2] . ' tháng ' . $date[1] . ', năm ' . $date[0]);
    $objPHPExcel->getActiveSheet()->setCellValue('A17', $student->name);
    $objPHPExcel->getActiveSheet()->setCellValue('A36', $student->name);
    $objPHPExcel->getActiveSheet()->setCellValue('A55', $student->name);
    $objPHPExcel->getActiveSheet()->setCellValue('F17', $user->realname);
    $objPHPExcel->getActiveSheet()->setCellValue('F36', $user->realname);
    $objPHPExcel->getActiveSheet()->setCellValue('F55', $user->realname);

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save(DATA_PATH . '/bill' . $id . '.xls');

    //Download
    header('Content-type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="bill_no_' . $id . '.xls"');
    readfile(DATA_PATH . '/bill' . $id . '.xls');
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