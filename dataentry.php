<?php require_once('connections/dbglobal.php'); ?>
<?php
	$talkback = "";
	$talkbacklinkcolor = "#FF9999";
	
	if(!isset($prodcat)) { $prodcat = 1; }

	mysql_select_db($database_dbGlobal, $dbGlobal);
	
	if($newprod == 'true')
	{
		$query_add = mysql_query(
			sprintf(
				"INSERT INTO products (ID, Name, Abbr, ProdGroup) VALUES(0, '%s', '%s', '%d')", 
				$txtNewprod, $txtNewprodAbbr, $prodcat 
			), 
				$dbGlobal) or die(mysql_error());
	}
	
	if($addpvar == 'true' && ($spid))
	{
		if(!empty($newpvar)) 
			$query_add = mysql_query(
				sprintf(
					"INSERT INTO productvars (ID, VariantName, ABBR, ProductID) VALUES (0, '%s', '%s', %d)",
					$newpvar, $newpvarabbr, $spid
				),
					$dbGlobal) or die(mysql_error());
		if(!empty($editpvar))			
			$query_update = mysql_query(
				sprintf(
					"UPDATE `productvars` SET `VariantName`='%s' WHERE ID=%d",
					$editpvar, $spvid
				),
				$dbGlobal) or die(mysql_error());
	}
	else {
		if($addpvar == 'true')
			$talkback = "Please select/highlight a Product to continue.";
	}
	
	if($addvarx == 'true' && ($spvid && $spid))
	{
		if($newvarx) {
		$query_add = mysql_query(
			sprintf(
				"INSERT INTO varsizes (ID, VarSize, PC, PVarID,PID) VALUES (0, '%s', '%s', %d, %d)",
				$newvarx, $pcode, $spvid, $spid
			),
				$dbGlobal) or die(mysql_error());
		}
		else if ($editvarx) {
			if($editvarxpc)
				$evxquery = sprintf("UPDATE `varsizes` SET VarSize='%s', PC='%s' WHERE PVarID=%d AND PID=%d AND `ID`=%d", $editvarx, $editvarxpc, $spvid, $spid, $svsid);
			else
				$evxquery = sprintf("UPDATE `varsizes` SET VarSize='%s' WHERE PVarID=%d AND PID=%d AND `ID`=%d", $editvarx, $spvid, $spid, $svsid);
			$query_add = mysql_query( $evxquery	, $dbGlobal) or die(mysql_error());
		}		
		else
		{
			// none!
		}
	}
	else {
		if($addvarx == 'true')
			$talkback = "Please select/highlight a Product and a Variant to continue.";
	}
	
	if($sact == 'del')
	{
		
		if($confirm == 'true')
		{
			$query_del = mysql_query(
				sprintf(
					"DELETE from products WHERE ID=%d", 
					$spid
				), 
					$dbGlobal) or die(mysql_error());
					header("Location: dataentry.php?prodcat=$prodcat"); 
		}
		else
		{
			$talkback =	"Confirm deletion : 
			<a 
			href='dataentry.php?prodcat=$prodcat&spid=$spid&sact=del&confirm=true' 
			style='background-color:$talkbacklinkcolor'>Proceed</a> /
			<a 
			href='dataentry.php?prodcat=$prodcat&spid=$spid' 
			style='background-color:$talkbacklinkcolor'>Cancel</a>";
		}
	}
	else if($sact == 'edit')
	{
		if($confirm == true)
		{
			$query_edit = mysql_query(
						sprintf(
							"UPDATE `products` 
							SET `Name`='%s',
								`Abbr`='%s'
							WHERE ID=%d", $editname, $editnameabbr, $spid), 
						$dbGlobal) or die(mysql_error());
			header("Location: dataentry.php?prodcat=$prodcat"); 
					
		}
		else
		{
			$query_editsel = mysql_query( sprintf( "SELECT * FROM `products` WHERE ID=%d LIMIT 1", $spid), $dbGlobal) or die(mysql_error());
			$editsel = mysql_fetch_assoc($query_editsel);
			$talkback = "
			<form method=post style=background-color:$talkbacklinkcolor; 
			action='dataentry.php?prodcat=$prodcat&spid=$spid&sact=edit&confirm=true'>
			Enter <b>new</b> name for entry: <input name=editname type=input value='{$editsel['Name']}'> Abbr: <input name=editnameabbr type=input value='{$editsel['Abbr']}'>
			<input id=apbutton value=Submit type=submit>
			</form>
			";
		}
	}
	
	
	if(($act == 'pvdel') && ($confirm == 'true'))
	{
		$qpvdel = sprintf("DELETE FROM `productvars` WHERE ID=%d", $spvid);
		$qvxdel = sprintf("DELETE FROM `varsizes` WHERE PVarID=%d", $spvid);
		$rs = mysql_query($qpvdel, $dbGlobal) or die(mysql_error());
		$rs2 = mysql_query($qvxdel, $dbGlobal) or die(mysql_error());
		header("Location: dataentry.php?prodcat=$prodcat&spid=$spid");
	}
	//*/
	$query_rsProducts = 
		sprintf( "SELECT * FROM products WHERE ProdGroup=%d ORDER BY Name ASC", $prodcat);
	$rsProducts = mysql_query($query_rsProducts, $dbGlobal) or die(mysql_error());
	$row_rsProducts = mysql_fetch_assoc($rsProducts);
	$totalRows_rsProducts = mysql_num_rows($rsProducts);
	
	if(isset($spid)) {		
		$query_rsProductVars = sprintf("SELECT * FROM productvars WHERE ProductID=%d", $spid);
		$rsProductVars = mysql_query($query_rsProductVars, $dbGlobal) or die(mysql_error());
		//echo mysql_num_rows($rsProductVars);
		//$row_rsProductVars = mysql_fetch_assoc($rsProductVars);
	}
?>
<html>
<head>
<title>Product Info Control Page</title>
<link href='styles/style.css' rel='stylesheet' type='text/css' />
<script type="text/javascript" src="scripts/interface_related.js" language="jscript"></script>
</head>
<body>

<h2>Product Data Entry Sheet</h2>
	<!--NOTE: <br><a href="#">links in <strong>BOLD</strong></a> are active items.-->
	<h3>
	Product Category: 
	<?php
	$query_rsPrincipals = 
		sprintf( "SELECT * FROM `principals` WHERE NOT `hidden`=1", $prodcat);
	$rsPrincipals = mysql_query($query_rsPrincipals, $dbGlobal) or die(mysql_error());
	if($rsPrincipals)
	while($row_rsPrincipals = mysql_fetch_assoc($rsPrincipals)) {	
	?>
	<a href='?prodcat=<?php echo $row_rsPrincipals['id']; ?>'>
	<?php if( $prodcat==$row_rsPrincipals['id'] ) { echo "<strong>"; } ?>
	<?php echo $row_rsPrincipals['title']; ?>
	<?php if( $prodcat==$row_rsPrincipals['id'] ) { echo "</strong>"; } ?>
	</a> 	
	<?php
	if ($row_rsPrincipals) echo "| ";
	}
	?>
	<br />
	</h3>
Select a product: <?php echo $totalRows_rsProducts; echo " item(s)"; ?>
<div id="sidecontent">
Don't see what you're looking for?<br><br>
 <a href="#">Add a product ></a>
<div id="addprod">
<form action="dataentry.php" method="post">
Name: <input type="text" name="txtNewprod" onKeyPress="">
Abbr: <input type="text" name="txtNewprodAbbr" onKeyPress="">
<input id="apbutton" type="submit" value="Submit">
<input type="hidden" name="newprod" value="true">
<input type="hidden" name="prodcat" value="<?php echo $prodcat; ?>">
</form>
</div>
</div>
<span style="background-color:<?php echo $talkbacklinkcolor ?>">
<?php echo $talkback; ?>
</span>
<table width=550>
<?php

	if($totalRows_rsProducts == 0)
	{
		echo "
		<tr height='24' valign='middle'>
			<td align='center'>
				<font size=2>No items are shown for this category. See the sidebar for adding a product.</font>
			</td>
		</tr>
		";
	} 
	else 
	{/**/
		//if(!isset($spid)) {	$spid = 1; }
		$cellctr = 0;
		do {
			if(($cellctr)%5 == 0 && $cellctr !=0) { echo "</tr>"; }
			if($cellctr%5 == 0) { echo "<tr height='24' valign='middle'>"; }
				$pid = $row_rsProducts['ID'];
				echo "<td width='20%'><a href='dataentry.php?prodcat=$prodcat&spid=$pid'>"; //selected
				//echo $cellctr%5;
				if($row_rsProducts['ID'] == $spid) { echo "<b>";}
				echo "&nbsp;";
				echo $row_rsProducts['Name'];
				echo " <sub>[" . $row_rsProducts['Abbr'] . "]</sub>";
				if($row_rsProducts['ID'] == $spid) { echo "</b>";}
				echo "</a></td>";			
			$cellctr++;
		} while (($row_rsProducts = mysql_fetch_assoc($rsProducts)) || (cellctr%4 != 0));
	  }
	//  mysql_free_result($row_rsProducts);
?>
<tr valign="middle" height="25">
	<td colspan="5" align="center">
	<hr noshade="noshade" size="1">
	<font size="1">
	<?php
		echo "Active Item Options [ ";
		if(!isset($spid))
		{
			echo "To view these options, please select an item. ]";
		}
		else
		{
			echo "<a href='dataentry.php?prodcat=$prodcat&spid=$spid&sact=edit'>Edit</a> | ";
			echo "<a href='dataentry.php?prodcat=$prodcat&spid=$spid&sact=del'>Delete</a> ]";
		}
	?>
	</font>
	</td>
</tr>
</table>
<h4>Details for selected product:</h4>
<table width='550' border='0' cellpadding='1' cellspacing='1'>
	<tr height=24>
		<td  width="50%" valign="top">
		<h6>Product Variants:</h6>
		</td>
		<td width="50%" valign="middle">
		<h6>Available Variant Sizes:</h6>
		</td>
	</tr>
	<tr height=24 valign="middle">
		<td>
			<font size="2">
			<?php
		if($act == 'pvdel')
		{
		?>
		
		Confirm Delete? ( <a 
								href="dataentry.php?prodcat=<?php 
								echo $prodcat; ?>&spid=<?php 
								echo $spid ?>&spvid=<?php 
								echo $spvid ?>&act=pvdel&confirm=true" method="post">Yes</a> / <a
								href="dataentry.php?prodcat=<?php 
								echo $prodcat; ?>&spid=<?php 
								echo $spid ?>&spvid=<?php 
								echo $spvid ?>">No</a> )
		<?php
		
		}
		else {		
		?>
			</font>
			<font size="1">
			<form action="dataentry.php?prodcat=<?php 
								echo $prodcat; ?>&spid=<?php 
								echo $spid ?>&spvid=<?php 
								echo $spvid ?>" method="post">
				<?php
					if($act == pvedit)
					{
					$query_editsel2 = mysql_query( sprintf( "SELECT * FROM `productvars` WHERE ID=%d LIMIT 1", $spvid), $dbGlobal) or die(mysql_error());
					$editsel2 = mysql_fetch_assoc($query_editsel2);
					$curname = $editsel2['VariantName'];
					$curabbr = $editsel2['ABBR'];
				?>
				Rename:
				<input name=editpvar type=input value="<?php echo $curname ?>">
				<br>Abbrvte :<input name=editpvarabbr type=input  value="<?php echo $curabbr ?>">
				<?php
					}
					else
					{
				?>
				Add new:
				<input name=newpvar type=input>
				<br>Abbrvte :<input name=newpvarabbr type=input>
				<?php
					}
				?>
				<input name='addpvar' value='true' type=hidden>
				<input id=apbutton value=Submit type=submit>
			</form>
		<?php
		}
		?>
			</font>
		</td>
		<td>
			<font size="1">		
			<form action="dataentry.php?prodcat=<?php 
											echo $prodcat 
										?>&spid=<?php 
											echo $spid 
												?>&spvid=<?php 
											echo $spvid 
										?>" method="post">
				<?php
					if($act == 'vsedit')
					{
				?>
				Rename:
				<input name=editvarx type=input>
				<br>New PC:
				<input name=editvarxpc type=input>
				<?php
					}
					else
					{
				?>
				Add new :
				<input name=newvarx type=input>
				<br>Prd Code:
				<input name=pcode type=input>
				<?php
					}
				?>
				<input name='addvarx' value='true' type=hidden>				
				<input id=apbutton value=Submit type=submit>
			</form>
			</font>
		</td>
	</tr>
	<tr>
		<td>
			<font size="2">
			<?php
				$itemctr = 0;
				if($rsProductVars)
				while($row_rsProductVars = mysql_fetch_assoc($rsProductVars))
				{
				$idpv = $row_rsProductVars['ID']
			?>		
					<a href='<?php echo 
						"dataentry.php?prodcat=$prodcat&spid=$spid&spvid=$idpv&act=pvedit"; ?>'>
						<img src='images/b_edit.png' alt='drop' width='16' height='16' border='0'>
					</a>&nbsp; 
					<a href='<?php echo 
						"dataentry.php?prodcat=$prodcat&spid=$spid&spvid=$idpv&act=pvdel"; ?>'>
						<img src='images/b_drop.png' alt='drop' width='16' height='16' border='0'>
					</a>&nbsp; 
					<a href='<?php echo	"dataentry.php?prodcat=$prodcat&spid=$spid&spvid=$idpv"; ?>'>
						<?php if($row_rsProductVars['ID'] == $spvid) { echo "<b>"; } ?> 	
						<?php echo $row_rsProductVars['VariantName']; ?>
						<sub>(<?php echo $row_rsProductVars['ABBR']; ?>)</sub>
						<?php if($row_rsProductVars['ID'] == $spvid) { echo "</b>"; } ?> 	
					</a>
					<br>

				<?php
					$itemctr++;
				};
				
				if ($act == pvedit)
				{
					echo "
					<a href='dataentry.php?prodcat=$prodcat&spid=$spid&spvid=$idpv'>
						Cancel Edit Mode
					</a> | ";
				}
				
				
				echo "&nbsp;";
				if($itemctr == 0)
					echo "No";
				else
					echo $itemctr;
				echo " records.";
				?>
			</font>
		</td>
		<td valign="top">
			<font size="2">
			<?php 
			if(isset($spvid))
			{
				$query_rsVarSizes = sprintf("
					SELECT 
						*, (CAST(`Varsize` AS CHAR) | CAST('axaa' AS CHAR)) as xVS 
					from varsizes 
						WHERE PVarID=%d 
						ORDER BY xVS", 
					$spvid);
				$rsVarSizes = mysql_query($query_rsVarSizes) or die(mysql_error());
				$rowctr = 0;
				//echo $query_rsVarSizes;
				
				while ($row_rsVarSizes = mysql_fetch_assoc($rsVarSizes)) {
					$varsz = $row_rsVarSizes['VarSize'] . " x" . $row_rsVarSizes['BQ'];
					$varpc = $row_rsVarSizes['PC'];
					$svsid = $row_rsVarSizes['ID'];
				
				echo "
					<a href='dataentry.php?prodcat=$prodcat&spid=$spid&spvid=$spvid&svsid=$svsid&act=vsedit'>
						<img src='images/b_edit.png' alt='edit' width='16' height='16' border='0'>
					</a>&nbsp; 
					<a href='dataentry.php?prodcat=$prodcat&spid=$spid&spvid=$spvid&svsid=$svsid&act=vsdel'>
						<img src='images/b_drop.png' alt='drop' width='16' height='16' border='0'>
					</a>&nbsp; 
					<a href='dataentry.php?prodcat=$prodcat&spid=$spid&spvid=$spvid'>
						$varsz ($varpc)
					</a>
					<br>
					";
				 $rowctr++;
				};
				if($rowctr == 0) { echo "No items exist for this variant."; }
			}
			else
			{
				echo "Select a product variant to view this section.";
			}
			?>
			</font>	
		</td>
	</tr>
</table>
</body>


</html>
<?php
mysql_free_result($rsProducts);
?>
