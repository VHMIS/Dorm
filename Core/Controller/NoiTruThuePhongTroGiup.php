<?php

if($sessionLoginError)
{
    echo '-99999';
    exit();
}

if($_ssvUri['params']['action'] == 'findstudent')
{
    $code = $rg->GetVar($_POST['code'], '');

    if($code == '')
    {
        echo '-88888';
        exit();
    }

    //Get row
    if($row = $dbStudent->getByCode($code))
    {
        //Find last room
        if($row1 = $dbStudentInRoom->findLastRoom($row->id))
        {
            $last = $row1->room_id;
        }
        else
        {
            $last = 0;
        }

        echo $row->name . "|||" . $row->id . "|||" . $row->gender . "|||" . $last;
        exit();
    }
    else
    {
        echo '-666666';
        exit();
    }
}
elseif($_ssvUri['params']['action'] == 'changesex')
{
    $id = $rg->GetVar($_POST['id'], '');

    if($id == '')
    {
        echo '-88888';
        exit();
    }

    //Get row
    if($student = $dbStudent->find($id))
    {
        $student = $student[0];
    }
    else
    {
        echo '-666666';
        exit();
    }

    $student->gender = $student->gender == 2 ? 1 : 2;
    $student->save();
    echo '0';
    exit();
}
elseif($_ssvUri['params']['action'] == 'thongtinphi')
{
    $id = $rg->GetVar($_POST['id'], '');
    $row = $dbStudentInRoom->checkStudentInRoom($id);

    if($row)
    {
        $rowpayment = $dbStudentPayment->checkStudentPayment($id);
    }
    else
    {
        echo 'no info';
        exit();
    }

    echo '<script>
        payment_fee.total_fee = ' . $row['total_fee'] . ';
        payment_fee.payed_fee = ' . $row['payed_fee'] . ';
        payment_fee.debt = ' . ($row['total_fee'] - $row['payed_fee']) . ';
        payment_fee.id = ' . $id . ';
    </script>
    <div class="info_money">
        <h2 id="info_money">' . $row['total_fee'] . ' - ' . $row['payed_fee'] . ' = ' . ($row['total_fee'] - $row['payed_fee']) . '</h2>
    </div>
    <form action="" onsubmit="studentPayFee();return false;">
        <div class="txt-fld">
            <label for="">Số tiền thu</label>
            <input id="money_will_pay" name="" value="' . ($row['total_fee'] - $row['payed_fee']) . '" type="text" />
        </div>
        <div class="btn-fld">
            <button type="submit">Thu tiền &raquo;</button>
        </div>
    </form>
    <div class="history">
        <h2>Các lần nộp tiền</h2>
    </div>
    <ul id="bill_list" class="bill_list">';
    foreach($rowpayment as $payment)
    {
        echo '<li id="bill_list_' . $payment['id'] . '">
            <div class="billno">#' . $payment['id'] . '</div>
            <div class="date">' . $payment['date_payment'] . '</div>
            <div class="money">' . $payment['payed_fee'] . '</div>';
        if($payment['active'] == 1)
            echo '
            <div class="print"><a href="' . URL_PATH . 'noitru/thuephong/inhoadon/' . $payment['id'] . '">bill</a></div>
            <div class="del"><a href="#" onclick="studentRemoveFee(' . $payment['id'] . ');return false;">huy</a></div>';
        else
            echo '
                <div class="print">---</div>
                <div class="del">---</div>';
        echo '
        </li>';
    }
    echo '
    </ul>';
}