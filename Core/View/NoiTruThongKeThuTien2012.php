<?php

$htmlPage['sitemap'][] = array('Ký Túc Xá', URL_PATH);
$htmlPage['sitemap'][] = array('Nội Trú', URL_PATH . 'noitru');
$htmlPage['sitemap'][] = array('Thống Kê Thu Tiền', '');
$htmlPage['menu'] = 'noitru';
$htmlPage['mainTitle'] = 'Thống Kê Thu Tiền';
$htmlPage['pageTitle'] = 'Thống Kê Thu Tiền - Ký Túc Xá';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/jquery.1.6.2.js';
//$htmlPage['jsfile'][] = 'http://code.jquery.com/jquery-1.6.2.js';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/jquery-ui-1.8.15.js';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/modal_lean.js';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/stickyscroll.js';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/global.js';

require_once 'Include/Header.php';

echo '    <div id="main">
        <div class="main">
            <div class="left">
                <h2 class="list_header" style="margin-top:20px">Danh sách phiếu thu kỳ 1 - 2012-2013</h2>
                <ul class="list_invoice">';
if(isset($htmlPayment))
{
    $i = 0;
    $total = 0;
    foreach($htmlPayment as $payment)
    {
        $i++;
        $payment['date_payment'] = explode(' ', $payment['date_payment']);
        $payment['date_payment'] = $payment['date_payment'][0];
        $infoky = explode('-', $payment['invoice_number']);
        echo '<li>
            <div class="num">' . $i . '</div>
            <div class="name">' . $payment['name'] . '</div>
            <div class="code" title="' . $payment['class'] . '">' . $payment['code'] . '</div>
            <div class="room">' . $payment['room_name'] . '</div>';
        if($payment['active'] == 1)
        {
            echo '<div class="payed_fee">' . $payment['payed_fee'] . '</div>
            <div class="date">' . $payment['date_payment'] . '</div>
            <div class="class" title="' . $infoky[0] . ' - ' . $infoky[1] . '">Kỳ ' . $infoky[2]  . '</div>
            <div class="invoice"><a href="' . URL_PATH . 'noitru/thuephong/inhoadon/' . $payment['id'] . '">' . $infoky[3] . '</a></div>
            <div class="user">' . $htmlUser['u_' . $payment['username']]['realname'] . '</div>';

            $total += $payment['payed_fee'];
        }
        else
        {
            echo '<div class="payed_fee">--------</div>
            <div class="date">--------</div>
            <div class="class">Kỳ ' . $infoky[2]  . '</div>
            <div class="invoice">' . $infoky[3] . '</div>
            <div class="user">Hủy</div>';
        }
        echo '
        </li>';
    }
    echo '<li>
            <div class="num">&nbsp;</div>
            <div class="name">&nbsp;</div>
            <div class="code">&nbsp;</div>
            <div class="room">&nbsp;</div>
            <div class="payed_fee">' . $total . '</div>
            <div class="date">&nbsp;</div>
            <div class="class">&nbsp;</div>
            <div class="invoice">&nbsp;</div>
            <div class="user">&nbsp;</div>
        </li>';
}
echo
                '</ul>
                <h2 class="list_header">Xuất dữ liệu</h2>
                <div class="button"><a href="' . URL_PATH . 'noitru/thongke/thuphiokytucxa2012/indanhsach" class="button">Danh sách hóa đơn</a></div>
            </div>
            <div class="right">
                <div id="sidebar_add_room">
                <h3 class="first">&nbsp;</h3>
                <form action="" method="get">
                    <h3>Người thu</h3>
                    <select name="user">
                        <option value="">All</option>';
foreach($htmlUser as $user)
{
    echo '<option value="' . $user[username] . '">' . $user['realname'] . '</option>' . "\n";
}
echo '
                    <select>
                    <h3>Thời gian từ</h3>
                    <input id="date_in" name="from" type="text" />
                    <h3>Đến</h3>
                    <input id="date_out" name="to"  type="text" />
                    <input type="submit" value="Tìm kiếm" class="button" />
                </form>
                </div>
            </div>
        </div>
    </div>';
echo '
    <!-- MODAL -->
    <div id="studen_roomfee" class="modal_form">
        <div class="header">
            <h2 id="feeinfo_name">Sinh vien</h2>
            <p id="feeinfo_desc">Phong</p>
        </div>
        <div id="feeinfo_main">
        </div>
    </div>
';

require_once 'Include/Footer.php';