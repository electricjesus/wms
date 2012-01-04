<?php require_once('Connections/dbGlobal.php'); ?>
<html>
<head>
<title>SDM Warehouse Management System</title>
<link href="styles/style2.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
#submenuPSKU {
	position:absolute;
	width:580px;
	height:33px;
	z-index:1;
	left: 189px;
}
-->
</style>
<script type="text/JavaScript">
<!--
function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
//-->
</script>
</head>
<body>
<div align="center"> Navigation Menu :. [ 
<a href="#" 
onClick="
	MM_goToURL('parent.frames[\'mainFrame\']','documents.htm');
	MM_goToURL('parent.frames[\'topFrame\']','indexmenu.php?menuitem=-1');
	return document.MM_returnValue
">
<?php if($menuitem==-1) { echo "<b>"; }; ?>
Documents
<?php if($menuitem==-1) { echo "</b>"; }; ?>
</a> | 
<a href="#" 
onClick="
	MM_goToURL('parent.frames[\'mainFrame\']','dataentry.php');
	MM_goToURL('parent.frames[\'topFrame\']','indexmenu.php?menuitem=0');
	return document.MM_returnValue
">
<?php if($menuitem==0) { echo "<b>"; }; ?>
Product Entry
<?php if($menuitem==0) { echo "</b>"; }; ?>
</a> | 
<a href="#" 
onClick="
	MM_goToURL('parent.frames[\'mainFrame\']','dr.php');
	MM_goToURL('parent.frames[\'topFrame\']','indexmenu.php?menuitem=1');
	return document.MM_returnValue
">
<?php if($menuitem==1) { echo "<b>"; }; ?>
DR Generator
<?php if($menuitem==1) { echo "</b>"; }; ?>
</a> | 
<a href="#" 
onClick="
	MM_goToURL('parent.frames[\'mainFrame\']','wiscor.php');
	MM_goToURL('parent.frames[\'topFrame\']','indexmenu.php?menuitem=2');
	return document.MM_returnValue
">
<?php if($menuitem==2) { echo "<b>"; }; ?>
WISCOR
<?php if($menuitem==2) { echo "</b>"; }; ?>
</a>  | 
<a href="#" 
onClick="
	MM_goToURL('parent.frames[\'mainFrame\']','prices.php');
	MM_goToURL('parent.frames[\'topFrame\']','indexmenu.php?menuitem=3');
	return document.MM_returnValue
">
<?php if($menuitem==3) { echo "<b>"; }; ?>
Prices
<?php if($menuitem==3) { echo "</b>"; }; ?>
</a> | 
<a href="#" 
onClick="
	MM_goToURL('parent.frames[\'mainFrame\']','dr_list.php');
	MM_goToURL('parent.frames[\'topFrame\']','indexmenu.php?menuitem=4');
	return document.MM_returnValue
">
<?php if($menuitem==4) { echo "<b>"; }; ?>
DR List
<?php if($menuitem==4) { echo "</b>"; }; ?>
</a>| 
<a href="#" 
onClick="
	MM_goToURL('parent.frames[\'mainFrame\']','inv_list.php');
	MM_goToURL('parent.frames[\'topFrame\']','indexmenu.php?menuitem=5');
	return document.MM_returnValue
">
<?php if($menuitem==5) { echo "<b>"; }; ?>
Inventories
<?php if($menuitem==5) { echo "</b>"; }; ?>
</a>
 ]
  <br>
</div><br>

<?php if($menuitem == -1) { ?>
<center>
	&nbsp;
</center>
<?php } ?>

