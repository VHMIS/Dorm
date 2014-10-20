<!DOCTYPE html>
<html>
<head>
    <title><?php echo $htmlPage['pageTitle']; ?> - ViethanAll</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="<?php echo URL_STATIC_CONTENT; ?>css/css.css" media="all" rel="stylesheet" type="text/css" />
    <link href="<?php echo URL_STATIC_CONTENT; ?>css/smoothness/jquery-ui-1.8.15.css" media="screen" rel="stylesheet" type="text/css" />
<?php
foreach($htmlPage['jsfile'] as $link)
{
    echo '    <script src="' . $link . '" type="text/javascript" language="javascript"></script>' . "\n";
}
?>
</head>
<body>
    <div id="header">
        <div class="global_bar">
            <ul class="left">
                <li><a href="#">Công Việc</a></li>
                <li><a href="#">Sinh Viên</a></li>
                <li><a href="#">Nhân Sự</a></li>
                <li><a href="<?php echo URL_PATH; ?>" class="selection">Ký Túc Xá</a></li>
                <li><a href="#">Đào Tạo</a></li>
                <li><a href="#">Thư Viện</a></li>
                <li><a href="#">Vật Tư Tài Sản</a></li>
            </ul>
            <ul class="right">
                <li><a href="#"><?php echo $sessionLogin->name; ?></a></li>
                <li><a href="<?php echo URL_PATH . 'logout'; ?>">Thoát</a></li>
            </ul>
        </div>
        <div class="application_bar">
            <h1>Ký Túc Xá</h1>
            <ul class="menu">
                <li class="no_menu"><a<?php if($htmlPage['menu'] == 'trangchu') echo ' class="selection"'; ?> href="<?php echo URL_PATH; ?>">Trang Chủ</a></li>
                <li class="sub_menu"><span class="title<?php if($htmlPage['menu'] == 'cosovatchat') echo ' selection'; ?>">Cơ Sở Vật Chất</span>
                    <ul class="sub_menu">
                        <li class="first"><a href="<?php echo URL_PATH . 'cosovatchat/daynha' ?>">Dãy Nhà</a></li>
                        <li class="last"><a href="<?php echo URL_PATH . 'cosovatchat/phong' ?>">Phòng</a></li>
                    </ul>
                </li>
                <li class="sub_menu"><span class="title<?php if($htmlPage['menu'] == 'noitru') echo ' selection'; ?>">Nội Trú HK2 - 2012-2013</span>
                    <ul class="sub_menu">
                        <li class="first"><a href="<?php echo URL_PATH . 'noitru/thuephong' ?>">Thuê Phòng</a></li>
                        <!--<li><a href="<?php echo URL_PATH . 'noitru/thuephong/nhaphoc2011' ?>">Sinh Viên Khoá 2012 Thuê Phòng</a></li>-->
                        <li><a href="<?php echo URL_PATH . 'noitru/quanly' ?>">Quản Lý Sinh Viên Ở KTX</a></li>
                        <li><a href="<?php echo URL_PATH . 'noitru/thongke/thuphiokytucxa' ?>">Thống Kê Thu Phí Ở Ký Túc Xá</a></li>
                        <li class="last"><a href="<?php echo URL_PATH . 'noitru/thongke/sinhvien' ?>">Thống Kê Sinh Viên Ở KTX</a></li>
                    </ul>
                </li>
                <li class="sub_menu"><span class="title<?php if($htmlPage['menu'] == 'kytruoc') echo ' selection'; ?>">Các Kỳ Trước</span>
                    <ul class="sub_menu right">
                        <li class="first"><a href="<?php echo URL_PATH . 'noitru/thongke/thuphiokytucxa2012' ?>">HK1 2012-2013</a></li>
                        <li><a href="<?php echo URL_PATH . 'noitru/thongke/thuphiokytucxa2011-2' ?>">HK2 2011-2012</a></li>
                        <li class="last"><a href="<?php echo URL_PATH . 'noitru/thongke/thuphiokytucxa2011' ?>">HK1 2011-2012</a></li>
                    </ul>
                </li>
                <li class="sub_menu"><span class="title<?php if($htmlPage['menu'] == 'thietlap') echo ' selection'; ?>">Thiết Lập</span>
                    <ul class="sub_menu right">
                        <li class="first"><a href="<?php echo URL_PATH . 'thietlap/danhmucloaiphong' ?>">Danh Mục Loại Phòng</a></li>
                        <li class="last"><a href="<?php echo URL_PATH . 'thietlap/danhmucmiengiam' ?>">Danh Mục Miễn Giảm</a></li>
                    </ul>
                </li>
            <ul>
        </div>
    </div>
    <div id="page_header">
        <div class="page_header_bar">
            <div class="page_info">
                <p><a href="#">ViethanAll</a><?php
foreach($htmlPage['sitemap'] as $sitemap)
{
    echo ' > ';
    if($sitemap[1] != '') echo '<a href="' . $sitemap[1] . '">' . $sitemap[0] . '</a>';
    else echo $sitemap[0];
}
?>

                <h1><?php echo $htmlPage['mainTitle']; ?></h1>
            </div>
            <div class="quick_menu_action">
            </div>
        </div>
    </div>
