<?php

Zend_Session::destroy(true);
header('Location: ' . URL_PATH . 'login');
exit();