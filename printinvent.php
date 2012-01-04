<?php 
	if($xls == 'true') 	{
		header("Content-Type:application/vnd.ms-excel");
	  	//header("Content-Disposition: attachment; inventory.xlsx"); 
	}
?>
<?php 

if(!isset($id)) die("Error while processing directive."); 

require_once('Connections/dbGlobal.php'); 
mysql_select_db($database_dbGlobal, $dbGlobal);

$query_invselect = sprintf( "SELECT * FROM `inv` WHERE `ID`=%d LIMIT 1", $id);

$query_s_invdselect = sprintf("SELECT 
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
								WHERE InvDetails.DRID=%d AND PRODUCTS.ProdGroup=1
								ORDER BY InvDetails.ID DESC;
								", $id);
								

/*	
echo $query_invselect . "<br>";
echo $query_n_invdselect . "<br>";
echo $query_s_invdselect . "<br>";
/**/
$rsinvheader = mysql_query($query_invselect, $dbGlobal) or die(mysql_error() . $query_invselect);
$rowheader = mysql_fetch_assoc($rsinvheader);
//$rssinvdetail = mysql_query($query_n_invdselect, $dbGlobal) or die(mysql_error() . $query_s_invdselect);

/**/
//$rowdetail = mysql_fetch_assoc($rsinvdetail);

?>



<html>
<head>
<style type="text/css">
	* {
	padding:0;
	margin:0;
}

body {
	color: #222222;
	background-color:#FFFFFF;
	font-family: "Trebuchet MS", Verdana, Helvetica, Arial, sans-serif;
	font-size:83%;
	margin:20px auto;
	width:760px;
	padding-left:10px;
	padding-right:10px;
}

h1, h2, h3, h4, h5, h6, p, pre, blockquote, label, ul, ol, dl, 
fieldset, address { margin:0.75em 0;}

h1 {font-size:167%;}

h2 {font-size:139%;}

h3 {font-size:120%;}

h4 {font-size:100%;}

li, dd { 
	margin-left:2em;
}

a {
	color: #CC0000;
	font-weight: normal;
	text-decoration: none;
}

a:hover {
	color: #CC0000;
	font-weight: normal;
	text-decoration: underline;
}

#contact {
	padding:0px 0px 0px 0px;
	float:right;
}

#contact a {
	color: #CC0000;
	background-color:#FFFFFF;
	font-weight:bold;
	text-decoration:none;
}

#contact a:hover {
	color: #CC0000;
	background-color:#FFFFFF;
	text-decoration:underline;
}

#header {
	clear:both;
	color: #CC0000;
	background-color:#FFFFFF;
	padding:5px 0px 10px 0px;
	margin:0px 0px 0px 0px;
}


#title {
	color: #CC0000;
	background-color:#FFFFFF;
	font-size:200%;
	font-weight:bold;
	padding:0px 0px 0px 0px;
	margin:0px 0px 0px 0px;
	float:left;
}

#slogan {
	color:#666666;
	background-color:#FFFFFF;
	font-size:83%;
	font-weight:normal;
	font-style:normal;
	padding:0px 20px 0px 0px;
	margin:0px 0px 0px 0px;
	float:left;
	width:35%;
}

ul#nav {
	line-height:125%;
	height:3em;
	float:right;
	margin: 0px 0px 0px 0px;
	padding: 10px 0px 0px 0px;
	list-style: none;
	text-align: center;
	width:60%;
}

ul#nav li {
	margin: 0px 0px 0px 0px;
	padding: 0px 0px 0px 20px;
	float: right;
}

ul#nav li a {
	display: block;
	font-size: small;
	color: #CC0000;
	background-color:#FFFFFF;
	font-weight: normal;
	text-decoration: none;
}

ul#nav li a:hover { 
	border-bottom:3px solid #CC0000;
}

ul#nav a.selected { 
	border-bottom:3px solid #CC0000;
}

#path {
	width:760px;
	clear:both;
	float:left;
	font-size:75%;
	font-weight:normal;
	margin:4px 0px 5px 0px;
	border-top:5px solid #666666;
}

#path a {
	font-weight:normal;
}

#maincontent {
	font-size:100%;
	padding:0px 5px 5px 0px;
	margin:0px 0px 0px 0px;
	width:540px;
	float:left;
}

