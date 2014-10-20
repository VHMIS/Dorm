<?php

$ssvConfigDb = array
(
    'host'     => 'localhost',
    'username' => 'root',
    'password' => '123',
    'dbname'   => 'viethanit_ktx'
);

$configUriRules = array(
    // Trang chu
    new MCFUriRule(''         , 'Index', '', null, null, ''),
    new MCFUriRule('index.php', 'Index', '', null, null, ''),
    new MCFUriRule('login'    , 'Login', '', null, null, ''),
    new MCFUriRule('logout'    , 'Logout', '', null, null, ''),
    new MCFUriRule('noitru', 'NoiTru', 'ThuePhong', null, null, ''),
    new MCFUriRule('noitru/thuephong', 'NoiTru', 'ThuePhong', null, null, ''),
    new MCFUriRule('noitru/thuephong/nhaphoc2011', 'NoiTru', 'ThuePhong2011', null, null, ''),
    new MCFUriRule('noitru/thuephong/trogiup/timsinhvien', 'NoiTru', 'ThuePhongTroGiup', array('ajax', 'json'), array('action' => 'findstudent'), ''),
    new MCFUriRule('noitru/thuephong/trogiup/thongtinphi', 'NoiTru', 'ThuePhongTroGiup', array('ajax', 'html'), array('action' => 'thongtinphi'), ''),
    new MCFUriRule('noitru/thuephong/trogiup/doigioitinh', 'NoiTru', 'ThuePhongTroGiup', array('ajax', 'html'), array('action' => 'changesex'), ''),
    new MCFUriRule('noitru/thuephong/sinhvienthuephong', 'NoiTru', 'ThuePhongQuanLy', array('ajax', 'text'), array('action' => 'studentgoroom'), ''),
    new MCFUriRule('noitru/thuephong/traphi', 'NoiTru', 'ThuePhongQuanLy', array('ajax', 'text'), array('action' => 'payment'), ''),
    new MCFUriRule('noitru/thuephong/huytraphi', 'NoiTru', 'ThuePhongQuanLy', array('ajax', 'text'), array('action' => 'cancelpayment'), ''),
    new MCFUriRule('noitru/thuephong/huythuephong', 'NoiTru', 'ThuePhongQuanLy', array('ajax', 'text'), array('action' => 'cancelroom'), ''),
    new MCFUriRule('noitru/thuephong/rakhoiphong', 'NoiTru', 'ThuePhongQuanLy', array('ajax', 'text'), array('action' => 'outroom'), ''),
    new MCFUriRule('noitru/thuephong/chuyenphong', 'NoiTru', 'ThuePhongQuanLy', array('ajax', 'text'), array('action' => 'changeroom'), ''),
    new MCFUriRule('noitru/thuephong/inhoadon/id:digit', 'NoiTru', 'ThuePhongQuanLy', null, array('action' => 'printinvoice'), ''),
    new MCFUriRule('noitru/quanly', 'NoiTru', 'QuanLy', null, null, ''),
    new MCFUriRule('noitru/thongke/sinhvienno/indanhsach', 'NoiTru', 'ThongKeSinhVienNo', array('excel', ''), null, ''),
    new MCFUriRule('noitru/quanly/daynha/id:digit', 'NoiTru', 'QuanLyDayNha', array('html', ''), null, ''),
    new MCFUriRule('noitru/quanly/daynha/id:digit/xuatdulieu', 'NoiTru', 'QuanLyDayNha', array('excel', ''), null, ''),
    new MCFUriRule('noitru/thongke/sinhvien', 'NoiTru', 'ThongKeSinhVien', array('html', ''), null, ''),
    new MCFUriRule('noitru/thongke/sinhvien/thang/month:string', 'NoiTru', 'ThongKeSinhVienTheoThang', array('html', ''), array('type' => ''), ''),
    new MCFUriRule('noitru/thongke/sinhvien/thang/month:string/indanhsach/danhsachra', 'NoiTru', 'ThongKeSinhVienTheoThang', array('excel', ''), array('type' => 'out'), ''),
    new MCFUriRule('noitru/thongke/sinhvien/thang/month:string/indanhsach/danhsachvao', 'NoiTru', 'ThongKeSinhVienTheoThang', array('excel', ''), array('type' => 'in'), ''),
    new MCFUriRule('noitru/thongke/sinhvieno/thang/month:string', 'NoiTru', 'ThongKeSinhVienOTheoThang', array('html', ''), array('type' => ''), ''),
    new MCFUriRule('noitru/thongke/sinhvieno/thang/month:string/indanhsach', 'NoiTru', 'ThongKeSinhVienOTheoThang', array('excel', ''), array('type' => ''), ''),
    new MCFUriRule('noitru/thongke/sinhvien/indanhsach', 'NoiTru', 'ThongKeSinhVien', array('excel', ''), null, ''),
    new MCFUriRule('noitru/thongke/thuphiokytucxa', 'NoiTru', 'ThongKeThuTien', array('html', ''), null, ''),
    new MCFUriRule('noitru/thongke/thuphiokytucxa/indanhsach', 'NoiTru', 'ThongKeThuTien', array('excel', ''), null, ''),
    new MCFUriRule('noitru/thongke/thuphiokytucxa2011', 'NoiTru', 'ThongKeThuTien2011', array('html', ''), null, ''),
    new MCFUriRule('noitru/thongke/thuphiokytucxa2011/indanhsach', 'NoiTru', 'ThongKeThuTien2011', array('excel', ''), null, ''),
    new MCFUriRule('noitru/thongke/thuphiokytucxa2011-2', 'NoiTru', 'ThongKeThuTien2011-2', array('html', ''), null, ''),
    new MCFUriRule('noitru/thongke/thuphiokytucxa2011-2/indanhsach', 'NoiTru', 'ThongKeThuTien2011-2', array('excel', ''), null, ''),
    new MCFUriRule('noitru/thongke/thuphiokytucxa2012', 'NoiTru', 'ThongKeThuTien2012', array('html', ''), null, ''),
    new MCFUriRule('noitru/thongke/thuphiokytucxa2012/indanhsach', 'NoiTru', 'ThongKeThuTien2012', array('excel', ''), null, ''),
    new MCFUriRule('cosovatchat', 'CoSoVatChat', 'DayNha', null, null, ''),
    new MCFUriRule('cosovatchat/daynha', 'CoSoVatChat', 'DayNha', null, null, ''),
    new MCFUriRule('cosovatchat/daynha/update', 'CoSoVatChat', 'DayNhaQuanLy', array('ajax', 'text'), array('action' => 'update'), ''),
    new MCFUriRule('cosovatchat/daynha/new', 'CoSoVatChat', 'DayNhaQuanLy', array('ajax', 'text'), array('action' => 'new'), ''),
    new MCFUriRule('cosovatchat/phong', 'CoSoVatChat', 'Phong', null, null, ''),
    new MCFUriRule('cosovatchat/phong/update', 'CoSoVatChat', 'PhongQuanLy', array('ajax', 'text'), array('action' => 'update'), ''),
    new MCFUriRule('cosovatchat/phong/new', 'CoSoVatChat', 'PhongQuanLy', array('ajax', 'text'), array('action' => 'new'), ''),
    new MCFUriRule('thietlap/danhmucloaiphong', 'ThietLap', 'DanhMucLoaiPhong', null, null, ''),
    new MCFUriRule('thietlap', 'ThietLap', 'DanhMucLoaiPhong', null, null, ''),
    new MCFUriRule('thietlap/danhmucmiengiam', 'ThietLap', 'DanhMucMienGiam', null, null, ''),
    new MCFUriRule('thietlap/danhmucloaiphong/update', 'ThietLap', 'DanhMucLoaiPhongQuanLy', array('ajax', 'text'), array('action' => 'update'), ''),
    new MCFUriRule('thietlap/danhmucloaiphong/new', 'ThietLap', 'DanhMucLoaiPhongQuanLy', array('ajax', 'text'), array('action' => 'new'), ''),

    // Cac trang lien quan den tin tuc
    //new MCFUriRule('news'                                  , 'News', ''      , array('html', ''), null, ''),
    //new MCFUriRule('news-from-forum'                       , 'News', 'Forum'      , array('html', ''), null, ''),
    //new MCFUriRule('news/id:digit'                         , 'News', 'ViewDetail' , array('html', ''), null, ''),
    //new MCFUriRule('news/year:digit-year/month:digit-month', 'News', 'ViewArchive', array('html', ''), null, ''),

    // Cac trang lien quan den dang ky thanh vien
    //new MCFUriRule('register'                                  , 'Register', 'RegisterForm'           , array('html', '')     , null, ''),
    //new MCFUriRule('ajax/register'                             , 'Register', 'Register'               , array('ajax', 'text') , null, ''),
    //new MCFUriRule('register/active'                           , 'Register', 'ActiveForm'             , array('html', '')     , null, ''),
    //new MCFUriRule('register/active/email:string/hash:hash-40' , 'Register', 'Active'                 , array('html', '')     , null, ''),
    new MCFUriRule('register/send-active-soshivn-ver2'         , 'Register', 'SendActiveSoshivn2Form' , array('html', '')     , null, '')
);