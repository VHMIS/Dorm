<?php

$htmlPage['sitemap'][] = array('Ký Túc Xá', URL_PATH);
$htmlPage['sitemap'][] = array('Cơ Sở Vật Chất', URL_PATH . 'cosovatchat');
$htmlPage['sitemap'][] = array('Phòng', '');
$htmlPage['menu'] = 'cosovatchat';
$htmlPage['mainTitle'] = 'Phòng';
$htmlPage['pageTitle'] = 'Phòng - Ký Túc Xá';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/jquery.1.6.2.js';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/modal_lean.js';
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
                <h2 class="list_header">Danh sách phòng chưa sử dụng</h2>
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
        if(isset($htmlRoomEmpty['a_' . $area['id']]))
        {
            foreach($htmlRoomEmpty['a_' . $area['id']] as $id)
            {
                $tabs .= '<li id="r_' . $id . '"><div class="name"><a onclick="insertRoomData(' . $id . ');" rel="leanModal" href="#update_room">' . $htmlRoom['r_' . $id]['name'] . '</a></div><div class="gender">' . ($htmlRoom['r_' . $id]['gender'] == 2 ? '-' : '+') . '</div><div class="maxium">' . $htmlRoomType['rt_' . $htmlRoom['r_' . $id]['type']]['max_students'] . '</div></li>';
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
                <div class="button"><a rel="leanModal" href="#new_room" class="button">Tạo phòng mới</a></div>
                <h2 class="list_header">Danh sách phòng đang sử dụng</h2>
                <ul class="tabs">
';
$tabs = '';
$js = '';
$i = 0;
if(isset($htmlArea))
{
    foreach($htmlArea as $area)
    {
        $tabs .= '                    <ul class="room_info_list' . ($i == 0 ? '' : ' hidden') . '" id="tabs_use_room_content_area_' . $area['id'] . '">';
        if(isset($htmlRoomUse['a_' . $area['id']]))
        {
            foreach($htmlRoomUse['a_' . $area['id']] as $id)
            {
                $tabs .= '<li id="r_' . $id . '"><div class="name">' . $htmlRoom['r_' . $id]['name'] . '</div><div class="gender">' . ($htmlRoom['r_' . $id]['gender'] == 2 ? '-' : '+') . '</div><div class="maxium">' . $htmlRoom['r_' . $id]['current_student'] . ' / ' . $htmlRoomType['rt_' . $htmlRoom['r_' . $id]['type']]['max_students'] . '</div></li>';
            }
        }
        $tabs .= '</ul>' . "\n";

        echo '                    <li class="tabs_use_room' . ($i != 0 ? '' : ' active') . '" id="tabs_use_room_area_' . $area['id'] . '">Dãy nhà ' . $area['name'] . '</li>'. "\n";

        if($i == 0)
        {
            $js = 'tabsControl[\'tabs_use_room_current\'] = \'tabs_use_room_content_area_' . $area['id'] . '\'';
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
                </div>
            <div class="right">
            </div>
        </div>
    </div>';
echo '
    <!-- MODAL -->
    <div id="update_room" class="modal_form">
        <div class="header">
            <h2>Cập Nhật Phòng</h2>
            <p>Nhập các thông tin sau</p>
        </div>
        <form action="" onsubmit="updateRoom();return false;">
            <input id="update_room_id" name="update_room_id" type="hidden" />
            <div class="txt-fld">
                <label for="update_room_name">Tên gọi</label>
                <input id="update_room_name" name="update_room_name" type="text" />
            </div>
            <div class="txt-fld">
                <label for="update_room_area">Dãy nhà</label>
                <select id="update_room_area" name="update_room_area">
                    <option value="0">Chọn</option>';
if(isset($htmlArea))
{
    foreach($htmlArea as $area)
    {
        echo '                    <option value="' . $area['id'] . '">' . $area['name'] . '</option>' . "\n";
    }
}
echo'                </select>
            </div>
            <div class="txt-fld">
                <label for="update_room_type">Loại phòng</label>
                <select id="update_room_type" name="update_room_type">
                    <option value="0">Chọn</option>';
if(isset($htmlRoomType))
{
    foreach($htmlRoomType as $roomtype)
    {
        echo '                    <option value="' . $roomtype['id'] . '">' . $roomtype['name'] . '</option>' . "\n";
    }
}
echo'                </select>
            </div>
            <div class="txt-fld">
                <label for="update_room_gender">Dành cho</label>
                <select id="update_room_gender" name="update_room_gender">
                    <option value="0">Chọn</option>
                    <option value="1">Nam</option>
                    <option value="2">Nữ</option>
                </select>
            </div>
            <div class="btn-fld">
                <button type="submit">Cập Nhật &raquo;</button>
            </div>
        </form>
    </div>
    <div id="new_room" class="modal_form">
        <div class="header">
            <h2>Tạo Phòng Mới</h2>
            <p>Nhập các thông tin sau</p>
        </div>
        <form action="" onsubmit="newRoom();return false;">
            <div class="txt-fld">
                <label for="new_room_name">Tên gọi</label>
                <input id="new_room_name" name="new_room_name" type="text" />
            </div>
            <div class="txt-fld">
                <label for="new_room_area">Dãy nhà</label>
                <select id="new_room_area" name="new_room_area">
                    <option value="0">Chọn</option>';
if(isset($htmlArea))
{
    foreach($htmlArea as $area)
    {
        echo '                    <option value="' . $area['id'] . '">' . $area['name'] . '</option>' . "\n";
    }
}
echo'                </select>
            </div>
            <div class="txt-fld">
                <label for="new_room_type">Loại phòng</label>
                <select id="new_room_type" name="new_room_type">
                    <option value="0">Chọn</option>';
if(isset($htmlRoomType))
{
    foreach($htmlRoomType as $roomtype)
    {
        echo '                    <option value="' . $roomtype['id'] . '">' . $roomtype['name'] . '</option>' . "\n";
    }
}
echo'                </select>
            </div>
            <div class="txt-fld">
                <label for="new_room_gender">Dành cho</label>
                <select id="new_room_gender" name="new_room_gender">
                    <option value="0">Chọn</option>
                    <option value="1">Nam</option>
                    <option value="2">Nữ</option>
                </select>
            </div>
            <div class="btn-fld">
                <button type="submit">Tạo Mới &raquo;</button>
            </div>
        </form>
    </div>
';

require_once 'Include/Footer.php';