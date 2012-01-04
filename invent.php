<?php 
require_once('Connections/dbGlobal.php'); 
mysql_select_db($database_dbGlobal, $dbGlobal);

	if($cancel == 'true')
	{
		$q_deldr = sprintf("DELETE FROM `inv` WHERE ID=%d;", $id);
		$q_deldr2 = sprintf("DELETE FROM `invdetails` WHERE `DRID`=%d;", $id);
		
		$res = mysql_query( $q_deldr, $dbGlobal ) or die(mysql_error());
		$res2 = mysql_query( $q_deldr2, $dbGlobal ) or die(mysql_error());;
		
		header("Location: invent.php?");
	}
		
	if(isset($_GET['delitem']))
	{
		$q =  "DELETE FROM `invdetails` WHERE ID=$delitem";
		$rs = mysql_query($q,$dbGlobal) or die(mysql_error());
		header("Location: invent.php?id=$id");
	}

	if(!isset($id) && isset($createticket))
	{
		if( 
			!( 
				($time = strtotime($cdate)) == -1 || $time === false
			) &&
			$customername != '' && 
			$reptype != '' &&
			$confirm == 'true'
		) // long if, address is left out because it is not really required
		{
			$querydrins = 
			sprintf(
			 "INSERT INTO `inv` 
			( 	`ID` ,
				`AccountName` ,
				`RepType` , 
				`DRNumber` , `DRDate` , `DRAmount` , 
				`md5ticket` 
			)
			VALUES (
				NULL ,
				'%s', 
				'%s', 
				'%s', '%s', '0', 
				'%s'
			);",
				$customername,
				$reptype,
				0, date("Y-n-j", $time), 
				$createticket
			);
			//echo $querydrins; 
			
			$result = mysql_query($querydrins, $dbGlobal) or die(mysql_error());
			$querydrfetch = sprintf("SELECT * FROM `inv` WHERE `md5ticket`='%s'", $createticket );
			$result = mysql_query($querydrfetch, $dbGlobal) or die(mysql_error());
			$rowDR = mysql_fetch_assoc($result);
			$id = $rowDR['ID'];
			header("Location: invent.php?id=$id");
			
		}
	}
	elseif (isset($id))
	{
		$qid_base = sprintf("SELECT * FROM `inv` WHERE `id`=%d LIMIT 1", $id);
		$result = mysql_query($qid_base, $dbGlobal) or die(mysql_error());
		$row = mysql_fetch_assoc($result);
		$customername = $row['AccountName'];
		$reptype = $row['reptype'];
		$reptypestr_a = array('Panel Inventory','B.O. Inventory');
		$reptypestr = $reptypestr_a[$reptype - 1];		
		$cdate = $row['DRDate'];
		
	}
?>
<HTML>
<HEAD>
<TITLE>Stocks In / Stocks Out</TITLE>
<LINK href='styles/style.css' rel='stylesheet' type='text/css'>
<LINK REL="stylesheet" HREF="css/autosuggest_inquisitor.css" TYPE="text/css" MEDIA="screen" CHARSET="utf-8" />

