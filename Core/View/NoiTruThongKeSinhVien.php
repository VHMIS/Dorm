<?php

$htmlPage['sitemap'][] = array('Ký Túc Xá', URL_PATH);
$htmlPage['sitemap'][] = array('Nội Trú', URL_PATH . 'noitru');
$htmlPage['sitemap'][] = array('Thống Kê Sinh Viên Ở KTX', '');
$htmlPage['menu'] = 'noitru';
$htmlPage['mainTitle'] = 'Thống Kê Sinh Viên Ở KTX';
$htmlPage['pageTitle'] = 'Thống Kê Sinh Viên Ở KTX - Ký Túc Xá';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/jquery.1.6.2.js';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/jquery-ui-1.8.15.js';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/modal_lean.js';
$htmlPage['jsfile'][] = URL_STATIC_CONTENT . 'js/global.js';

require_once 'Include/Header.php';

echo '    <div id="main">
        <div class="main">
            <div class="left full">
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
echo 'var roomInfo = {';
if(isset($htmlRoom))
{
    $count = count($htmlRoom);
    foreach($htmlRoom as $room)
    {
        echo '                    \'r_' . $room['id'] . '\' : {
                        \'current_student\' : ' . $room['current_student'] . ',
                        \'name\' : \'' . $room['name'] . '\',
                        \'area\' : ' . $room['area'] . ',
                        \'type\' : ' . $room['type'] . ',
                        \'sex\' : ' . $room['gender'] . ',
                        \'schoolyear\' : {';
        $count1 = count($room['schoolyear']);
        foreach($room['schoolyear'] as $schoolyear => $total)
        {
            echo '                            \'' . $schoolyear . '\' : ' . $total;
            if($count1 == 1) echo "\n";
            else echo ',' . "\n";
            $count1--;
        }
        echo '
                        }
                    }';
        if($count == 1) echo "\n";
        else echo ',' . "\n";
        $count--;
    }
}
echo '};';
echo '                </script>
                <a name="sodo"></a>
                <h2 class="list_header" id="map_title">Sơ đồ KTX : Phòng và Sinh viên</h2>
                <ul class="tabs">
