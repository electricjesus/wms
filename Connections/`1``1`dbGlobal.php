<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_dbGlobal = "fdb1.awardspace.com";
$database_dbGlobal = "electricjesus_wm";
$username_dbGlobal = "electricjesus_wm";
$password_dbGlobal = "seth123";
$dbGlobal = mysql_pconnect($hostname_dbGlobal, $username_dbGlobal, $password_dbGlobal) or trigger_error(mysql_error(),E_USER_ERROR); 
?>