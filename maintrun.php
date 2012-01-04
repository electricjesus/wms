<?php require_once('Connections/dbGlobal.php'); ?>
<?
$q1 = "DELETE FROM `varsizes` WHERE `PVarID`=0;";
$q2 = "DELETE FROM `varsizes` WHERE `PVarID`=0;";
$r1st = microtime();
$r1 = mysql_query($q1) or die(mysql_error());
$r1en = microtime();
printf("Deleting null varsizes: Finished in %d secs.<br>", $r1st-$r1en);
$r2st = microtime();
$rs2 = mysql_query($q1) or die(mysql_error());
$r2en = microtime();
printf("Deleting null products var: Finished in %d secs.<br>", $r1st-$r1en);
?>
<a href="http://localhost/wms/dataentry.php">go back</a>