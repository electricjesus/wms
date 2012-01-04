<html>
<head>
<title>Untitled Document</title>
</head>

<body>
<form action="arraytest.php" method="post">
<table width="492" border="0" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
<?php
	for( $i = 0; $i < 5; $i++ )
	{
?>
  <tr>
    <td width="176" height="51" valign="top">
		VAL
		<input type="hidden" name="rand_id[<?php echo $i ?>]" value=""></td>
    <td width="183" valign="top">
		<input type="text" name="pnl[<?php echo $i ?>]" value="<?php echo $pnl[$i] ?>">
	</td>
    <td width="133" valign="top">
		<input type="text" name="bkg[<?php echo $i ?>]" value="<?php echo $bkg[$i] ?>">
	</td>
  </tr>
<?php
	}
?>
  <tr>
    <td height="32" colspan="3" valign="top"><input type="submit" name="Submit" value="Submit"></td>
  </tr>
</table>
</form>
</body>
</html>