#sidecontent {
	font-family:Arial, sans-serif;
	color: #666666;
	background-color:#FFFFFF;
	font-size:75%;
	/*padding:10px 10px 20px 10px;*/
	padding:10px 10px 10px 10px;
	margin:0px 0px 10px 0px;
	width:180px;
	float:right;
	border: #999999 solid thin;
}

#sidecontent a {
	color: #CC0000;
	background-color:#FFFFFF;
}

#sidecontent h2 { margin:0.75em 0.25em 0.25em 0em;}

#sidecontent ul { margin:0.25em 0.25em 0.25em 0.25em;}

#footer {
	height:40px;
	color:#666666;
	background-color:#FFFFFF;
	border-top:5px solid #666666;
	font-size:75%;
	line-height:1.5em;
	width: 760px;
	clear:both;
}

#footer	a {
	color:#CC0000;
	background-color:#FFFFFF;
	text-decoration: none;
}

#footer	a:hover {
	color:#CC0000;
	background-color:#FFFFFF;
	font-weight: normal;
	text-decoration: underline;
}

#copyrightdesign {
	color:#666666;
	background-color:#FFFFFF;
	padding:5px 20px 5px 0px;
	width: 580px;
	float:left;
}

#footercontact {
	color:#CC0000;
	background-color:#FFFFFF;
	padding:5px 0px 5px 0px;
	float:right;
}
#addprod {

	font-family: "Trebuchet MS", Verdana, Helvetica, Arial, sans-serif;
	color: #666666;
	background-color:#FFFFFF;
	margin:0px 0px 10px 0px;
	width:180px;
	float:right;
	visibility:visible;
}
#apbutton {
	color: #666666;
	background-color:#FFFFFF;
	font-family: "Trebuchet MS", Verdana, Helvetica, Arial, sans-serif;
	font-size:83%;
}
</style>
</head>
<body>
<table width="748" border="0" cellpadding="0" cellspacing="0">
  <!--DWLayoutTable-->
  <tr>
    <td height="95" colspan="13" valign="top"><h2>SDM Distributors</h2>
    <h4>Weekly Panel Inventory Reports : <strong>$inventory_type </strong></h4></td>
  </tr>
  <tr>
    <td width="79" height="22" valign="top">Salesman:</td>
    <td colspan="3" valign="top">$salesman</td>
    <td colspan="2" valign="top">Date:</td>
    <td colspan="3" valign="top">$date</td>
    <td colspan="2" valign="top">Area:</td>
    <td colspan="2" valign="top">Roxas/Kalibo</td>
  </tr>
  <tr>
    <td height="22" colspan="13" valign="top"><strong>Oishi 2 PRODUCTS</strong> </td>
  </tr>
  <tr>
    <td height="31" colspan="2" valign="bottom" style="border-bottom:1px black solid">Product/SKU</td>
    <td width="50" align="center" valign="bottom" style="border-bottom:1px black solid">Qty</td>
    <td colspan="2" align="center" valign="bottom" style="border-bottom:1px black solid">Unit</td>
    <td colspan="2" align="center" valign="bottom" style="border-bottom:1px black solid">Price</td>
    <td width="71" align="center" valign="bottom" style="border-bottom:1px black solid">Landed </td>
    <td colspan="2" align="center" valign="bottom" style="border-bottom:1px black solid">Total Amt </td>
    <td colspan="2" align="center" valign="bottom" style="border-bottom:1px black solid">Landed Amt </td>
    <td width="85" align="center" valign="bottom" style="border-bottom:1px black solid">Prod. Date </td>
  </tr>
