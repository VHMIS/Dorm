<?php

$htmlPage['sitemap'][] = array('Ký Túc Xá', URL_PATH);
$htmlPage['sitemap'][] = array('Nội Trú', URL_PATH . 'noitru');
$htmlPage['sitemap'][] = array('Sinh Viên Khoá 2012 Thuê Phòng', '');
$htmlPage['menu'] = 'noitru';
$htmlPage['mainTitle'] = 'Sinh Viên Khoá 2012 Thuê Phòng';
$htmlPage['pageTitle'] = 'Sinh Viên Khoá 2012 Thuê Phòng - Ký Túc Xá';
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
echo '                </script>
                <h2 class="list_header">Danh sách phòng</h2>
                <ul class="tabs">
';
$tabs = '';
$js = '';
$i = 0;
if(isset($htmlArea))
{
    foreach($htmlArea as $area)
    {
        $tabs .= '                    <ul class="room_info_list' . ($i == 0 ? '' : ' hidden') . '" id="tabs_room_content_area_' . $area['id'] . '">';
        if(isset($htmlRoomArea['a_' . $area['id']]))
        {
            foreach($htmlRoomArea['a_' . $area['id']] as $id)
            {
                $tabs .= '<li id="r_' . $id . '"><div class="name">' . $htmlRoom['r_' . $id]['name'] . '</div><div class="gender">' . ($htmlRoom['r_' . $id]['gender'] == 2 ? '-' : '+') . '</div><div class="maxium">' . $htmlRoom['r_' . $id]['current_student'] . ' / ' . $htmlRoomType['rt_' . $htmlRoom['r_' . $id]['type']]['max_students'] . '</div></li>';
            }
        }
        $tabs .= '</ul>' . "\n";

        echo '                    <li class="tabs_room' . ($i != 0 ? '' : ' active') . '" id="tabs_room_area_' . $area['id'] . '">Dãy nhà ' . $area['name'] . '</li>' . "\n";

        if($i == 0)
        {
            $js = 'tabsControl[\'tabs_room_current\'] = \'tabs_room_content_area_' . $area['id'] . '\';';
        }

        $i++;
    }
}
echo '                </ul>
                <script>
                    ' . $js . '
                </script>
                <div class="tab_container">
';
echo $tabs;
echo '                </div>
                <h2 class="list_header" style="margin-top:20px">Danh sách sinh viên khoá 2012 mới vào</h2>
                <ul id="list_student_in_room" class="list_student_in_room">';
if(isset($htmlSIRooms))
{
    foreach($htmlSIRooms as $SIR)
    {
        echo '<li id="student_in_room_' . $SIR['id'] . '">
            <div class="name">' . $SIR['name'] . '</div>
            <div class="room" id="sir_id_' . $SIR['room_id'] . '">' . $SIR['room_name'] . '</div>
            <div class="gender">' . ($htmlRoom['r_' . $SIR['room_id']]['gender'] == 2 ? 'Nữ' : 'Nam') . '</div>
            <div class="fee">' . $SIR['total_fee'] . '</div>
            <div class="payedfee">' . $SIR['payed_fee'] . '</div>
            <div class="debt">' . ($SIR['total_fee'] - $SIR['payed_fee']) . '</div>
            <div class="money"><a rel="leanModal" href="#studen_roomfee" onclick="studentFee(' . $SIR['id'] . ')">Tien</a></div>
            <div class="delete"><a href="#" onclick="studentCancelRoom(' . $SIR['id'] . ');return false;">Del</a></div>
        </li>';
    }
}
echo
                '</ul>
            </div>
            <div class="right applysidebar">
                <script>
                    var selected_student = {
                        sex: 0,
                        name: \'\',
                        code: \'\',
                        id: 0,
                    };
                    var payment_fee = {
                        total_fee: 0,
                        payed_fee: 0,
                        debt: 0,
                        id: 0,
                    };
                </script>
                <div id="sidebar_add_room">
                <h3 class="first">Sinh Viên</h3>
                <form action="" onsubmit="findStudent();return false;">
                    <input id="student_code" type="text" />
                </form>
                <div id="student_info" class="box_info">Nhập SBD (bỏ số 0) ở ô trên để lấy thông tin</div>
                <a class="button" href="#" onclick="studentChangeSex();return false;">Đổi giới tính</a>
                <h3>Thời gian ở</h3>
                <p style="padding: 0 5px">Học kỳ I năm học 2011-2012, 5 tháng từ tháng 9-2011 đến tháng 1-2012</p>
                <input id="date_in" style="display:none" type="text" value="01/09/2011" />
                <input id="date_out" style="display:none" type="text" value="31/01/2012" />
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