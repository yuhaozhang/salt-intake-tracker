<?php

define('DB_HOST','localhost');
define('DB_USER','root');
define('DB_PASSWORD','123456');
define('DB_SCHEMA','changzhi_salt');
define('DB_TBL_PREFIX','');

date_default_timezone_set("Etc/GMT-8");

if (!$GLOBALS['DB'] = mysql_connect(DB_HOST,DB_USER,DB_PASSWORD)){
    die ('Error: Unable to connect to database server.');
}

if (!mysql_select_db(DB_SCHEMA,$GLOBALS['DB'])){
    mysql_close($GLOBALS['DB']);
    die ('Error: Unable to select database schema.');
}

?>