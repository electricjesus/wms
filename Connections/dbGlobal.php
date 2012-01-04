<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_dbGlobal = "localhost";
$database_dbGlobal = "nutri";
$username_dbGlobal = "root";
$password_dbGlobal = "sethm123";
$dbGlobal = mysql_pconnect($hostname_dbGlobal, $username_dbGlobal, $password_dbGlobal) or trigger_error(mysql_error(),E_USER_ERROR); 
?>