<?php

$htmlPage['sitemap'][] = array('Ký Túc Xá', URL_PATH);
$htmlPage['sitemap'][] = array('Nội Trú', URL_PATH . 'noitru');
$htmlPage['sitemap'][] = array('Thống Kê Sinh Viên', URL_PATH . 'noitru/thongke/sinhvien');
$htmlPage['sitemap'][] = array('Tháng ' . $_ssvUri['params']['month'], '');

$htmlPage['menu'] = 'noitru';
$htmlPage['mainTitle'] = 'Thống Kê Sinh Viên Ở KTX Tháng ' . $_ssvUri['params']['month'];
$htmlPage['pageTitle'] = 'Thống Kê Sinh Viên Ở KTX Tháng ' . $_ssvUri['params']['month']. ' - Ký Túc Xá';
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
            <h2 class="list_header">' . $htmlPrevTotal . ' Sinh Viên Ở KTX Cuối Tháng Trước</h2>';
echo '<ul class="list_students_in_room">';
if(isset($htmlStudentInRoomLastMonth))
{
foreach($htmlStudentInRoomLastMonth as $htmlStudentInRoom)
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
    echo '</li>';
}
}
echo '</ul>';
echo '<h2 class="list_header">' . $htmlOutTotal . ' Sinh Viên Ra Tháng Này</h2><ul class="list_students_in_room">';
if(isset($htmlStudentInRoomOutMonth))
{
foreach($htmlStudentInRoomOutMonth as $htmlStudentInRoom)
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
    echo '</li>';
}
}
echo '</ul>';
echo '<h2 class="list_header">' . $htmlInTotal . ' Sinh Viên Vào Tháng Này</h2><ul class="list_students_in_room">';
if(isset($htmlStudentInRoomInMonth))
{
foreach($htmlStudentInRoomInMonth as $htmlStudentInRoom)
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
    echo '</li>';
}
}
echo '</ul>
    <h2 class="list_header">Xuất dữ liệu</h2>
    <div class="button">' . ($htmlOutTotal > 0 ? '<a href="' . URL_PATH . 'noitru/thongke/sinhvien/thang/' . $_ssvUri['params']['month'] . '/indanhsach/danhsachra" class="button">Danh sách sinh viên ra trong tháng</a>' : '') . ' ' . ($htmlInTotal > 0 ? '<a href="' . URL_PATH . 'noitru/thongke/sinhvien/thang/' . $_ssvUri['params']['month'] . '/indanhsach/danhsachvao" class="button">Danh sách sinh viên vào trong tháng</a>' : '') . '</div>';
echo
            '</div>
            <div class="right">
                <div id="sidebar_add_room">
                    <h3 class="first">&nbsp;</h3>
                    <h3 class="first">Số Liệu Thống kê Tháng ' . $_ssvUri['params']['month'] . '</h3>
                    <ul>
                        <li>Tháng trước : <b>' . $htmlPrevTotal . '</b></li>
                        <li>Tháng này ra : <b>' . $htmlOutTotal . '</b></li>
                        <li>Tháng này vào : <b>' . $htmlInTotal . '</b></li>
                        <li>Tổng cộng : <b>' . ($htmlPrevTotal + $htmlInTotal) . '</b></li>
                        <li>Thực tế : <b>' . ($htmlPrevTotal + $htmlInTotal - $htmlOutandIn) . '</b></li>
                    </ul>';
echo '          </div>
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