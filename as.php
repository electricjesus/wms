<?php 
	if($xls == 'true') 	{
		header("Content-Type:application/vnd.ms-excel");
	  //header("Content-Disposition: attachment; filename=Wisc" . md5(date("U") ." .xlsx"); 
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<style type="text/css">
	* { padding:0; margin:0; }
	
	body {	color: #666666; background-color:#FFFFFF; font-family: "Trebuchet MS", Courier, monospace; 	font-size:90%; }
	h1, h2, h3, h4, h5, h6, p, pre, blockquote, label, ul, ol, dl, 
	fieldset, address { margin:0.75em 0;}
	h1 {font-size:167%;}
	h2 {font-size:139%;}
	h3 {font-size:120%;}
	h4 {font-size:100%;}
	li, dd { margin-left:2em; }
	a {	color: #CC0000;	background-color: #FFFFFF; font-weight: normal;	text-decoration: none; }
	a:hover { color: #CC0000; background-color: #FFFFFF; font-weight: normal; text-decoration: underline; 
	}
	td { 
		font-size: 11;
	}
	/* odd table rows 1,3,5,7,... */
	table tr.odd th,
	table tr.odd {
		background-color:#FFFFFF;
		text-align: left;
	}
	
	/* even table rows 2,4,6,8,... */
	table tr.even th,
	table tr.even {
		background-color:<?PHP if($xls != 'true') { echo '#F2F2F2'; } else { '#FFFFFF'; } ?>;
		text-align: left;
	}
	
	/* out of stock rows */
	table tr.out th,
	table tr.out {
		background-color:<?PHP if($xls != 'true') { echo '#FFDDDD'; } else { '#FFFFFF'; } ?>;
		text-align: left;
	}
	
	/* marked tbale rows */
	table tr.marked th,
	table tr.marked {
		background-color:<?PHP if($xls != 'true') { echo '#00FF99'; } else { '#FFFFFF'; } ?>;
	}
	
	/* hovered table rows */
	/*table tr:hover,*/
	table tr.out:hover,
	table tr.out:hover th,
	table tr.odd:hover,
	table tr.even:hover,
	table tr.odd:hover th,
	table tr.even:hover th,
	table tr.hover th,
	table tr.hover {
		background-color:#00FF99;
	}
</style>
</head>
<body>
<?php if($xls != 'true') { echo "<center>"; } ?>

<table border=0 cellpadding=0 cellspacing=0 width=650>	
<?php if($xls == 'true') { ?>
	<tr>
		<td colspan="6" align="center">
		<h3>
		SDM Distributors Arrived Stocks Report
		</h3>
		</td>
	</tr>
	<tr>
		<td colspan="2">Distributor: <strong>Seth D. Malaki</strong></td>
		<td colspan="2">Area: <strong>Roxas City</strong></td>
		<td colspan="2" align="right">Date: <strong><?php echo date("M d Y"); ?></strong></td>
	</tr>
<?php } ?>
	<tr>
		<td align="center" width="100"><strong>ARRIVAL DATE</strong></td>
		<td align="center" width="130"><strong>CONTAINER NUMBER</strong></td>
		<td align="center" width="90"><strong>NUTRI</strong></td>
		<td align="center" width="90"><strong>Oishi</strong></td>
		<td align="center" width="90"><strong>TOTAL</strong></td>
		<td align="center" width="120"><strong>BS NO.</strong></td>
	</tr>
<?php
	require_once('Connections/dbGlobal.php'); 
	mysql_select_db($database_dbGlobal, $dbGlobal);
	
	$q = "
		SELECT 
			DRDate, 
			AccountName,
			Sum(DRAmount) AS Total
		FROM `dr` 
		WHERE 
			`AcctType`=4 AND 
			`AccountName` NOT LIKE '%INITIAL%' 
		GROUP BY DRDate, AccountName 
		ORDER BY `DRDate`
		";

	if($datemode=='range')
		$q = "
			SELECT 
				DRDate, 
				AccountName,
				Sum(DRAmount) AS Total
			FROM `dr` 
			WHERE 
				`AcctType`=4 AND 
				`AccountName` NOT LIKE '%INITIAL%' AND
				  `DRDate` >= '$fy-$fm-$fd' AND
				  `DRDate` <= '$ty-$tm-$td'
			GROUP BY DRDate, AccountName 
			ORDER BY `DRDate`
		";	
		$rs_as = mysql_query($q, $dbGlobal) or die(mysql_error());
?>

	<?php
	$ctr=0;
	$q_t = 0;
	$q_n = 0;
	$q_s = 0;
	while( $row = mysql_fetch_assoc($rs_as) )
	{
		$drdate = $row['DRDate'];
		$accname = $row['AccountName'];
		
		if($ctr%2==0) { $cl = "even"; } else { $cl = "odd"; }
		$ctr++;
		//$subq =  "SELECT COUNT(*) from `dr` WHERE DRDate = '$drdate' AND AccountName = '$accname'";
		$subq = "SELECT 
					ProdGroup, DRDate, AccountName, Sum(DRAmount) as Total
				FROM `dr` 
				WHERE DRDate = '$drdate' AND AccountName = '$accname' 
				GROUP BY AccountName, ProdGroup ORDER BY ProdGroup ASC;";
				
		$sub_rs = mysql_query($subq,$dbGlobal);
		$dispdate = date("M d Y", strtotime($drdate));
		echo "<tr class=$cl>";
		echo "<td align='center'>$dispdate</td>";
		echo "<td align='center'>$accname</td>";
		
		$col_color = array("red","blue");		
		$col_double = array("&nbsp;","&nbsp;");
		
		while($sub_row = mysql_fetch_assoc($sub_rs))
		{			
			$col_double[($sub_row['ProdGroup'] - 1)] = $sub_row['Total'];
		}

		for( $i = 1; $i >= 0; --$i )
		{
			echo "<td align='right'>";
			if($xls != 'true')	echo "<font color=$col_color[$i]>";
			if($col_double[$i] > 0)	echo number_format($col_double[$i],2,'.',','); else echo "&nbsp;";
			//echo $i;
			
			if($xls != 'true')	echo "</font>";
			echo "</td>";
			if($i == 0)
				$q_n += $col_double[$i];
			else 
				$q_s += $col_double[$i];		
		}

		echo "<td align='right'>";
		if($row['Total'] > 0)
			echo number_format($row['Total'],2,'.',',');
		else
			echo "&nbsp;";
		echo "</td>";
		echo "<td align='center'>&nbsp;</td>";
		echo "</tr>";
		$q_t += $row['Total'];
	}
	?>
	<tr>
		<td colspan="2" align="right">&nbsp;</td>
		<td align="right"><strong><?php 
			if($xls != 'true')
				echo "<font color=$col_color[1]>";
			echo number_format($q_s,2,'.',','); 
			if($xls != 'true')
				echo "</font>";
		?></strong></td>
		<td align="right"><strong><?php 
			if($xls != 'true')
				echo "<font color=$col_color[0]>";
			echo number_format($q_n,2,'.',','); 
			if($xls != 'true')
				echo "</font>";
		?></strong></td>
		<td align="right"><strong><?php 
			echo number_format($q_t,2,'.',','); 
		?></strong></td>
		<td>&nbsp;</td>
	</tr>
</table>
<?php if($xls != 'true') { echo "</center>"; } ?>
</body>
</html>
