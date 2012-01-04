<?php 
	if($xls == 'true') 	{
		header("Content-Type:application/vnd.ms-excel");
	  //header("Content-Disposition: attachment; filename=Wisc" . md5(date("U") ." .xlsx"); 
	}
?>
<?php 
require_once('Connections/dbGlobal.php'); 
mysql_select_db($database_dbGlobal, $dbGlobal);

	if($cancel == 'true')
	{
		$q_deldr = sprintf("DELETE FROM `dr` WHERE ID=%d;", $id);
		$q_deldr2 = sprintf("DELETE FROM `drdetails` WHERE `DRID`=%d;", $id);
		
		$res = mysql_query( $q_deldr, $dbGlobal ) or die(mysql_error());
		$res2 = mysql_query( $q_deldr2, $dbGlobal ) or die(mysql_error());;
		
		header("Location: dr.php?prodcat=$prodcat");
	}
		
	if(isset($_GET['delitem']))
	{
		$q =  "DELETE FROM `drdetails` WHERE ID=$delitem";
		$rs = mysql_query($q,$dbGlobal) or die(mysql_error());
		header("Location: dr.php?prodcat=$prodcat&id=$id");
	}

	if(!isset($id) && isset($createticket))
	{
		if( 
			!( 
				($time = strtotime($cdate)) == -1 || $time === false
			) &&
			$customername != '' && 
			$accttype != '' &&
			$prodcat != '' &&
			$drno != '' &&
			$terms != '' &&
			$confirm == 'true'
		) // long if, address is left out because it is not really required
		{
			$querydrins = 
			sprintf(
			 "INSERT INTO `dr` 
			( 	`ID` , `ProdGroup` , 
				`AccountName` , `Address` , 
				`Terms` , `AcctType` , 
				`DRNumber` , `DRDate` , `DRAmount` , 
				`md5ticket` 
			)
			VALUES (
				NULL , '%d', 
				'%s', '%s', 
				'%s', '%d', 
				'%s', '%s', '0', 
				'%s'
			);",
				$prodcat,
				$customername, $addr,
				$terms, $accttype,
				$drno, date("Y-n-j", $time), 
				$createticket
			);
			//echo $querydrins; 
			
			$result = mysql_query($querydrins, $dbGlobal) or die(mysql_error());
			$querydrfetch = sprintf("SELECT * FROM `dr` WHERE `md5ticket`='%s'", $createticket );
			$result = mysql_query($querydrfetch, $dbGlobal) or die(mysql_error());
			$rowDR = mysql_fetch_assoc($result);
			$id = $rowDR['ID'];
			header("Location: dr.php?prodcat=$prodcat&id=$id");
			
		}
	}
	elseif (isset($id))
	{
		$qid_base = sprintf("SELECT * FROM `dr` WHERE `id`=%d LIMIT 1", $id);
		$result = mysql_query($qid_base, $dbGlobal) or die(mysql_error());
		$row = mysql_fetch_assoc($result);
		$customername = $row['AccountName'];
		$accttype = $row['AcctType'];
		// 1 RP, 2 RB, 3 PB, 4 AS
		$accttypestr_a = array('Panel','City Bkg','Prov Bkg','Arvd Stocks','City Bkg + VAT','Prov Bkg + VAT','Tie-up');
		$accttypestr = $accttypestr_a[$accttype - 1];
		$drno = $row['DRNumber'];
		$addr = $row['Address'];
		$cdate = $row['DRDate'];
		$terms = $row['Terms'];
		
	}
