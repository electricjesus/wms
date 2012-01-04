<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
</head>

<body>
<form id="form1" name="form1" method="post" action="bcode_hack.php">
<h3>barcode hack helper</h3>
  <label>
  code <input type="text" name="code" / value="<?php echo $code; ?>">
  </label>
  <label>
  plaintext
  <input type="text" name="plaintext" / value="<?php echo $plaintext; ?>">
  </label>
  <label>
  <input type="submit" name="Submit" value="Submit" />
  </label>
</form>
<br />
<?php
	echo "<font face='UPCEANXL' size=5>". $plaintext . "</font>";
?>
<pre>
<?php	
	$len = strlen($plaintext);
	$len2 = strlen($code);
	if(isset($code) && ($len != ($len2+3))) // code is always 3 chars shorter, or guard and middle bars.
		die("code and plaintext does not match! $len - $len2");
		
	$arr = preg_split('//', $plaintext, -1, PREG_SPLIT_NO_EMPTY);
	$scode = preg_split('//', $code, -1, PREG_SPLIT_NO_EMPTY);
	
	$arrcode = array(	
		$scode[0],
		"G",
		$scode[1],$scode[2],$scode[3],$scode[4],$scode[5],$scode[6],
		"G",
		$scode[7],$scode[8],$scode[9],$scode[10],$scode[11],$scode[12],
		"G");
	
	echo "\n\nComponent chars:\t\t";
	for($i = 0; $i < $len; $i++)
		echo $arr[$i] . " ";
	echo "\n\nComponent ord equiv:\t\t";
	for($i = 0; $i < $len; $i++)
		printf("%3d ",ord($arr[$i]));
	echo "\n\nComponent code equiv:\t\t";
	for($i = 0; $i < $len; $i++)
		if($arrcode[$i] != "G")
			printf("%3d ",$arrcode[$i]);
		else
			printf("    ",$arrcode[$i]);
	echo "\n\n=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-\n
	\nComponent calc1 equiv:\t\t";
	for($i = 0; $i < $len; $i++)
		if($arrcode[$i] != "G")
			printf("%3d ",ord($arr[$i])/$arrcode[$i]);
		else
			printf("    ",$arrcode[$i]);
	echo "\nComponent diff equiv:\t\t";
	for($i = 0; $i < $len; $i++)
		if($arrcode[$i] != "G")
			printf("%3d ",ord($arr[$i])-$arrcode[$i]);
		else
			printf("    ",$arrcode[$i]);
	// Unicode difference bet. 
	$diffarray = array(58,64,81,65,66,66,66,67,67,67);
	$resultarr= array(	
		chr($scode[0] + 224),
		chr(91),
		chr($scode[1] + 48),
		chr($scode[2] + 48),
		chr($scode[3] + 48),
		chr($scode[4] + 48),
		chr($scode[5] + 48),
		chr($scode[6] + 48),
		chr(124),
		chr($scode[7] + $diffarray[$scode[7]]),
		chr($scode[8] + $diffarray[$scode[8]]),
		chr($scode[9] + $diffarray[$scode[9]]),
		chr($scode[10] + $diffarray[$scode[10]]),
		chr($scode[11] + $diffarray[$scode[11]]),
		chr($scode[12] + $diffarray[$scode[12]]),
		chr(93));
		$result = implode("",$resultarr);
?>
<br />
<?php
	echo "<font face='UPCEANXL' size=6>". $result . "</font><br><br>$result";
?>

</pre>
</body>
</html>
