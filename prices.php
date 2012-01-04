<?php 
	require_once('Connections/dbGlobal.php'); 
	ini_set('error_reporting', E_NONE);	
	mysql_select_db($database_dbGlobal, $dbGlobal);

	if($selectCommand == "Update")
	{
		//echo "Save $recordCount records.";
		for($i = 0; $i < $recordCount; $i++)
		{
				 $qupdate = sprintf( "
								UPDATE varsizes 
									SET 
										PNL='%0.2f',
										BKG='%0.2f',
										PBKG='%0.2f',
										LC='%0.2f',
										VATBKG='%0.2f',
										VATPBKG='%0.2f',
										BQ='%d'
								WHERE ID=%d", 
								$pnl[$i],
								$bkg[$i],
								$pbkg[$i],
								$lc[$i],
								$vatbkg[$i],
								$vatpbkg[$i],
								$bq[$i],
								$vsid[$i]);
				//echo $qupdate;
				$result = mysql_query($qupdate,$dbGlobal) or die(mysql_error());
		//echo $qupdate . "<br>";
		}
	}
	elseif($selectCommand == "Update All")
	{
	}
	else
	{
	}	
	if(!isset($prodcat)) { $prodcat = 1; }
?>
<HTML>
<HEAD>
<TITLE>PRICES</TITLE>
<LINK HREF="styles/style.css" REL="stylesheet" TYPE="text/css">
<link href="css/tabledisp.css" rel="stylesheet" type="text/css">
</HEAD>
<BODY>
<FORM ACTION="prices.php" METHOD="post">
        <TABLE WIDTH="840" BORDER="0" CELLPADDING="1" CELLSPACING="0">
          <!--DWLayoutTable-->
          <TR>
            <TD HEIGHT="25" COLSPAN="7" VALIGN="top">
			<STRONG>
				Manual Price List [
				<?php 
					$q = "SELECT * FROM `principals` WHERE `hidden` = 0;";
					$rs = mysql_query($q) or die(mysql_error());
					while($r = mysql_fetch_assoc($rs)) {
				?>
				<A href='?prodcat=<?php echo $r['id']; ?>' alt='<?php echo $r['description']; ?>'>
				<?php if ($prodcat == $r['id']) { ?>
				<strong>
				<?php } ?>			
				<?php echo $r['title']; ?>
				<?php if ($prodcat == $r['id']) { ?>
				</strong>
				<?php } ?>
				</A> 
				<?php
				}
				?>
				]
			</STRONG>
			</TD>
          </TR>
		  <TR>
			<TD WIDTH="350" HEIGHT="25" VALIGN="middle">Product/ SKU Name </TD>
			<TD WIDTH="70" ALIGN="center" VALIGN="middle">Panel Price </TD>
			<TD WIDTH="84" ALIGN="center" VALIGN="middle">Booking Price </TD>
			<TD WIDTH="84" ALIGN="center" VALIGN="middle">Prov. Booking </TD>
		    <TD WIDTH="71" ALIGN="center" VALIGN="middle">Landed Cost</TD>
			<TD WIDTH="64" ALIGN="center" VALIGN="middle">BKG with VAT</TD>
			<TD WIDTH="70" ALIGN="center" VALIGN="middle">PBKG with VAT</TD>
			<TD WIDTH="70" ALIGN="center" VALIGN="middle">BQ</TD>
	      </TR>
		  <?php
			$query_articles_join = 
			sprintf(
			"
				SELECT
					PRODUCTS.ID AS ProductID,
					PRODUCTS.Name AS ProductName,
					
					PRODUCTVARS.ID AS ProductVarID,
					PRODUCTVARS.VariantName AS VariantName,
					
					VARSIZES.ID AS VarSizeID,
					VARSIZES.*,
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
				ORDER BY ProductName ASC, VariantName ASC, xVS ASC;
			", $prodcat);
			$rs_articles = mysql_query($query_articles_join, $dbGlobal);
			$ctr = 0;
			while($rowArticles = mysql_fetch_assoc($rs_articles))
			{			
				$xpid = $rowArticles['ProductID'];
				$xpname = $rowArticles['ProductName'];
				$xpvid = $rowArticles['ProductVarID'];
				$xpvname = $rowArticles['VariantName'];
				$xvsid = $rowArticles['VarSizeID'];
				$xvid = $rowArticles['ID'];
				$xvsname = $rowArticles['VarSize'];
				$xprice_panel = number_format(0+$rowArticles['PNL'], 2, '.', '');
				$xprice_booking = number_format(0+$rowArticles['BKG'], 2, '.', '');
				$xprice_pbooking = number_format(0+$rowArticles['PBKG'], 2, '.', '');				
				$xprice_landed = number_format(0+$rowArticles['LC'], 2, '.', '');				
				$xprice_vatbkg = number_format(0+$rowArticles['VATBKG'], 2, '.', '');
				$xprice_vatpbkg = number_format(0+$rowArticles['VATPBKG'], 2, '.', '');
				$xbq = $rowArticles['BQ'];
	/*				
				$xprice_booking = round($xprice_landed * 1.01);  //--- 1%
				$xprice_pbooking = round($xprice_landed * 1.03); //--- 3%
				$xprice_panel = round($xprice_landed * 1.05); //--- 5%
	//*/			
				$zerotag[] = array("","","","","","","");
				if( $xprice_panel == '0.00' ) 
					$zerotag[0] = "<font color=red>*</font>";
				else
					$zerotag[0] = "";
				if( $xprice_booking == '0.00' ) 
					$zerotag[1] = "<font color=red>*</font>";
				else
					$zerotag[1] = "";
				if( $xprice_pbooking == '0.00' ) 
					$zerotag[2] = "<font color=red>*</font>";
				else
					$zerotag[2] = "";
				if( $xprice_landed == '0.00' ) 
					$zerotag[3] = "<font color=red>*</font>";
				else
					$zerotag[3] = "";
				if( $xprice_vatbkg == '0.00' ) 
					$zerotag[4] = "<font color=red>*</font>";
				else
					$zerotag[4] = "";
				if( $xprice_vatpbkg == '0.00' ) 
					$zerotag[5] = "<font color=red>*</font>";
				else
					$zerotag[5] = "";
				if( $xbq == '0.00' ) 
					$zerotag[6] = "<font color=red>*</font>";
				else
					$zerotag[6] = "";
				if(($ctr%2) == 0) { $cl = "even"; } else { $cl = "odd"; }
				echo "<tr class=$cl>
						<td height='25' valign='top'>$xpname $xpvname <b>$xvsname</b>
							<input name='vsid[$ctr]' type='hidden' id='vsid[$ctr]' value=$xvsid></td>
						<td align='right' valign='middle'>
							$zerotag[0] <input name='pnl[$ctr]' type='text' id='pnl[$ctr]' size='6' 
										value=\"{$xprice_panel}\" style='background:none; border:thin'>
						</td>
						<td align='right' valign='middle'>
							$zerotag[1] <input name='bkg[$ctr]' type='text' id='bkg[$ctr]' size='6' 
									value=\"{$xprice_booking}\" style='background:none; border:thin'>
						</td>
						<td align='right' valign='middle'>
							$zerotag[2] <input name='pbkg[$ctr]' type='text' id='bkg[$ctr]' size='6' 
									value=\"{$xprice_pbooking}\" style='background:none; border:thin'>
						</td>
						<td align='right' valign='middle'>
							$zerotag[3] <input name='lc[$ctr]' type='text' id='lc[$ctr]' size='6' 
									value=\"{$xprice_landed}\" style='background:none; border:thin'>
						</td>
						<td align='right' valign='middle'>
							$zerotag[4] <input name='vatbkg[$ctr]' type='text' id='vatbkg[$ctr]' size='6' 
									value=\"{$xprice_vatbkg}\" style='background:none; border:thin'>
						</td>
						<td align='right' valign='middle'>
							$zerotag[5] <input name='vatpbkg[$ctr]' type='text' id='vatpbkg[$ctr]' size='6' 
									value=\"{$xprice_vatpbkg}\" style='background:none; border:thin'>
						</td>
						<td align='right' valign='middle'>
							$zerotag[6] <input name='bq[$ctr]' type='text' id='bq[$ctr]' size='6' 
									value=$xbq style='background:none; border:thin'>
						</td>
				</tr>
				";
				$ctr++;
			}
		?>
<TR>
		    <TD HEIGHT="28" COLSPAN="7" ALIGN="center" VALIGN="middle"><EM><?php echo $ctr ?> Items. With Selected: 
			  <INPUT NAME="recordCount" TYPE="hidden" ID="recordCount" VALUE="<?php echo $ctr ?>">
			  <INPUT NAME="prodcat" TYPE="hidden" ID="prodcat" VALUE="<?php echo $prodcat ?>">
		      <INPUT NAME="selectCommand" TYPE="submit" ID="selectCommand" VALUE="Update">
		      <INPUT NAME="selectCommand" TYPE="submit" ID="selectCommand" VALUE="Update All">
    </EM></TD>
          </TR>
        </TABLE>
</FORM>
</BODY>
</HTML>