<?php 
	$query_n_invdselect = sprintf("
				SELECT 
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
				WHERE InvDetails.DRID=%d AND PRODUCTS.ProdGroup=2
				ORDER BY InvDetails.ID DESC;
							", $id);
							
	//$rsDRD_disp = mysql_query($query_drdselect, $dbGlobal);
	$rsnsinvdetail = mysql_query($query_n_invdselect, $dbGlobal) or die(mysql_error() . $query_n_invdselect);
	$tr_invdetail = mysql_num_rows($rsnsinvdetail);
	
	$tqty = 0;
	$tgrosssales = 0;
	$tnet = 0;
	while($rowdrdisp = mysql_fetch_assoc($rsnsinvdetail))  
	{
		/*$p_id = $rowdrdisp['ID'];
		
		$productname = $rowdrdisp['ProductName'];
		$productvariant = $rowdrdisp['VariantName'];
		$varsize = $rowdrdisp['VarSize'];
		$sku = $productname . " " . $productvariant . " " . $varsize;
		$quantity = $rowdrdisp['Quantity'];
		$unit = $rowdrdisp['Unit'];
		$unitprice = number_format($rowdrdisp['UnitPrice'],2,'.',',');
		$netprice = number_format($rowdrdisp['NetPrice'],2,'.',',');
		$amount = $rowdrdisp['Amount'];
		$s_amount = number_format($rowdrdisp['Amount'],2,'.',',');
		$netamount = $rowdrdisp['NetAmount'];
		$s_netamount = number_format($rowdrdisp['NetAmount'],2,'.',',');
		$tqty = $tqty + $quantity;
		$tgrosssales = $tgrosssales + $amount;
		$tnet = $tnet + $netamount;*/
?>
  <tr>
    <td height="22" colspan="2" valign="top"><?php echo $sku; ?></td>
    <td valign="top"><?php echo $quantity; ?></td>
    <td colspan="2" valign="top"><?php echo $unit; ?></td>
    <td colspan="2" valign="top"><?php echo $unitprice; ?></td>
    <td valign="top"><?php echo $netprice; ?></td>
    <td colspan="2" valign="top"><?php echo $s_amount; ?></td>
    <td colspan="2" valign="top"><?php echo $s_netamount; ?></td>
    <td valign="top"><?php echo $prdate; ?></td>
  </tr>
<?php
	//}
?>
  <tr>
    <td height="22">&nbsp;</td>
    <td width="154" valign="top">SUB TOTAL -------- </td>
    <td valign="top">$ntqty</td>
    <td colspan="2" valign="top">$ntunit</td>
    <td width="42">&nbsp;</td>
    <td width="28">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2" valign="top">$ngtprice</td>
    <td colspan="2" valign="top">$ngtnet</td>
    <td></td>
  </tr>
  <tr>
    <td height="22" colspan="13" valign="top"><strong>Oishi PRODUCTS</strong> </td>
  </tr>
  <tr>
    <td height="31" colspan="2" valign="bottom" style="border-bottom:1px black solid">Product/SKU</td>
    <td width="50" align="center" valign="bottom" style="border-bottom:1px black solid">Qty</td>
    <td colspan="2" align="center" valign="bottom" style="border-bottom:1px black solid">Unit</td>
    <td colspan="2" align="center" valign="bottom" style="border-bottom:1px black solid">Price</td>
    <td width="71" align="center" valign="bottom" style="border-bottom:1px black solid">Landed </td>
    <td colspan="2" align="center" valign="bottom" style="border-bottom:1px black solid">Total Amt </td>
    <td colspan="2" align="center" valign="bottom" style="border-bottom:1px black solid">Landed Amt </td>
    <td width="85" align="center" valign="bottom" style="border-bottom:1px black solid">Prod. Date </td>
  </tr>
  <tr>
    <td height="22" colspan="2" valign="top">$sku</td>
    <td valign="top">$sqty</td>
    <td colspan="2" valign="top">$sunit</td>
    <td colspan="2" valign="top">$price</td>
    <td valign="top">$net</td>
    <td colspan="2" valign="top">$stprice</td>
    <td colspan="2" valign="top">$stnet</td>
    <td valign="top">$prdate</td>
  </tr>
  <tr>
    <td height="22">&nbsp;</td>
    <td valign="top">SUB TOTAL -------- </td>
    <td valign="top">$stqty</td>
    <td colspan="2" valign="top">$stunit</td>
    <td width="42">&nbsp;</td>
    <td width="28">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2" valign="top">$sgtprice</td>
    <td colspan="2" valign="top">$sgtnet</td>
    <td></td>
  </tr>  
  <tr>
    <td height="22">&nbsp;</td>
    <td valign="top"><strong>GRAND TOTAL ---- </strong></td>
    <td valign="top">$tqty</td>
    <td colspan="2" valign="top">$tunit</td>
    <td width="42">&nbsp;</td>
    <td width="28">&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2" valign="top">$gtprice</td>
    <td colspan="2" valign="top">$gtnet</td>
    <td></td>
  </tr> 
</table>
</body>
</html>
