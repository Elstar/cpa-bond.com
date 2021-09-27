<?php
//запускается каждую минуту

ini_set("display_errors", "1");
error_reporting(E_ALL);
include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/../classes/class.pdoHelper.php';

pdoHelper::getInstance()->update("UPDATE user SET count_requests_per_time=0 WHERE count_requests_per_time>0");
exit;