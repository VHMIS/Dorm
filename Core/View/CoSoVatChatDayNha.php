<?php

$htmlPage['sitemap'][] = array('Ký Túc Xá', URL_PATH);
$htmlPage['sitemap'][] = array('Cơ Sở Vật Chất', URL_PATH . 'cosovatchat');
$htmlPage['sitemap'][] = array('Dãy Nhà', '');
$htmlPage['menu'] = 'cosovatchat';
$htmlPage['mainTitle'] = 'Dãy Nhà';
$htmlPage['pageTitle'] = 'Dãy Nhà - Ký Túc Xá';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/jquery.1.6.2.js';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/modal_lean.js';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/global.js';

require_once 'Include/Header.php';

echo '    <div id="main">
        <div class="main">
            <div class="left">';
echo '
                <h2 class="list_header">Danh sách dãy nhà</h2>
                <ul id="list_area" class="list_areas">';
if($htmlArea)
{
    foreach($htmlArea as $area)
    {
        echo '
                    <li id="a_' . $area['id'] . '">
                        <div class="name">' . $area['name'] . '</div>
                        <div class="edit"><a rel="leanModal" href="#update_area" onclick="insertAreaData(\'' . $area['id'] . '\')">Edit</a></div>
                        <div class="delete"><!--<a href="#" onclick="">Delete</a>--></div>
                    </li>
                    <script>
                        areaData[\'a_' . $area['id'] . '\'] = {
                            \'name\' : \'' . $area['name'] . '\'
                        };
                    </script>';
    }
}
echo '
                </ul>
                <div class="button"><a rel="leanModal" href="#new_area" class="button">Tạo dãy nhà mới</a></div>' . "\n";
echo '        </div>
            <div class="right">
            </div>
        </div>
    </div>';
echo '
    <!-- MODAL -->
    <div id="update_area" class="modal_form">
        <div class="header">
            <h2>Cập Nhật Dãy Nhà</h2>
            <p>Nhập các thông tin sau</p>
        </div>
        <form action="" onsubmit="updateArea();return false;">
            <input id="update_area_id" name="update_area_id" type="hidden" />
            <div class="txt-fld">
                <label for="update_area_name">Tên gọi</label>
                <input id="update_area_name" name="update_area_name" type="text" />
            </div>
            <div class="btn-fld">
                <button type="submit">Cập Nhật &raquo;</button>
            </div>
        </form>
    </div>
    <div id="new_area" class="modal_form">
        <div class="header">
            <h2>Tạo Dãy Nhà Mới</h2>
            <p>Nhập các thông tin sau</p>
        </div>
        <form action="" onsubmit="newArea();return false;">
            <div class="txt-fld">
                <label for="new_area_name">Tên gọi</label>
                <input id="new_area_name" name="new_area_name" type="text" />
            </div>
            <div class="btn-fld">
                <button type="submit">Tạo Mới &raquo;</button>
            </div>
        </form>
    </div>
';

require_once 'Include/Footer.php';