<?php

$htmlPage['sitemap'][] = array('Ký Túc Xá', URL_PATH);
$htmlPage['sitemap'][] = array('Thiết Lập', URL_PATH . 'thietlap');
$htmlPage['sitemap'][] = array('Danh Mục Loại Phòng', '');
$htmlPage['menu'] = 'thietlap';
$htmlPage['mainTitle'] = 'Danh Mục Loại Phòng';
$htmlPage['pageTitle'] = 'Danh Mục Loại Phòng - Ký Túc Xá';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/jquery.1.6.2.js';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/modal_lean.js';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/global.js';

require_once 'Include/Header.php';

echo '    <div id="main">
        <div class="main">
            <div class="left">';
echo '
                <h2 class="list_header">Danh mục loại phòng</h2>
                <ul id="list_room_type" class="list_room_types">';
if($htmlRoomType)
{
    foreach($htmlRoomType as $roomtype)
    {
        echo '
                    <li id="rt_' . $roomtype['id'] . '">
                        <div class="name">' . $roomtype['name'] . '</div>
                        <div class="max_students">' . $roomtype['max_students'] . '</div>
                        <div class="fee_day">' . $roomtype['fee_day'] . ' VND</div>
                        <div class="fee_month">' . $roomtype['fee_month'] . ' VND</div>
                        <div class="edit"><a rel="leanModal" href="#update_room_type" onclick="insertRoomTypeData(\'' . $roomtype['id'] . '\')">Edit</a></div>
                        <div class="delete"><!--<a href="#" onclick="">Delete</a>--></div>
                    </li>
                    <script>
                        roomTypeData[\'rt_' . $roomtype['id'] . '\'] = {
                            \'maxs\' : ' . $roomtype['max_students'] . ',
                            \'name\' : \'' . $roomtype['name'] . '\',
                            \'feed\' : ' . $roomtype['fee_day'] . ',
                            \'feem\' : ' . $roomtype['fee_month'] . '
                        };
                    </script>';
    }
}
echo '
                </ul>
                <div class="button"><a rel="leanModal" href="#new_room_type" class="button">Tạo loại phòng mới</a></div>' . "\n";
echo '        </div>
            <div class="right">
            </div>
        </div>
    </div>';
echo '
    <!-- MODAL -->
    <div id="update_room_type" class="modal_form">
        <div class="header">
            <h2>Cập Nhật Loại Phòng</h2>
            <p>Nhập các thông tin sau</p>
        </div>
        <form action="" onsubmit="updateRoomType();return false;">
            <input id="update_room_type_id" name="update_room_type_id" type="hidden" />
            <div class="txt-fld">
                <label for="update_room_type_name">Tên gọi</label>
                <input id="update_room_type_name" name="update_room_type_name" type="text" />
            </div>
            <div class="txt-fld">
                <label for="update_room_type_max_student">Số lượng SV</label>
                <input id="update_room_type_max_students" name="update_room_type_max_students" type="text" />
            </div>
            <div class="txt-fld">
                <label for="update_room_type_fee_day">Giá ngày</label>
                <input id="update_room_type_fee_day" name="update_room_type_fee_day" type="text" />
            </div>
            <div class="txt-fld">
                <label for="update_room_type_fee_month">Giá tháng</label>
                <input id="update_room_type_fee_month" name="update_room_type_fee_month" type="text" />
            </div>
            <div class="btn-fld">
                <button type="submit">Cập Nhật &raquo;</button>
            </div>
        </form>
    </div>
    <div id="new_room_type" class="modal_form">
        <div class="header">
            <h2>Tạo Loại Phòng Mới</h2>
            <p>Nhập các thông tin sau</p>
        </div>
        <form action="" onsubmit="newRoomType();return false;">
            <div class="txt-fld">
                <label for="new_room_type_name">Tên gọi</label>
                <input id="new_room_type_name" name="new_room_type_name" type="text" />
            </div>
            <div class="txt-fld">
                <label for="new_room_type_max_student">Số lượng SV</label>
                <input id="new_room_type_max_students" name="new_room_type_max_students" type="text" />
            </div>
            <div class="txt-fld">
                <label for="new_room_type_fee_day">Giá ngày</label>
                <input id="new_room_type_fee_day" name="new_room_type_fee_day" type="text" />
            </div>
            <div class="txt-fld">
                <label for="new_room_type_fee_month">Giá tháng</label>
                <input id="new_room_type_fee_month" name="new_room_type_fee_month" type="text" />
            </div>
            <div class="btn-fld">
                <button type="submit">Tạo Mới &raquo;</button>
            </div>
        </form>
    </div>
';

require_once 'Include/Footer.php';