<SCRIPT TYPE="text/javascript" SRC="js/bsn.AutoSuggest_c_2.0.js"></SCRIPT>
<LINK HREF="css/tabledisp.css" REL="stylesheet" TYPE="text/css">
<style type="text/css">
<!--
.style3 {color: #FFFFFF}
-->
</style>
</HEAD>
<BODY>
<?php
/* all products in this form
		if( $prodcat == 1)
			$prodcat_title = "Oishi";
		elseif ($prodcat == 2)
			$prodcat_title = "Oishi 2";
		else
			die("<center>Enter necessary information above to continue</center>");
*/
?>
<H1>Create New Inventory Form <?php /* ( <?php echo $prodcat_title ?> Products ) </H1> */ ?>
<?php

	if (!isset($id)) { //if 1 
?>
<FORM action='invent.php?' method='post'>
	<TABLE width='700' cellspacing='7' bgcolor='#CCCCCC'>
		<!--DWLayoutTable-->
		<TR>
			<TD height="19" colspan='2'>
				<?php if($errno == "false") { ?>
				<FONT size='2' color='#FF0000'>Please accomplish all fields to continue.</FONT>
				<?php } else echo "&nbsp;"; ?>			</TD>
		</TR>
		<TR>
		  <TD width='376' height='146' align='left' valign='top'>Salesman :
            <INPUT 
					name='customername' 
					type='text' 
					id='customername' 
					size='32' 
					value="<?php echo $customername ?>"
					tabindex="1" >
            <SCRIPT TYPE="text/javascript">
	                        </SCRIPT>
            <br>
            <P>Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:<?php if(isset($dr_m,$dr_d,$dr_y)) { $cdate = $dr_m . "/" . $dr_d . "/" . $dr_y; } ?>
              <INPUT type='text' name='cdate' value='<?php echo $cdate ?>' TABINDEX="2" >
              <br>
              <br>
              <br>
              Report type:
              <select id='reptype' name='reptype' style='width:240' tabindex="7" >
                <option value="1">Panel Inventory</option>
                <option value="2">B.O. Report</option>
                            </select>
              
              <SCRIPT TYPE="text/javascript">document.getElementById('customername').focus();
</SCRIPT>
                                    </P></TD>
			<TD width='293' align='center' valign='top'><P><FONT size='2'><br>
		      <br>
		      <INPUT NAME="confirm" type='checkbox' VALUE="true" TABINDEX="6" >
&nbsp;Confirm creation<br>
<br>
<input name="submit" type='submit' tabindex="8" value='Create Inventory Report' >
<BR>
            </FONT></P></TD>
		</TR>
	</TABLE>
	<INPUT name='createticket' type='hidden' value='<?php echo bin2hex( md5( date('U'), true) ); ?>'>
	<INPUT type='hidden' name='prodcat' value='<?php echo $prodcat ?>'>
	<INPUT type='hidden' name='errno' value='false'>
</FORM>
<P>OR.. View/Edit an Existing Inventory Form</P>
<table width="700">
<tr height="27">
	<td width="10%">
		ID
	</td>
	<td width="40%">
		Salesman Name
	</td>
	<td width="20%">
		Inventory Type
	</td>
	<td width="20%">
		Date
	</td>
</tr>
<?php
	$rsInvents = mysql_query("SELECT * FROM inv ORDER BY DRDate DESC") or die(mysql_error());
	if(mysql_num_rows($rsInvents) == 0)
	{
?>
	<td colspan="4" align="center"><em>No records yet.</em></td>
<?php 
	}
	else
	{
		while($row = mysql_fetch_assoc($rsInvents)) {

		$inv_id = $row['ID'];
		$inv_salesman = $row['AccountName'];
		$inv_reptype = $row['reptype'];
		$inv_reptypestr = $reptypestr_a[$inv_reptype - 1];
		$inv_date = $row['DRDate'];
?>
<tr height="27">
	<td width="10%">
		<?php echo $inv_id; ?>
	</td>
	<td width="40%">
		<?php echo $inv_salesman; ?>
	</td>
	<td width="20%">
		<?php echo $inv_reptypestr; ?>
	</td>
	<td width="20%">
		<?php echo $inv_date; ?>
	</td>
</tr>
<?php
		}
	}
?>
</table>
<?php 
	} 
	else { 
?>
<FORM ACTION="invent.php?&id=<?php echo $id ?>" METHOD="post">
<TABLE width='700' border='0' cellpadding='0' cellspacing='0' bordercolor='#CCCCCC'>
  <!--DWLayoutTable-->
  	<TR>
		<TD HEIGHT="114" COLSPAN="3" align='left' VALIGN="top">
		<P>Transaction: <B><?php echo 
										$customername . " ( <i>" . 
										$reptypestr . "</i> ) "; 
							?></B></P>
		<P>Options:<BR>
		  <A HREF="invent.php?id=<?php echo $id ?>&cancel=true">• Cancel Report  (!) </A>		  <span class="style3">-----------------------</span> <A HREF="printinvent.php?id=<?php echo $id ?>&print=true">• Print Report</A>
		  <br>
		  </P></TD>
	<TD COLSPAN="4" VALIGN="top"></STRONG></P>
	  <P>Date: <STRONG><?php echo date("F j, Y",strtotime($cdate)); ?></STRONG> </P>
	  <P>Terms: <STRONG><?php echo $terms; ?></STRONG></P></TD>
    </TR>
	<TR height='24'>
		<TD COLSPAN="2" align='center' VALIGN="bottom" BGCOLOR="#CCCCCC">ARTICLES</TD>
		<TD COLSPAN="2" align='center' VALIGN="bottom" BGCOLOR="#CCCCCC">QUANTITY</TD>
		<TD width='62' align='center' VALIGN="bottom" BGCOLOR="#CCCCCC">UNIT</TD>
		<TD COLSPAN="2" align='right' VALIGN="middle" BGCOLOR="#CCCCCC">
			<A HREF="invent.php?id=<?php echo $id ?>">
			<IMG SRC="images/refresh_v.JPG" WIDTH="16" HEIGHT="16" BORDER="0"></A></TD>
	</TR>
	<TR height='24' CLASS="even">
		<TD HEIGHT="24" COLSPAN="2" align='left' VALIGN="top">
		  <INPUT ID="selectArticle" name='selectArticle' size='52' TABINDEX="1" > 
		  <INPUT STYLE="width: 0px; visibility:hidden" TYPE="text" ID="infoset" NAME="infoset" VALUE="" /> 
		  <INPUT STYLE="width: 0px; visibility:hidden" TYPE="text" ID="idset" NAME="idset" VALUE="" /> 
		  	<SCRIPT TYPE="text/javascript">
				var options = {
					script:"inv_product_suggest.php?json=true&prodcat=<?php echo $prodcat ?>&reptype=<?php echo $reptype ?>&",
					varname:"input",
					json:true,
					callback: function (obj) { 
						document.getElementById('idset').value = obj.id;
						document.getElementById('infoset').value = obj.info; 
					}
				};
				var as_json = new AutoSuggest('selectArticle', options);
				
			/*	
				var options_xml = {
					script:"product_suggest.php?",
					varname:"input"
				};
				var as_xml = new AutoSuggest('testinput_xml', options_xml);
			*/
			document.getElementById('selectArticle').focus();
			</SCRIPT>
			
		</TD>	
		<TD COLSPAN="2" align='center' VALIGN="top">
		<INPUT name='qty' type='text' ID="qty" VALUE="0" SIZE="16" TABINDEX="2"></TD>
		<TD align='right' VALIGN="top"><SELECT select style='width:100%' name='selectUnit'>
			<OPTION>bdl.</OPTION>
			<OPTION>pcs.</OPTION>
        </SELECT>		  </TD>
		<TD COLSPAN="2" align='right' VALIGN="top">
		<INPUT type='submit' name='Submit' value='+'>
		</TD>
	</TR>
	<TR height='24' CLASS="even">
		<TD HEIGHT="44" VALIGN="top"><!--DWLayoutEmptyCell-->&nbsp;</TD>
	    <TD colspan='4' align='right' VALIGN="top"><!--DWLayoutEmptyCell-->&nbsp;</TD>
		<TD align='center' VALIGN="bottom"><FONT size='2'>Unit Price</FONT></TD>
      	<TD align='center' VALIGN="bottom"><FONT size='2'>Amount</FONT></TD>
	</TR>
	<?php 
	
	if($drdetail == 'true' && $qty != '0')
	{	//add
		$drid = $id;
		
		list($pid, 	$pvid, 	$vsid, $bq) = split("/", $idset);

		list($price,$landed) = split(",", $infoset );
		$unitprice = substr(trim($price), strlen("Price: P ")); 
		$netprice = substr(trim($landed), strlen("Landed: P ")); 

		//$qty already set via POST
		$unit = $selectUnit;
		if($unit == 'bdl.')
			$amount = $qty * $unitprice;
		else
			$amount = $qty * ($unitprice / $bq);
		$netamount = $qty * $netprice;
		$query_add = sprintf(
			"INSERT INTO `invdetails`
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
									InvDetails.*
								FROM 
									VARSIZES 
										RIGHT JOIN (		
									PRODUCTVARS 
											RIGHT JOIN (
									PRODUCTS 
												RIGHT JOIN 
													InvDetails ON PRODUCTS.ID = InvDetails.PID
											) ON PRODUCTVARS.ID = InvDetails.PVID
										) ON VARSIZES.ID = InvDetails.VS
								WHERE InvDetails.DRID=%d
								ORDER BY InvDetails.ID DESC;
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
		if (($ctr % 2) == 0) { $cl = "even"; } else { $cl = "odd"; }
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
		<tr class='$cl'>	
			<td height='22' align='center' valign='middle'>
	<a href='invent.php?id=$id&delitem=$p_id'>X</a></td>
			<td align='left' valign='top'>$productname $productvariant $varsize</td>
			<td colspan='2' align='right' valign='top'>$quantity</td>
			<td align='right' valign='top'><font size='2'>$unit</font></td>
			<td align='center' valign='top'><font size='2'>$unitprice</font></td>
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
	<TR height='24'>
		<TD HEIGHT="22" COLSPAN="7" align='center' VALIGN="middle">
		  <EM><?php echo $tqty ?> Total Items in <?php echo $tr_rsDRD ?> Total SKU's</EM>
		</TD>
	</TR>
	<TR height='24'>
		<TD height='24' colspan='7' align='right' valign='top'>
		<STRONG>TOTAL AMOUNT</STRONG> PhP <STRONG><?php echo $tgrosssales ?></STRONG> </TD>
	</TR>
</TABLE>
<INPUT TYPE="hidden" NAME="id" VALUE="<?php echo $id ?>">
<INPUT TYPE="hidden" NAME="prodcat" VALUE="<?php echo $prodcat ?>">
<INPUT TYPE="hidden" NAME="drdetail" VALUE="true">
</FORM>
<?php 
	} 
?>

</BODY>
</HTML>