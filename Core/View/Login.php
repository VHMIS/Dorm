<!DOCTYPE html>
<html>
<head>
    <title>Ký Túc Xá - ViethanAll</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="/kytucxa/static/css/css.css" media="screen" rel="stylesheet" type="text/css" />
</head>
<body>
    <!-- MODAL -->
    <div class="modal_form login">
        <div class="header">
            <h2>Đăng Nhập Chương Trình Quản Lý Ký Túc Xá</h2>
            <p>Nhập các thông tin sau</p>
        </div>
<?php
if(isset($htmlWarning))
echo '
       <div class="error">
            ' . $htmlWarning . '
        </div>';
?>
        <form action="" method="post">
            <input name="login" value="ok" type="hidden" />
            <div class="txt-fld">
                <label for="user">Tên đăng nhập</label>
                <input id="user" name="user" type="text" />
            </div>
            <div class="txt-fld">
                <label for="pass">Mật khẩu</label>
                <input id="pass" name="pass" type="password" />
            </div>
            <div class="btn-fld">
                <button type="submit">Đăng Nhập &raquo;</button>
            </div>
        </form>
    </div>
</body>
</html>