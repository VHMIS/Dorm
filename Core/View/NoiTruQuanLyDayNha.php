<?php

$htmlPage['sitemap'][] = array('Ký Túc Xá', URL_PATH);
$htmlPage['sitemap'][] = array('Nội Trú', URL_PATH . 'noitru');
$htmlPage['sitemap'][] = array('Quản Lý Sinh Viên', URL_PATH . 'noitru/quanly');
$htmlPage['sitemap'][] = array('Dãy Nhà ' . $htmlArea['a_' . $htmlIdDayNha]['name'], '');

$htmlPage['menu'] = 'noitru';
$htmlPage['mainTitle'] = 'Dãy Nhà ' . $htmlArea['a_' . $htmlIdDayNha]['name'];
$htmlPage['pageTitle'] = 'Quản Lý Sinh Viên Dãy Nhà ' . $htmlArea['a_' . $htmlIdDayNha]['name'] . ' - Ký Túc Xá';
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
                <script>
';
if(isset($htmlRoomType))
{
    foreach($htmlRoomType as $roomtype)
    {
        echo '                    roomTypeData[\'rt_' . $roomtype['id'] . '\'] = {
                        \'maxs\' : ' . $roomtype['max_students'] . ',
                        \'name\' : \'' . $roomtype['name'] . '\',
                        \'feed\' : ' . $roomtype['fee_day'] . ',
                        \'feem\' : ' . $roomtype['fee_month'] . '
                    };' . "\n";
    }
}
if(isset($htmlArea))
{
    foreach($htmlArea as $area)
    {
        echo '                    areaData[\'a_' . $area['id'] . '\'] = {
                        \'name\' : \'' . $area['name'] . '\'
                    };' . "\n";
    }
}
if(isset($htmlRoom))
{
    foreach($htmlRoom as $room)
    {
        echo '                    roomData[\'r_' . $room['id'] . '\'] = {
                        \'current_student\' : ' . $room['current_student'] . ',
                        \'name\' : \'' . $room['name'] . '\',
                        \'area\' : ' . $room['area'] . ',
                        \'type\' : ' . $room['type'] . ',
                        \'sex\' : ' . $room['gender'] . '
                    };' . "\n";
    }
}
echo 'var payment_fee = {
                       total_fee: 0,
                       payed_fee: 0,
                       debt: 0,
                       id: 0,
                   };';
echo '                </script>';

foreach($htmlRoominArea as $roomId)
{
    echo '<a name="' . $htmlRoom['r_' . $roomId]['name'] . '"></a>
            <h2 class="list_header">' . $htmlRoom['r_' . $roomId]['name'] . ' (' . $htmlRoomType['rt_' . $htmlRoom['r_' . $roomId]['type']]['name'] . ')</h2>';
    echo '<ul id="list_students_in_room_' . $roomId . '" class="list_students_in_room">';

    $max_student = $htmlRoomType['rt_' . $htmlRoom['r_' . $roomId]['type']]['max_students'];
    $current_student = 1;
    if(isset($htmlStudentInRoom['r_' . $roomId]))
    {
        foreach($htmlStudentInRoom['r_' . $roomId] as $sirId)
        {
            echo '<li id="student_in_room_' . $sirId . '">';
            //echo '<div class="num">' . $current_student . '.</div>';
            echo '<div class="num">-</div>';
            echo '<div class="room" id="sir_id_' . $roomId . '">' . $htmlRoom['r_' . $roomId]['name'] . '</div>';
            echo '<div class="name">' . $htmlStudentInRoom['s_' . $sirId]['student_name'] . '</div>';
            echo '<div class="class">' . $htmlStudentInRoom['s_' . $sirId]['class'] . '</div>';
            echo '<div class="date">' . $htmlStudentInRoom['s_' . $sirId]['date_in']['d'] . '.' . $htmlStudentInRoom['s_' . $sirId]['date_in']['m'] . ' - ' . $htmlStudentInRoom['s_' . $sirId]['date_out']['d'] . '.' . $htmlStudentInRoom['s_' . $sirId]['date_out']['m'] . '</div>';
            echo '<div class="duration">' . $htmlStudentInRoom['s_' . $sirId]['total_month'] . ' t ' . $htmlStudentInRoom['s_' . $sirId]['total_date'] . ' n</div>';
            echo '<div class="fee">' . $htmlStudentInRoom['s_' . $sirId]['total_fee'] . '</div>';
            echo '<div class="payed_fee">' . $htmlStudentInRoom['s_' . $sirId]['payed_fee'] . '</div>';
            echo '<div class="debt">' . $htmlStudentInRoom['s_' . $sirId]['debt'] . '</div>';
            echo '<div class="money"><a rel="leanModal" href="#studen_roomfee" onclick="studentFee(' . $sirId . ')">Tien</a></div>';
            echo '<div class="delete"><a href="#" onclick="studentCancelRoom(' . $sirId . ');return false;">Del</a></div>';
            echo '<div class="move"><a rel="leanModal" href="#studen_roomchange" onclick="studentMove(' . $sirId . ')">Move</a></div>';
            echo '<div class="out"><a href="#" onclick="studentOutRoom(' . $sirId . ');return false;">Out</a></div>';
            echo '</li>';
            $max_student--;
            $current_student++;
        }
    }

    for($i = $max_student; $i >= 1 ; $i--)
    {
        echo '<li>';
        //echo '<div class="num">' . $current_student . '.</div>';
        echo '<div class="num">-</div>';
        echo '<div class="name">&nbsp;</div><div></div><div></div><div></div></li>';
        $current_student++;
    }
    echo '</ul>';
}

echo
            '</div>
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
            echo '<a href="#' . $htmlRoom['r_' . $roomId]['name'] . '">' . $htmlRoom['r_' . $roomId]['name'] . '</a> ';
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
echo '
    <!-- MODAL -->
    <div id="studen_roomchange" class="modal_form">
        <div class="header">
            <h2 id="roomchange_name">Sinh vien</h2>
            <p id="roomchange_desc">Phong</p>
        </div>
        <div id="roomchange_main">
            <form action="" onsubmit="studentMoveRoom();return false;">
                <div class="txt-fld">
                    <input id="sir_id_change" name="" type="hidden" />
                    <label for="">Chuyển đến phòng</label>
                    <select id="room_id_change" name="">
                    </select>
                    <!--<label for="">Chọn ngày chuyển</label>-->
                    <!--<input id="date_change" name="" type="text" />-->
                </div>
                <div class="btn-fld">
                    <button type="submit">Chuyển phòng &raquo;</button>
                </div>
            </form>
        </div>
    </div>
';

require_once 'Include/Footer.php';