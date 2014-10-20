<?php

$htmlPage['sitemap'][] = array('Ký Túc Xá', URL_PATH);
$htmlPage['sitemap'][] = array('Nội Trú', URL_PATH . 'noitru');
$htmlPage['sitemap'][] = array('Quản Lý Sinh Viên', '');

$htmlPage['menu'] = 'noitru';
$htmlPage['mainTitle'] = 'Quản Lý Sinh Viên';
$htmlPage['pageTitle'] = 'Quản Lý Sinh Viên - Ký Túc Xá';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/jquery.1.6.2.js';
//$htmlPage['jsfile'][] = 'http://code.jquery.com/jquery-1.6.2.js';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/jquery-ui-1.8.15.js';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/modal_lean.js';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/stickyscroll.js';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/global.js';

require_once 'Include/Header.php';

echo '    <script>var payment_fee = {
                       total_fee: 0,
                       payed_fee: 0,
                       debt: 0,
                       id: 0,
                   };</script><div id="main">
        <div class="main">
            <div class="left">
            <h2 class="list_header">Danh sách sinh viên quá hạn / sắp đến hạn ra</h2>';
echo '<ul class="list_students_in_room">';
foreach($htmlStudentInRoomOutSoon as $htmlStudentInRoom)
{
    echo '<li id="student_in_room_' . $htmlStudentInRoom['id'] . '">';
    echo '<div class="num">-</div>';
    echo '<div class="room">' . $htmlStudentInRoom['room_name'] . '</div>';
    echo '<div class="name">' . $htmlStudentInRoom['student_name'] . '</div>';
    echo '<div class="class">' . $htmlStudentInRoom['class'] . '</div>';
    echo '<div class="duration">' . $htmlStudentInRoom['room_name'] . '</div>';
    echo '<div class="date">' . $htmlStudentInRoom['date_in']['d'] . '.' . $htmlStudentInRoom['date_in']['m'] . ' - ' . $htmlStudentInRoom['date_out']['d'] . '.' . $htmlStudentInRoom['date_out']['m'] . '</div>';
    echo '<div class="fee">' . $htmlStudentInRoom['total_fee'] . '</div>';
    echo '<div class="payed_fee">' . $htmlStudentInRoom['payed_fee'] . '</div>';
    echo '<div class="debt">' . $htmlStudentInRoom['debt'] . '</div>';
    echo '<div class="money"><a rel="leanModal" href="#studen_roomfee" onclick="studentFee(' . $htmlStudentInRoom['id'] . ')">Tien</a></div>';
    echo '<div class="delete"><a href="#" onclick="studentCancelRoom(' . $htmlStudentInRoom['id'] . ');return false;">Del</a></div>';
    echo '<div class="move"><a href="#" onclick="return false;">Move</a></div>';
    echo '<div class="out"><a href="#" onclick="studentOutRoom(' . $htmlStudentInRoom['id'] . '); return false;">Out</a></div>';
    echo '</li>';
}
echo '</ul>';
echo '<ul class="list_students_in_room">
        <h2 class="list_header">Danh sách sinh viên còn nợ</h2>';
foreach($htmlStudentInRoomDebt as $htmlStudentInRoom)
{
    echo '<li id="student_in_room_' . $htmlStudentInRoom['id'] . '">';
    echo '<div class="num">-</div>';
    echo '<div class="room">' . $htmlStudentInRoom['room_name'] . '</div>';
    echo '<div class="name">' . $htmlStudentInRoom['student_name'] . '</div>';
    echo '<div class="class">' . ($htmlStudentInRoom['class'] == '' ? '&nbsp;' : $htmlStudentInRoom['class']) . '</div>';
    echo '<div class="duration">' . $htmlStudentInRoom['room_name'] . '</div>';
    echo '<div class="date">' . $htmlStudentInRoom['date_in']['d'] . '.' . $htmlStudentInRoom['date_in']['m'] . ' - ' . $htmlStudentInRoom['date_out']['d'] . '.' . $htmlStudentInRoom['date_out']['m'] . '</div>';
    echo '<div class="fee">' . $htmlStudentInRoom['total_fee'] . '</div>';
    echo '<div class="payed_fee">' . $htmlStudentInRoom['payed_fee'] . '</div>';
    echo '<div class="debt">' . $htmlStudentInRoom['debt'] . '</div>';
    echo '<div class="money"><a rel="leanModal" href="#studen_roomfee" onclick="studentFee(' . $htmlStudentInRoom['id'] . ')">Tien</a></div>';
    echo '<div class="delete"><a href="#" onclick="studentCancelRoom(' . $htmlStudentInRoom['id'] . ');return false;">Del</a></div>';
    echo '<div class="move"><a href="#" onclick="return false;">Move</a></div>';
    echo '<div class="out"><a href="#" onclick="studentOutRoom(' . $htmlStudentInRoom['id'] . '); return false;">Out</a></div>';
    echo '</li>';
}
echo '</ul>
                <h2 class="list_header">Xuất dữ liệu</h2>
                <div class="button"><a href="' . URL_PATH . 'noitru/thongke/sinhvienno/indanhsach" class="button">Danh sách sinh viên còn nợ</a></div>
            </div>
            <div class="right">
                <div id="sidebar_add_room">
                    <h3 class="first">&nbsp;</h3>';
foreach($htmlArea as $area)
{
    echo '<a class="button" href="' . URL_PATH . 'noitru/quanly/daynha/' . $area['id'] . '">Dãy nhà ' . $area['name'] . '</a><br />' . "\n";
    if($area['id'] == $htmlIdDayNha)
    {
        echo '<div class="area_room_list">';
        foreach($htmlRoominArea as $roomId)
        {
            echo '<a href="' . URL_PATH . 'noitru/quanly/daynha/' . $area['id'] . '#' . $htmlRoom['r_' . $roomId]['name'] . '">' . $htmlRoom['r_' . $roomId]['name'] . '</a> ';
        }
        echo '<a href="' . URL_PATH . 'noitru/quanly/daynha/' . $area['id'] . '/xuatdulieu' . '">Danh sách sinh viên</a> ';
        echo '</div>';
    }
}
echo '                </div>
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