<?php if($menuitem == 1) { ?>
<div id="DRopts" align="center">

<form action="dr.php" method="post" target="mainFrame">
Product Line: 
<select name="prodcat" style="width:100;">
<?php
	$q = "SELECT * FROM `principals` WHERE `hidden`=0;";
	$rs = mysql_query($q) or die(mysql_error());
	while($r = mysql_fetch_assoc($rs)) {
		echo "<option value='{$r['id']}'>{$r['title']}</option>";
	}
?>
</select>
Date: 
<select name="dr_m" style="width:40;">
<?php 

	for ($i = 1; $i <= 12; $i++)
		echo "<option value=$i>$i</option>";
?>
</select>
<select name="dr_d" style="width:40;">
<?php 

	for ($i = 1; $i <= 31; $i++)
		echo "<option value=$i>$i</option>";
?>
</select>
<select name="dr_y" style="width:80;">
<?php 
	$yearrange = 40;
	for ($i = date("Y") - $yearrange; $i <= date("Y") + $yearrange; $i++)
	{
		if($i == date("Y")) { $seltxt = "selected"; } else { $seltxt = ""; }
		echo "<option value=$i $seltxt>$i</option>";
	}
?>
</select>
<input type="submit" value="Proceed >>">
</form>
</div>
<?php } ?>
<?php if($menuitem == 2) { ?>
<div id="WISCORopts" align="center">

<form action="wiscor.php" method="get" target="mainFrame">
Product Line: 
<select name="prodgroup" style="width:100;">
<?php
	$q = "SELECT * FROM `principals` WHERE `hidden`=0;";
	$rs = mysql_query($q) or die(mysql_error());
	while($r = mysql_fetch_assoc($rs)) {
		echo "<option value='{$r['id']}'>{$r['title']}</option>";
	}
?>
</select>
Date: 
<select name="fm" style="width:40;">
<?php 

	for ($i = 1; $i <= 12; $i++)
		echo "<option value=$i>$i</option>";
?>
</select>
<select name="fd" style="width:40;">
<?php 

	for ($i = 1; $i <= 31; $i++)
		echo "<option value=$i>$i</option>";
?>
</select>
<select name="fy" style="width:80;">
<?php 
	$yearrange = 40;
	for ($i = date("Y") - $yearrange; $i <= date("Y") + $yearrange; $i++)
	{
		if($i == date("Y")) { $seltxt = "selected"; } else { $seltxt = ""; }
		echo "<option value=$i $seltxt>$i</option>";
	}
?>
</select>
&nbsp;TO 
<select name="tm" style="width:40;">
<?php 

	for ($i = 1; $i <= 12; $i++)
		echo "<option value=$i>$i</option>";
?>
</select>
<select name="td" style="width:40;">
<?php 

	for ($i = 1; $i <= 31; $i++)
		echo "<option value=$i>$i</option>";
?>
</select>
<select name="ty" style="width:80;">
<?php 
	$yearrange = 40;
	for ($i = date("Y") - $yearrange; $i <= date("Y") + $yearrange; $i++)
	{
		if($i == date("Y")) { $seltxt = "selected"; } else { $seltxt = ""; }
		echo "<option value=$i $seltxt>$i</option>";
	}
?>
</select>
&nbsp;Export to excel: <input name="xls" type="checkbox" value="true">
<input type="submit" value=">">
</form>
</div>
<?php } ?>
<?php 
 if($menuitem == 4) { ?>
<div id="dr_list_opts" align="center">

<form action="dr_list.php" method="get" target="mainFrame">
Product Line: 
<select name="prodgroup" style="width:80;">
<?php
	$q = "SELECT * FROM `principals` WHERE `hidden`=0;";
	$rs = mysql_query($q) or die(mysql_error());
	while($r = mysql_fetch_assoc($rs)) {
		echo "<option value='{$r['id']}'>{$r['title']}</option>";
	}
?>
</select>
Filter Date: 
<select name="fm" style="width:35;">
<?php 

	for ($i = 1; $i <= 12; $i++)
		echo "<option value=$i>$i</option>";
?>
</select>
<select name="fd" style="width:35;">
<?php 
	for ($i = 1; $i <= 31; $i++)
		echo "<option value=$i>$i</option>";
?>
</select>
<select name="fy" style="width:60;">
<?php 
	$yearrange = 40;
	for ($i = date("Y") - $yearrange; $i <= date("Y") + $yearrange; $i++)
	{
		if($i == date("Y")) { $seltxt = "selected"; } else { $seltxt = ""; }
		echo "<option value=$i $seltxt>$i</option>";
	}
?>
</select>
&nbsp;TO 
<select name="tm" style="width:35;">
<?php 

	for ($i = 1; $i <= 12; $i++)
		echo "<option value=$i>$i</option>";
?>
</select>
<select name="td" style="width:35;">
<?php 

	for ($i = 1; $i <= 31; $i++)
		echo "<option value=$i>$i</option>";
?>
</select>
<select name="ty" style="width:60;">
<?php 
	$yearrange = 40;
	for ($i = date("Y") - $yearrange; $i <= date("Y") + $yearrange; $i++)
	{
		if($i == date("Y")) { $seltxt = "selected"; } else { $seltxt = ""; }
		echo "<option value=$i $seltxt>$i</option>";
	}
?>
</select>
&nbsp;All <input name="showall" type="checkbox" value="true">
&nbsp;Ignore Dates <input name="dateoff" type="checkbox" value="true" checked>
<input type="submit" value=">>">
</form>
</div>
<?php } ?>

<?php 
 if($menuitem == 5) { ?>
<div id="inv_list_opts" align="center">

<form action="inv_list.php" method="get" target="mainFrame">
Product Line: 
<select name="prodgroup" style="width:80;">
<?php
	$q = "SELECT * FROM `principals` WHERE `hidden`=0;";
	$rs = mysql_query($q) or die(mysql_error());
	while($r = mysql_fetch_assoc($rs)) {
		echo "<option value='{$r['id']}'>{$r['title']}</option>";
	}
?>
</select>
Filter Date: 
<select name="fm" style="width:35;">
<?php 

	for ($i = 1; $i <= 12; $i++)
		echo "<option value=$i>$i</option>";
?>
</select>
<select name="fd" style="width:35;">
<?php 
	for ($i = 1; $i <= 31; $i++)
		echo "<option value=$i>$i</option>";
?>
</select>
<select name="fy" style="width:60;">
<?php 
	$yearrange = 40;
	for ($i = date("Y") - $yearrange; $i <= date("Y") + $yearrange; $i++)
	{
		if($i == date("Y")) { $seltxt = "selected"; } else { $seltxt = ""; }
		echo "<option value=$i $seltxt>$i</option>";
	}
?>
</select>
&nbsp;TO 
<select name="tm" style="width:35;">
<?php 

	for ($i = 1; $i <= 12; $i++)
		echo "<option value=$i>$i</option>";
?>
</select>
<select name="td" style="width:35;">
<?php 

	for ($i = 1; $i <= 31; $i++)
		echo "<option value=$i>$i</option>";
?>
</select>
<select name="ty" style="width:60;">
<?php 
	$yearrange = 40;
	for ($i = date("Y") - $yearrange; $i <= date("Y") + $yearrange; $i++)
	{
		if($i == date("Y")) { $seltxt = "selected"; } else { $seltxt = ""; }
		echo "<option value=$i $seltxt>$i</option>";
	}
?>
</select>
&nbsp;All <input name="showall" type="checkbox" value="true">
&nbsp;Ignore Dates <input name="dateoff" type="checkbox" value="true" checked>
<input type="submit" value=">>">
</form>
</div>
<?php } ?>


</div>
</body>
</html>
