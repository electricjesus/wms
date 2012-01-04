<?php 
	if($xls == 'true') 	{
		header("Content-Type:application/vnd.ms-excel");
	  //header("Content-Disposition: attachment; filename=Wisc" . md5(date("U") ." .xlsx"); 
	}

	require_once('Connections/dbGlobal.php'); 
	mysql_select_db($database_dbGlobal, $dbGlobal);
	
	if($id)
	{
		$q = "SELECT *, (SELECT `title` FROM `principals` WHERE `id`=`dr`.`ProdGroup`) as `title` FROM `dr` WHERE `id`={$id} LIMIT 1";
		$rs = mysql_query($q) or die(mysql_error() . " [ " . $q . " ]");
		$r = mysql_fetch_assoc($rs);
			$reportName = $r['AccountName'];
			$reportDate = $r['DRDate'];
			$reportAddress = $r['Address'];
			$reportNetAmount = $r['DRNetAmount'];
			$reportType = $r['AcctType'];
			$reportProdLine = $r['title'];
		switch($reportType) {
		case 8:
			$reportTitle = "BO Summary Report";
			break;
		case 9:
			$reportTitle = "BO Inventory";
		break;
		case 10:
			$reportTitle = "Panel Inventory";
		break;
		default:
			die("Invalid report type!");
		break;
		
		}	
	}
	else
		die();
?>
<HTML>
<HEAD>
<TITLE>Print DR</TITLE>
<style type="text/css">
	* { padding:0; margin:0; }
	
	body {	color: #000000; background-color:#FFFFFF; font-family: Courier New, monospace; 	font-size:83%; }
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
		font-face:Courier New;font-size: 14;
	}
	tr.topborder {
		border-style: solid;
		border-color: #000000 #ffffff #ffffff #ffffff;
	}
	tr.botborder {
		border-style: solid;
		border-color: #ffffff #ffffff #000000 #ffffff;
	}
	.fwdbalcolor {
		color: #FF0000;
	}
	.stkbalcolor {
		color: #FF0000;
	}
	.drentcolor {
		color: #0000FF;
	}
	.arstkscolor {
		color: #000000;
	}
	.offtkecolor {
		color: #000000;
	}
	.netbalcolor {
		color: #FF0000;
	}
</style>
</HEAD>
<BODY>
<table width="700" border="0">	
	<tr height="20">
		<td colspan="3">
			<strong>SDM Distributors</strong><br/>
			<sub>Roxas City, Capiz</sub>
		</td>
		<td align="left" colspan="3">
			<?php echo $reportTitle; ?><br />
			<?php echo $reportProdLine ?> Products
		</td>
		<td align="right" colspan="2">
			<?php echo date("M d, Y"); ?>
		</td>
	</tr>	
	<tr class="botborder">
		<td colspan="2" align="center"><strong>Product Name</strong></td>
		<td align="center" width=100><strong>Size</strong></td>
		<td align="center">Quantity</td>
		<?php if($reportType > 8) {	?>
		<td align="center" colspan="1">Panel Price</td>
		<td align="center" colspan="1">Amount</td>
		<?php }	?>
		<td align="center" colspan="<?php if($reportType > 8) { echo "1"; } else { echo "2"; } ?>">Net Price</td>
		<td align="center" colspan="<?php if($reportType > 8) { echo "1"; } else { echo "2"; } ?>">Net Amount</td>		
	</tr>	
	<?php
		if($id)
		{
			$q = sprintf("SELECT 
							PRODUCTS.Name AS ProductName, 
							PRODUCTVARS.VariantName AS VariantName, 
							VARSIZES.VarSize AS VarSize,
							DRDetails.Unit,
							CONCAT(PRODUCTS.Name,PRODUCTVARS.VariantName,VARSIZES.VarSize,DRDetails.Unit) AS UnifiedName,
							AVG(DRDetails.UnitPrice) AS UnitPrice,
							SUM(DRDetails.Amount) AS Amount,
							AVG(DRDetails.NetPrice) AS NetPrice,
							SUM(DRDetails.NetAmount) AS NetAmount,
							SUM(DRDetails.Quantity) AS Quantity
						FROM 
							VARSIZES 
								RIGHT JOIN (		
							PRODUCTVARS 
									RIGHT JOIN (
							PRODUCTS 
										RIGHT JOIN 
											DRDetails ON PRODUCTS.ID = DRDetails.PID
									) ON PRODUCTVARS.ID = DRDetails.PVID
								) ON VARSIZES.ID = DRDetails.VS
						WHERE DRDetails.DRID=%d
						GROUP BY UnifiedName
						ORDER BY ProductName ASC, VariantName ASC, VarSize ASC, Unit ASC;
						", $id);		
			$rs = mysql_query($q) or die(mysql_error() . " [ " . $q . " ]");
			$qtyTotalBdl = 0;
			$qtyTotalUnit = 0;
			$PriceT = 0;
			$AmtT = 0;
			$netPriceT = 0;
			$netAmtT = 0;
			$numItems = 0;
			while($r = mysql_fetch_assoc($rs))
			{
		
	?>
	<tr>
		<td align="left" colspan="2"><?php echo $r['ProductName'] . " " . $r['VariantName']; ?></td>
		<td align="center"><?php echo $r['VarSize'] ?></td>
		<td align="right"><?php echo $r['Quantity'] . " " . $r['Unit'] ?></td>
		<?php if($reportType > 8) {	?>
		<td align="right" colspan="1">="<?php echo number_format($r['UnitPrice'],2,'.',','); ?>"</td>
		<td align="right" colspan="1">="<?php echo number_format($r['Amount'],2,'.',','); ?>"</td>
		<?php }	?>
		<td align="right" colspan="<?php if($reportType > 8) { echo "1"; } else { echo "2"; } ?>">="<?php echo number_format($r['NetPrice'],2,'.',','); ?>"</td>
		<td align="right" colspan="<?php if($reportType > 8) { echo "1"; } else { echo "2"; } ?>">="<?php echo number_format($r['NetAmount'],2,'.',','); ?>"</td>
	</tr>	
	<?php
			if($r['Unit'] == "bdl.") {
				$qtyTotalBdl += $r['Quantity'];
			} else {
				$qtyTotalUnit += $r['Quantity'];
			}
			$AmtT += $r['Amount'];			
			$netAmtT += $r['NetAmount'];
			$numItems++;
			}
		}		
	?>
	<tr>
		<td align="left" colspan="2" valign="top"><strong>TOTALS:</strong></td>
		<td align="left" valign="top">&nbsp;</td>
		<td align="right" valign="top"><?php echo "<strong>" . $qtyTotalBdl . "</strong> bdls<br /><strong>" . $qtyTotalUnit . "</strong> pcs." ?></td>
		<?php if($reportType > 8) {	?>
		<td align="right" colspan="1" valign="top">&nbsp;</td>
		<td align="right" colspan="1" valign="top">="<?php echo number_format($AmtT,2,'.',','); ?>"</td>
		<?php }	?>
		<td align="right" colspan="<?php if($reportType > 8) { echo "1"; } else { echo "2"; } ?>" valign="top">&nbsp;</td>
		<td align="right" colspan="<?php if($reportType > 8) { echo "1"; } else { echo "2"; } ?>" valign="top">="<?php echo number_format($netAmtT,2,'.',','); ?>"</td>
	</tr>
	<tr>
		<td align="left" colspan="4"><strong>Prepared by:</strong> <?php echo $reportName; ?></td>
		<td align="right" colspan="4"><strong>Area/Address:</strong> <?php echo $reportAddress; ?></td>			
	</tr>		
</TABLE>
</BODY>
</HTML>