';
$tabs = '';
$js = '';
$i = 0;
if(isset($htmlArea))
{
    foreach($htmlArea as $area)
    {
        $tabs .= '                    <div class="' . ($i == 0 ? '' : ' hidden') . '" id="tabs_room_content_area_' . $area['id'] . '">';
        $rightArea = $leftArea = '<ul class="room_info_list">';

        if(isset($htmlRoomArea['a_' . $area['id']]))
        {
            foreach($htmlRoomArea['a_' . $area['id']] as $id)
            {

                //if($level != $htmlRoom['r_' . $id]['name'][1])
                //{
                    //if($level != 0) $tabs .= '</ul>';
                    //$tabs .= '<ul class="room_info_list">';
                    //$level++;
                //}
                $roomDetail = '';
                $roomDetail .= '<li id="r_' . $id . '"><div class="name">' . $htmlRoom['r_' . $id]['name'] . '</div>';
                if($htmlRoomType['rt_' . $htmlRoom['r_' . $id]['type']]['max_students'] > 0)
                {
                    $roomDetail .= '<div class="gender">' . ($htmlRoom['r_' . $id]['gender'] == 2 ? '-' : '+') . '</div><div class="maxium">' . $htmlRoom['r_' . $id]['current_student'] . ' / ' . $htmlRoomType['rt_' . $htmlRoom['r_' . $id]['type']]['max_students'] . '</div><div class="info">fdf</div></li>';
                }
                else
                {
                    $roomDetail .= '<div class="gender none">&nbsp;</div><div class="maxium none">&nbsp;</div></li>';
                }

                $numberRoom = str_replace(array('A', 'B', '-'), '', $htmlRoom['r_' . $id]['name']);
                if($numberRoom % 2 == 0) $rightArea .= $roomDetail;
                else $leftArea .= $roomDetail;
            }
        }

        $leftArea .= '</ul>';
        $rightArea .= '</ul>';

        $tabs .= $rightArea . "\n" . $leftArea . "\n" . "</div>";

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
                <div class="button"><a href="#sodo" onclick="viewRoomMap(\'sinhvieno\', \'\');" class="button">Phòng và Sinh Viên</a>&nbsp;&nbsp;&nbsp;<a href="#sodo" onclick="viewRoomMap(\'sinhvienotheokhoa\', \'\');" class="button">Phòng và Sinh Viên (Khóa)</a>&nbsp;&nbsp;&nbsp;<a href="#sodo" onclick="viewRoomMap(\'chocondu\', \'\');" class="button">Phòng còn trống</a></div>
                <h2 class="list_header">Số liệu thống kê</h2>
                <ul class="tabs">
                    <li class="tabs_stat active" id="tabs_stat_student">Thống Kê Chung</li>
                    <li class="tabs_stat" id="tabs_stat_k">Thông Kê Sinh Viên Ở KTX Theo Khoá</li>
                    <li class="tabs_stat" id="tabs_stat_tanggiam">Thông Kê Tăng Giảm (beta)</li>
                </ul>
                <script>
                    tabsControl[\'tabs_stat_current\'] = \'tabs_stat_content_student\';
                </script>
                <div class="tab_container">
                    <div id="tabs_stat_content_student">
                    <div>
                    <ul class="stats_area" >
                        <li class="one_area">
                            <h3>' . $htmlStats['rooms']['total'] . ' Phòng - Nam / Nữ</h3>
                            <ul>
                                <li>
                                    <div class="text">Phòng nam</div>
                                    <div>' . $htmlStats['rooms']['genre']['male'] . '</div>
                                </li>
                                <li>
                                    <div class="text">Phòng nữ</div>
                                    <div>' . $htmlStats['rooms']['genre']['female'] . '</div>
                                </li>
                                <li>
                                    <div class="text">Phòng không sử dụng (dùng cho việc khác hoặc hư hỏng, sửa chữa)</div>
                                    <div>' . $htmlStats['unstats_rooms']['total'] . '</div>
                                </li>
                            </ul>
                            <div class="chart">
                                <img src="http://chart.apis.google.com/chart?chs=130x130&chds=0,' . $htmlStats['rooms']['total'] . '&cht=p&chd=t:' . $htmlStats['rooms']['genre']['male'] . ',' . $htmlStats['rooms']['genre']['female'] . ',' . $htmlStats['unstats_rooms']['total'] . '" alt="" />
                            </div>
                        </li>
                        <li class="one_area">
                            <h3>' . $htmlStats['rooms']['total'] . ' Phòng - Loại Phòng</h3>
                            <ul>';
foreach($htmlStats['rooms']['type'] as $id => $value)
{
    $statNumber[] = $value;
    echo '                                <li>
                                    <div class="text">' . $htmlRoomType[$id]['name'] . '</div>
                                    <div>' . $value . '</div>
                                </li>';
}
$statNumber[] = $htmlStats['unstats_rooms']['total'];
$statNumber = implode($statNumber, ',');
echo '
                                <li>
                                    <div class="text">Phòng không sử dụng</div>
                                    <div>' . $htmlStats['unstats_rooms']['total'] . '</div>
                                </li>
                            </ul>
                            <div class="chart">
                                <img src="http://chart.apis.google.com/chart?chs=130x130&chds=0,' . $htmlStats['rooms']['total'] . '&cht=p&chd=t:' . $statNumber . '" alt="" />
                            </div>
                        </li>
                        <li class="one_area">
                            <h3>' . $htmlStats['rooms']['total'] . ' Phòng - Loại Phòng & Nam / Nữ</h3>
                            <ul>';
unset($statNumber);
foreach($htmlStats['rooms']['type_gender'] as $id => $value)
{
    $statNumber[] = $value['male'];
    $statNumber[] = $value['female'];
    echo '                                <li>
                                    <div class="text">' . $htmlRoomType[$id]['name'] . ' - Nam</div>
                                    <div>' . $value['male'] . '</div>
                                </li>
                                <li>
                                    <div class="text">' . $htmlRoomType[$id]['name'] . ' - Nữ</div>
                                    <div>' . $value['female'] . '</div>
                                </li>';
}
$statNumber[] = $htmlStats['unstats_rooms']['total'];
$statNumber = implode($statNumber, ',');
echo '
                                <li>
                                    <div class="text">Phòng không sử dụng</div>
                                    <div>' . $htmlStats['unstats_rooms']['total'] . '</div>
                                </li>
                            </ul>
                            <div class="chart">
                                <img src="http://chart.apis.google.com/chart?chs=130x130&chds=0,' . $htmlStats['rooms']['total'] . '&cht=p&chd=t:' . $statNumber . '" alt="" />
                            </div>
                        </li>
                    </ul>
                    </div>
                    <div>
                    <ul class="stats_area">
                        <li class="one_area">
                            <h3>' . $htmlStats['beds']['total'] . ' Chổ ở (Giường) - Nam / Nữ</h3>
                            <ul>
                                <li>
                                    <div class="text">Dành cho nam</div>
                                    <div>' . $htmlStats['beds']['genre']['male'] . '</div>
                                </li>
                                <li>
                                    <div class="text">Dành cho nữ</div>
                                    <div>' . $htmlStats['beds']['genre']['female'] . '</div>
                                </li>
                            </ul>
                            <div class="chart">
                                <img src="http://chart.apis.google.com/chart?chs=130x130&chds=0,' . $htmlStats['beds']['total'] . '&cht=p&chd=t:' . $htmlStats['beds']['genre']['male'] . ',' . $htmlStats['beds']['genre']['female'] . '" alt="" />
                            </div>
                        </li>
                        <li class="one_area">
                            <h3>' . $htmlStats['beds']['total'] . ' Chổ ở (Giường) - Loại Phòng</h3>
                            <ul>';
unset($statNumber);
foreach($htmlStats['beds']['type'] as $id => $value)
{
    $statNumber[] = $value;
    echo '                                <li>
                                    <div class="text">' . $htmlRoomType[$id]['name'] . '</div>
                                    <div>' . $value . '</div>
                                </li>';
}
$statNumber = implode($statNumber, ',');
echo '
                            </ul>
                            <div class="chart">
                                <img src="http://chart.apis.google.com/chart?chs=130x130&chds=0,' . $htmlStats['beds']['total'] . '&cht=p&chd=t:' . $statNumber . '" alt="" />
                            </div>
                        </li>
                        <li class="one_area">
                            <h3>' . $htmlStats['beds']['total'] . ' Chổ ở (Giường) - Loại Phòng & Nam / Nữ</h3>
                            <ul>';
unset($statNumber);
foreach($htmlStats['beds']['type_gender'] as $id => $value)
{
    $statNumber[] = $value['male'];
    $statNumber[] = $value['female'];
    echo '                                <li>
                                    <div class="text">' . $htmlRoomType[$id]['name'] . ' - Nam</div>
                                    <div>' . $value['male'] . '</div>
                                </li>
                                <li>
                                    <div class="text">' . $htmlRoomType[$id]['name'] . ' - Nữ</div>
                                    <div>' . $value['female'] . '</div>
                                </li>';
}
$statNumber = implode($statNumber, ',');
echo '
                            </ul>
                            <div class="chart">
                                <img src="http://chart.apis.google.com/chart?chs=130x130&chds=0,' . $htmlStats['beds']['total'] . '&cht=p&chd=t:' . $statNumber . '" alt="" />
                            </div>
                        </li>
                    </ul>
                    </div>
                    <div>
                    <ul class="stats_area">
                        <li class="one_area">
                            <h3>' . $htmlStats['students']['total'] . ' Sinh Viên ở KTX - Nam / Nữ</h3>
                            <ul>
                                <li>
                                    <div class="text">Nam</div>
                                    <div>' . $htmlStats['students']['gender']['male'] . '</div>
                                </li>
                                <li>
                                    <div class="text">Nam - Dư</div>
                                    <div>' . ($htmlStats['beds']['genre']['male'] - $htmlStats['students']['gender']['male']) . '</div>
                                </li>
                                <li>
                                    <div class="text">Nữ</div>
                                    <div>' . $htmlStats['students']['gender']['female'] . '</div>
                                </li>
                                <li>
                                    <div class="text">Nữ - Dư</div>
                                    <div>' . ($htmlStats['beds']['genre']['female'] - $htmlStats['students']['gender']['female']) . '</div>
                                </li>
                            </ul>
                            <div class="chart">
                                <img src="http://chart.apis.google.com/chart?chs=130x130&chds=0,' . $htmlStats['students']['total'] . '&cht=p&chd=t:' . $htmlStats['students']['gender']['male'] . ',' . ($htmlStats['beds']['genre']['male'] - $htmlStats['students']['gender']['male']) . ',' . $htmlStats['students']['gender']['female'] . ',' . ($htmlStats['beds']['genre']['female'] - $htmlStats['students']['gender']['female']) . '" alt="" />
                            </div>
                        </li>
                        <li class="one_area">
                            <h3>' . $htmlStats['students']['total'] . ' Sinh Viên ở KTX - Loại Phòng</h3>
                            <ul>';
unset($statNumber);
foreach($htmlStats['students']['type'] as $id => $value)
{
    $statNumber[] = $value;
    echo '                                <li>
                                    <div class="text">' . $htmlRoomType[$id]['name'] . '</div>
                                    <div>' . $value . '</div>
                                </li>';
    $statNumber[] = $htmlStats['beds']['type'][$id] - $value;
    echo '                                <li>
                                    <div class="text">' . $htmlRoomType[$id]['name'] . ' - Dư</div>
                                    <div>' . ($htmlStats['beds']['type'][$id] - $value) . '</div>
                                </li>';
}
$statNumber = implode($statNumber, ',');
echo '
                            </ul>
                            <div class="chart">
                                <img src="http://chart.apis.google.com/chart?chs=130x130&chds=0,' . $htmlStats['students']['total'] . '&cht=p&chd=t:' . $statNumber . '" alt="" />
                            </div>
                        </li>
                        <li class="one_area">
                            <h3>' . $htmlStats['students']['total'] . ' Sinh Viên ở KTX - Loại Phòng & Nam / Nữ</h3>
                            <ul>';
unset($statNumber);
foreach($htmlStats['students']['type_gender'] as $id => $value)
{
    $statNumber[] = $value['male'];
    $statNumber[] = $htmlStats['beds']['type_gender'][$id]['male'] - $value['male'];
    $statNumber[] = $value['female'];
    $statNumber[] = $htmlStats['beds']['type_gender'][$id]['female'] - $value['female'];
    echo '                                <li>
                                    <div class="text">' . $htmlRoomType[$id]['name'] . ' - Nam</div>
                                    <div>' . $value['male'] . '</div>
                                </li>
                                <li>
                                    <div class="text">' . $htmlRoomType[$id]['name'] . ' - Nam - Dư</div>
                                    <div>' . ($htmlStats['beds']['type_gender'][$id]['male'] - $value['male']) . '</div>
                                </li>
                                <li>
                                    <div class="text">' . $htmlRoomType[$id]['name'] . ' - Nữ</div>
                                    <div>' . $value['female'] . '</div>
                                </li>
                                <li>
                                    <div class="text">' . $htmlRoomType[$id]['name'] . ' - Nữ - Dư</div>
                                    <div>' . ($htmlStats['beds']['type_gender'][$id]['female'] - $value['female']) . '</div>
                                </li>';
}
$statNumber = implode($statNumber, ',');
echo '
                            </ul>
                            <div class="chart">
                                <img src="http://chart.apis.google.com/chart?chs=130x130&chds=0,' . $htmlStats['students']['total'] . '&cht=p&chd=t:' . $statNumber . '" alt="" />
                            </div>
                        </li>
                    </ul>
                    </div>
                    </div>
                    <div id="tabs_stat_content_k" class="hidden">
                    <div>
                    <ul class="stats_area">
                        <li class="one_area">
                            <h3>' . $htmlStats['students']['total'] . ' Sinh Viên ở KTX - Khóa</h3>
                            <ul>';
unset($statNumber);
foreach($htmlStats['school_year'] as $id => $value)
{
    $khoa = $id[2];
    $statNumber[] = $value['total'];
    echo '                                <li>
                                    <div class="text">Khóa ' . $khoa . '</div>
                                    <div>' . $value['total'] . ' <a href="#sodo" onclick="viewRoomMap(\'sinhvienokhoa\', \'' . $khoa . '\');" >(S)</a></div>
                                </li>';
}
$statNumber = implode($statNumber, ',');
echo '
                            </ul>
                            <div class="chart">
                                <img src="http://chart.apis.google.com/chart?chs=130x130&chds=0,' . $htmlStats['students']['total'] . '&cht=p&chd=t:' . $statNumber . '" alt="" />
                            </div>
                        </li>';
$count = 1;

foreach($htmlStats['school_year'] as $id => $value)
{
    if($value['total'] > 0)
    {
        $khoa = $id[2];
        if($count == 0)
        {
            echo '
                    <div>
                    <ul class="stats_area">';
        }
        echo '
                        <li class="one_area">
                            <h3>' . $value['total'] . ' Sinh Viên Khóa ' . $khoa . ' ở KTX</h3>
                            <ul>
                                <li>
                                    <div class="text">Nam</div>
                                    <div>' . $value['male'] . '</div>
                                </li>
                                <li>
                                    <div class="text">Nữ</div>
                                    <div>' . $value['female'] . '</div>
                                </li>
                            </ul>
                            <div class="chart">
                                <img src="http://chart.apis.google.com/chart?chs=130x130&chds=0,' . $value['total'] . '&cht=p&chd=t:' . $value['male'] . ',' . $value['female'] . '" alt="" />
                            </div>
                        </li>';
        if($count == 2)
        {
            echo '
                    </ul>
                    </div>';
            $count = 0;
        }
        else
        {
            $count++;
        }
    }
}
echo '
                        </ul></div>
                    </div>
                    <div id="tabs_stat_content_tanggiam" class="hidden">
                        <div class="stats_area">
                            <div class="thongke_tanggiam">
                            <h3>Số lượng sinh viên ở KTX qua các tháng</h3>
                            <ul>';
if(isset($htmlStudentInRoomPassMonth))
{
    foreach($htmlStudentInRoomPassMonth as $month => $data)
    {
        echo '<li>
            <div class="text">Tháng ' . $month . '</div>
            <div><a href="' . URL_PATH . 'noitru/thongke/sinhvien/thang/' . $month . '">' . $data['total'];
        if($data['diff'] != 0) echo ' (' . ($data['diff'] > 0 ? '+' : '') . $data['diff'] . ')';
        echo '</a></div>
        </li>';
        $statsSIRbyMonth[] = $data['total'];
    }
}
echo'                          </ul>
                            <h3>Dự kiến các tháng sắp tới</h3>
                            <ul>';

if(isset($htmlStudentInRoomNextMonth))
{
    foreach($htmlStudentInRoomNextMonth as $month => $data)
    {
        echo '<li>
            <div class="text">Tháng ' . $month . '</div>
            <div>' . $data['total'];
        if($data['diff'] != 0) echo ' (' . ($data['diff'] > 0 ? '+' : '') . $data['diff'] . ')';
        echo '</div>
        </li>';
        $statsSIRbyMonth[] = $data['total'];
    }
}

echo '                            </ul>
                            <h3>Dự đoán các tháng sau nữa</h3>
                            <ul>';
if(isset($htmlStudentInRoomFutureMonth))
{
    foreach($htmlStudentInRoomFutureMonth as $month => $data)
    {
        echo '<li>
            <div class="text">Tháng ' . $month . '</div>
            <div>' . $data['total'];
        if($data['diff'] != 0) echo ' (' . ($data['diff'] > 0 ? '+' : '') . $data['diff'] . ')';
        echo '</div>
        </li>';
        $statsSIRbyMonth[] = $data['total'];
    }
}
echo '
                            </ul>
                            </div>
                            <div class="thongke_tanggiam_bieudo">
                                <img src="http://chart.apis.google.com/chart?chxl=1:|8|9|10|11|12|1|2|3|4|5|6|7&chxr=0,0,1500&chxs=0,676767,11.5,-0.5,l,676767|1,676767,11.5,-0.333,l,676767&chxt=y,x&chbh=a,0,12&chs=700x350&cht=bvg&chco=3072F3&chds=-3.333,1500&chd=t:' . implode(',', $statsSIRbyMonth) . '&chdlp=b&chma=0,0,0,30" width="700" height="350" alt="" />
                            </div>
                        </div>
                    </div>
                </div>
                <h2 class="list_header">Xuất dữ liệu</h2>
                <div class="button"><a href="' . URL_PATH . 'noitru/thongke/sinhvien/indanhsach" class="button">Danh sách sinh viên ở KTX</a></div>';
echo '
            </div>
        </div>
    </div>';

require_once 'Include/Footer.php';