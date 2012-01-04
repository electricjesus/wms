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
		background-color:#00FF99;
	}
	
	/* hovered table rows */
	/*table tr:hover,*/
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
		<td colspan="5" align="center">
		<strong>
		SDM Distributors Warehouse Inventory
		</strong>
		</td>
	</tr>
	<tr>
		<td>Distributor: Seth D. Malaki</td>
		<td colspan="2">Area: Roxas City</td>
		<td colspan="2" align="right">Inventory Date: <?php echo date("M d Y"); ?></td>
	</tr>
<?php } ?>
	<tr style="background-color:#F0F0F0">
		<td width="270" align="center"><strong>Product</strong></td>
		<td width="70" align="center"><strong>Quantity</strong></td>
		<td width="70" align="center"><strong>Price</strong></td>
		<td width="120" align="center"><strong>Total Amt</strong></td>
		<td width="120" align="center"><strong>Prod. Date</strong></td>
	</tr>
<?php
	require_once('Connections/dbGlobal.php'); 
		$total = 0;
		$tqty = 0;
		$ctr = 0;
	mysql_select_db($database_dbGlobal, $dbGlobal);
	if(!isset($prodgroup))	 { $prodgroup = 1; }
	$query_articles_join = 
			sprintf(
			"
				SELECT
				
				PRODUCTS.ID AS PID,
				PRODUCTVARS.ID AS PVID,
				VARSIZES.ID AS VSID,
				VARSIZES.*,
				
				CONCAT(
				PRODUCTS.Name, ' ',
				PRODUCTVARS.VariantName, ' ',
				VARSIZES.Varsize) AS Name,
						
				(CAST(VARSIZES.Varsize AS CHAR) | CAST('axaa' AS CHAR)) as xVS
				FROM
					VARSIZES
				LEFT JOIN (
						PRODUCTS
					RIGHT JOIN
						PRODUCTVARS
					ON
						PRODUCTS.ID = PRODUCTVARS.ProductID
				)
				ON
					VARSIZES.PVarID = PRODUCTVARS.ID
				
				WHERE PRODUCTS.ProdGroup=%d
				ORDER BY Products.Name ASC, VariantName ASC, xVS ASC
			", $prodgroup);	
	
		$rsArticles = mysql_query($query_articles_join, $dbGlobal) or die(mysql_error() .
		'<pre><code>'. $query_articles_join . '</code></pre>');

		while($rowArticles = mysql_fetch_assoc($rsArticles))
		{
			$articlename = $rowArticles['Name'];
			$articlePID = $rowArticles['PID'];
			$articlePVID = $rowArticles['PVID'];
			$articleVSID = $rowArticles['VSID'];
			$articleLC = $rowArticles['LC'];
			
			$subq_credit_qty = sprintf("
				SELECT 
					SUM(QUANTITY) 
				FROM 
					`DRDETAILS` 
				RIGHT JOIN `DR` ON `DRDETAILS`.DRID = `DR`.ID 
				WHERE 
					`DR`.ACCTTYPE=4
					 AND
					`DRDETAILS`.VS = %d", $articleVSID);
			$rs_subq_cr = mysql_query($subq_credit_qty,$dbGlobal) or die(mysql_error());
			
			$subq_debit_qty = sprintf("
				SELECT 
					SUM(QUANTITY) 
				FROM 
					`DRDETAILS` 
				RIGHT JOIN `DR` ON `DRDETAILS`.DRID = `DR`.ID 
				WHERE 
					(`DR`.ACCTTYPE!=4 AND `DR`.ACCTTYPE < 8)
					 AND
					`DRDETAILS`.VS = %d", $articleVSID);
			$rs_subq_db = mysql_query($subq_debit_qty,$dbGlobal) or die(mysql_error());
			
			$rcr = mysql_fetch_row($rs_subq_cr);
			$rdb = mysql_fetch_row($rs_subq_db);
			$qty = $rcr[0] - $rdb[0];
			$tqty += $qty;
			$tamt = $qty * $articleLC;
			$total = $total + $tamt;
			$tamt = number_format($tamt, 2, '.',',');
			if($ctr%2==0) { $cl = "even"; } else { $cl = "odd"; }
			$ctr++;
			if($xls != 'true')
			{
				if($qty <= 0)
				{
					$flag_s = "<font color=#BB3333>";
					
					$cl = "out";
					
				}
				else
				{
					$flag_s = "<font>";
				}
				$flag_e = "</font>";
			}
			echo "	<tr class=$cl>
						<td align=left>$articlename</td>
						<td align=center><strong>$flag_s $qty $flag_e</strong></td>
						<td align=center>$articleLC</td>
						<td align=right>$tamt</td>
						<td align=center>&nbsp;</td>
					</tr>";
		}
?>
					<tr bgcolor="#F0F0F0">
						<td align=left><strong>TOTALS:</strong></td>
						<td align=center><strong><?php echo $tqty ?></strong></td>
						<td align=center>&nbsp;</td>
						<td align=right><strong><?php echo number_format($total, 2, '.',',') ?></strong>
						</td>
						<td align=center>&nbsp;</td>
					</tr>
<?php if($xls != 'true') { echo "</center>"; } ?>
</center>
</body>
</html>