?>
<HTML>
<HEAD>
<TITLE>Print DR</TITLE>
<style type="text/css">
	* { padding:0; margin:0; }
	
	body {	color: #000000; background-color:#FFFFFF; font-family: "Trebuchet MS", Courier, monospace; 	font-size:83%; }
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
<?php

	if (!isset($id)) { //if 1 
?>
	<TABLE width='700' cellpadding='15' cellspacing='15' bgcolor='#CCCCCC'>
		<!--DWLayoutTable-->
		<TR>
			<TD>Error. no ID specified.</TD>
		</TR>
	</TABLE>
<P>&nbsp;</P>
<?php 
	} 
	else { 
?>
<TABLE width='500' border='0' cellpadding='0' cellspacing='0' bordercolor='#CCCCCC'>
  <!--DWLayoutTable-->
  	<TR>
		<TD HEIGHT="20" COLSPAN="1" align='left' VALIGN="top">&nbsp;</TD>
		<TD HEIGHT="20" COLSPAN="4" align='left' VALIGN="top">&nbsp;</TD>
		<TD HEIGHT="20" COLSPAN="2" align='left' VALIGN="top">&nbsp;</TD>
	</TR>
	<TR>
		<TD HEIGHT="20" COLSPAN="1" align='left' VALIGN="top">&nbsp;</TD>
		<TD HEIGHT="20" COLSPAN="4" align='left' VALIGN="top">&nbsp;</TD>
		<TD HEIGHT="20" COLSPAN="2" align='left' VALIGN="top">&nbsp;</TD>
	</TR>
	<TR>
		<TD HEIGHT="20" COLSPAN="1" align='left' VALIGN="top">&nbsp;</TD>
		<TD HEIGHT="20" COLSPAN="4" align='left' VALIGN="top">&nbsp;</TD>
		<TD HEIGHT="20" COLSPAN="2" align='left' VALIGN="top">&nbsp;</TD>
	</TR>
	<TR>
		<TD HEIGHT="20" COLSPAN="1" align='left' VALIGN="top">&nbsp;</TD>
		<TD HEIGHT="20" COLSPAN="4" align='left' VALIGN="top"><?php echo $customername ?></TD>
		<TD HEIGHT="20" COLSPAN="2" align='left' VALIGN="top"><?php echo date("F j, Y",strtotime($cdate)); ?></TD>
	</TR>
	<TR>
		<TD HEIGHT="20" COLSPAN="1" align='left' VALIGN="top">&nbsp;</TD>
		<TD HEIGHT="20" COLSPAN="4" align='left' VALIGN="top">&nbsp;</TD>
		<TD HEIGHT="20" COLSPAN="2" align='left' VALIGN="top">&nbsp;</TD>
	</TR>
	<TR>
		<TD HEIGHT="20" COLSPAN="1" align='left' VALIGN="top">&nbsp;</TD>
		<TD HEIGHT="20" COLSPAN="4" align='left' VALIGN="top">#</TD>
		<TD HEIGHT="20" COLSPAN="2" align='left' VALIGN="top"><?php echo $terms; ?> days</TD>
	</TR>
	<TR>
		<TD HEIGHT="20" COLSPAN="1" align='left' VALIGN="top">&nbsp;</TD>
		<TD HEIGHT="20" COLSPAN="4" align='left' VALIGN="top">&nbsp;</TD>
		<TD HEIGHT="20" COLSPAN="2" align='left' VALIGN="top">&nbsp;</TD>
	</TR>
	<TR>
		<TD HEIGHT="20" COLSPAN="1" align='left' VALIGN="top">&nbsp;</TD>
		<TD HEIGHT="20" COLSPAN="4" align='left' VALIGN="top"><?php echo $addr; ?></TD>
		<TD HEIGHT="20" COLSPAN="2" align='left' VALIGN="top">&nbsp;&nbsp;&nbsp;&nbsp;SETH D. MALAKI</TD>
	</TR>
	<TR>
		<TD HEIGHT="20" COLSPAN="1" align='left' VALIGN="top">&nbsp;</TD>
		<TD HEIGHT="20" COLSPAN="4" align='left' VALIGN="top">&nbsp;</TD>
		<TD HEIGHT="20" COLSPAN="2" align='left' VALIGN="top">&nbsp;</TD>
	</TR>
	<TR>
		<TD HEIGHT="20" COLSPAN="1" align='left' VALIGN="top">&nbsp;</TD>
		<TD HEIGHT="20" COLSPAN="4" align='left' VALIGN="top">&nbsp;</TD>
		<TD HEIGHT="20" COLSPAN="2" align='left' VALIGN="top">&nbsp;</TD>
	</TR>
	<TR>
		<TD HEIGHT="20" COLSPAN="1" align='left' VALIGN="top">&nbsp;</TD>
		<TD HEIGHT="20" COLSPAN="4" align='left' VALIGN="top">&nbsp;</TD>
		<TD HEIGHT="20" COLSPAN="2" align='left' VALIGN="top">&nbsp;</TD>
	</TR>
	<?php 
	
	if($drdetail == 'true' && $qty != '0')
	{	//add
		$drid = $id;
		
		list($pid, 	$pvid, 	$vsid) = split("/", $idset);

		list($price,$landed) = split(",", $infoset );
		$unitprice = substr(trim($price), strlen("Price: P ")); 
		$netprice = substr(trim($landed), strlen("Landed: P ")); 

		//$qty already set via POST
		$unit = $selectUnit;
		$amount = $qty * $unitprice;
		$netamount = $qty * $netprice;
		$query_add = sprintf(
			"INSERT INTO `drdetails`
			(
				ID, DRID,
				PID, PVID, VS, 
				Quantity, Unit, UnitPrice, NetPrice,
				Amount, NetAmount
			) VALUES (
				0, '%d',
				'%d', '%d', '%d',
				'%d', '%s', '%s','%s',
				'%s','%s'
			);
			",
				$drid,
				$pid, $pvid, $vsid, 
				$qty, $unit, $unitprice, $netprice,
				$amount, $netamount
			);
		//echo $query_add . $idset;
		$res = mysql_query($query_add, $dbGlobal) or die(mysql_error() . $query_add);
		
	}
	$query_drdselect = sprintf("SELECT 
									PRODUCTS.Name AS ProductName, 
									PRODUCTVARS.VariantName AS VariantName, 
									VARSIZES.VarSize AS VarSize,
									DRDetails.*
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
								ORDER BY DRDetails.ID DESC;
								", $id);
								
	$rsDRD_disp = mysql_query($query_drdselect, $dbGlobal);
	$tr_rsDRD = mysql_num_rows($rsDRD_disp);
	
	$tqty = 0;
	$tgrosssales = 0;
	$tnet = 0;
	$ctr = 0;
	$cl = "";
	while($rowdrdisp = mysql_fetch_assoc($rsDRD_disp))  {
		$ctr++;
		$p_id = $rowdrdisp['ID'];
		$productname = $rowdrdisp['ProductName'];
		$productvariant = $rowdrdisp['VariantName'];
		$varsize = $rowdrdisp['VarSize'];
		$quantity = $rowdrdisp['Quantity'];
		$unit = $rowdrdisp['Unit'];
		$unitprice = number_format($rowdrdisp['UnitPrice'],2,'.',',');
		$amount = $rowdrdisp['Amount'];
		$s_amount = number_format($rowdrdisp['Amount'],2,'.',',');
		$netamount = $rowdrdisp['NetAmount'];
		$s_netamount = number_format($rowdrdisp['NetAmount'],2,'.',',');
		$tqty = $tqty + $quantity;
		$tgrosssales = $tgrosssales + $amount;
		$tnet = $tnet + $netamount;
		
		echo "
		<tr height='22'>
			<td align='right' valign='top' width='20'><font size='2'>$quantity</font></td>
			<td align='right' valign='top' width='20'><font size='2'>$unit</font></td>
			<td align='center' valign='top' width='30'><font size='2'>$unitprice</font></td>	
			<td colspan='2' align='left' valign='top' width='60%'><font size='2'>$productname $productvariant $varsize</font></td>			
			<td align='right' valign='top'><font size='2'>$s_amount</font></td>
		</tr>";
	};
	
	// vat rules
	$tsales = $tgrosssales / 1.12;
	$tvat = $tsales *  0.12;
	
	//---------------
		$qupdate_dr = sprintf("
						UPDATE `dr` SET
							DRAmount = '%0.2f',
							DRNetAmount = '%0.2f'
						WHERE
							ID=%d	
						",$tgrosssales, $tnet, $id);
		mysql_free_result($res);
		$res = mysql_query($qupdate_dr,$dbGlobal) or die(mysql_error());
	//----------------
	
	$tgrosssales = number_format($tgrosssales, 2, '.', ',');
	$tvat = number_format($tvat, 2, '.', ',');
	$tsales = number_format($tsales, 2, '.', ',');
	
	?>
	<TR height='22'>
		<TD HEIGHT="22" COLSPAN="6" align='center' VALIGN="middle">
		  <font size='1.5'>
		  <EM><?php echo $tqty ?> Total Items in <?php echo $tr_rsDRD ?> Total SKU's</EM>
		  </font>
		</TD>
	</TR>
	<TR height='22'>
		<TD colspan='6' align='right' valign='top'>
		<font size='2'>Net Sales </STRONG>PhP <?php echo $tsales ?></font>
		</TD>
	</TR>
	<TR height='22'>
		<TD height='22' colspan='6' align='right' valign='top'>
			<font size='2'>VAT PhP <?php echo $tvat ?></font>
		</TD>
	</TR>
	<TR height='22'>
		<TD height='22' colspan='6' align='right' valign='top'>
			<font size='2'>
			<STRONG>TOTAL SALES</STRONG> PhP <STRONG><?php echo $tgrosssales ?></STRONG>
			</font>
		</TD>
	</TR>
</TABLE>
<?php 
	} 
?>

</BODY>
</HTML>