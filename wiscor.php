<?php 
	if($xls == 'true') 	{
		header("Content-Type:application/vnd.ms-excel");
	  //header("Content-Disposition: attachment; filename=Wisc" . md5(date("U") ." .xlsx"); 
	}
?>
<html>
<head>
<title>WISCOR</title>
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
</head>
<body>
<?php
	// GLOBAL -- CONNECTION 
	require_once('Connections/dbGlobal.php');
	mysql_select_db($database_dbGlobal, $dbGlobal);

	// GLOBAL -- Date condition for AS and DR exclusion.
	$date_condition_sql = sprintf(" AND DRDate >= '%d-%d-%d' AND DRDate <= '%d-%d-%d' ",
													$fy, $fm, $fd, $ty, $tm, $td );
	// START PRODUCTS
	if(!isset($prodgroup))
		die("<br><center>Please select a Product Line and a Date Range.</center>"); //
		//$query_rsProducts = sprintf("SELECT * FROM products");
	else
		$query_rsProducts = sprintf("SELECT * FROM products where ProdGroup=%d",$prodgroup);
		$rsProducts = mysql_query($query_rsProducts, $dbGlobal);

	// Pre-counter for first row.
	$qcount_vs = sprintf("
					SELECT 
						COUNT(*) AS CountA
					FROM 
						VARSIZES 
					LEFT JOIN PRODUCTS 
						ON VARSIZES.PID = PRODUCTS.ID
					WHERE PRODUCTS.Prodgroup=%d;",
					$prodgroup
					);
	$rs = mysql_query($qcount_vs, $dbGlobal);
	$row = mysql_fetch_assoc($rs);
	$vs_count = $row['CountA'];
	$vsa_count = $vs_count + 6;
	$colspan_dist = round($vsa_count * 0.2);
	$colspan_area = round($vsa_count * 0.2);
	$colspan_page = round($vsa_count * 0.2);
	$colspan_from = round($vsa_count * 0.2);
	$colspan_to = round($vsa_count * 0.2);
	
	$overflow = $vsa_count - 
				(
					$colspan_dist 
				+ 	$colspan_area 
				+ 	$colspan_page 
				+ 	$colspan_from 
				+ 	$colspan_to
				);
	$colspan_to += $overflow;
	
	$prodgroupname = array("Oishi","Oishi 2");
	
	//row maximizer
	$w_maxrows = 29;
	$w_rowctr = 0;
	mysql_free_result($rs);
?>
<table width=100% border='1' cellpadding='1' cellspacing='0'>
	<tr height="50" style="border:none;">
		<td colspan="<?php echo $vsa_count ?>">
			<center>
				  <h3>SDM DISTRIBUTORS<br>
				  Weekly Inventory Sales & Control Report
				  </h3>
			</center>
		</td>
	</tr>
	<tr height='24'  style="border:none;">
		<td colspan=<?php echo $colspan_dist ?> style="border:none" >
			Distributor: <strong>Seth D. Malaki</strong>
		</td>
		<td colspan=<?php echo $colspan_area ?> style="border:none" >
			Area: <strong>Roxas City, Panay</strong>
		</td>
		<td colspan=<?php echo $colspan_page ?> style="border:none" >
			Page: _______
		</td>
		<td colspan=<?php echo $colspan_from ?> style="border:none" >
			Date Range: <strong><?php 
			echo date("M j, Y", mktime(0,0,0,$fm,$fd,$fy) ) ?></strong> to <strong><?php 
			echo date("M j, Y", mktime(0,0,0,$tm,$td,$ty) ) ?></strong>
		</td>
		<td colspan=<?php echo $colspan_to ?> style="border:none" >
			Product Line: <strong><?php echo $prodgroupname[$prodgroup-1]; ?></strong>
		</td>
	</tr>
	<tr height='30' >
		<td width='70' rowspan='3' valign='middle' align='center'>DR Date</td>
		<td width='70' rowspan='3' valign='middle' align='center'>DR Number</td>
		<td width='140' rowspan='3' valign='middle' align='center'>CUSTOMER</td>	
		<?php 	
			// Product Name
			while( $row_rsProducts = mysql_fetch_assoc($rsProducts) ) {
				$query_rsProductVars = sprintf("SELECT * FROM productvars WHERE ProductID=%d", $row_rsProducts['ID']);
				$query_rsVS = sprintf("SELECT * FROM varsizes WHERE PID=%d", $row_rsProducts['ID']);
				$rsProductVars = mysql_query($query_rsProductVars, $dbGlobal);
				$rsVS = mysql_query($query_rsVS, $dbGlobal);
				$totalRows_rsVS = mysql_num_rows($rsVS);			
				if($totalRows_rsVS == 0 ) { $totalRows_rsVS = 1; }

				$pCurrentProdName = substr($row_rsProducts['Name'],0, $totalRows_rsVS*3 );
				//special case -- Oh BOY!
				if($row_rsProducts['Name'] == "OH BOY!")
					$pCurrentProdName = "OHB";
				echo "
					<td valign='middle' align='center' colspan='$totalRows_rsVS' >
						$pCurrentProdName
					</td>
					";
			};
		?>
		<td width='70' rowspan='3' valign='middle' align='center'>Selling Price</td>
		<td width='70' rowspan='3' valign='middle' align='center'>Landed Cost</td>
		<td width='70' rowspan='3' valign='middle' align='center'>CRR Number</td>
	</tr>
	<tr height='21' valign='middle' >
	<?php
			mysql_free_result($rsProducts);
			$rsProducts = mysql_query($query_rsProducts, $dbGlobal);		
			while($row_rsProducts = mysql_fetch_assoc($rsProducts))
			{
				$query_rsProductVars = 
					sprintf("SELECT * FROM productvars WHERE ProductID=%d", $row_rsProducts['ID']);
				$rsProductVars = mysql_query($query_rsProductVars, $dbGlobal);
				while($row_rsProductVars = mysql_fetch_assoc($rsProductVars)) {	//while level (2)			
					mysql_free_result($rsVS);
					$query_rsVS = 
					sprintf("SELECT * FROM varsizes WHERE PVarID=%d", $row_rsProductVars['ID']);
					$rsVS = mysql_query($query_rsVS, $dbGlobal);
					$totalRows_rsVS = mysql_num_rows($rsVS);
					if($totalRows_rsVS == 0 ) { $totalRows_rsVS = 1; }
					$pCurrentPVName = $row_rsProductVars['VariantName'];
					if($mode != 'abbr') {
						if(strlen($pCurrentPVName) > 4)
						{
							$pvsplit = preg_split("/[\s,]+/", $pCurrentPVName); 							
							$pvsplit_size = count($pvsplit);
							if($pvsplit_size == 1)	{
								//abbreviate shorter using substr 4 len
								$pvabbr = substr($pCurrentPVName,0,4);
							} else 	{
								$pvabbr = "";
								for($ii = 0; $ii < $pvsplit_size; $ii++ )
									$pvabbr .= substr($pvsplit[$ii],0,1);					
							}
						}
						else
							$pvabbr = $pCurrentPVName;
					} else
						$pvabbr = $pCurrentPVName;			
					$pvabbr = $row_rsProductVars['ABBR'];			
					echo "\t\t<td align='middle' colspan='$totalRows_rsVS'>$pvabbr</td>\n";
				};
			}
		?>
	  </tr>
	  <tr height='21'>
	  <?php  
		$vsarea_width = 25; //smallest column size, used to define var size <td>'s width. dependents: product and prodvar width 
		$row_rsProducts = mysql_data_seek($rsProducts, 0);
		$row_rsProductVars = mysql_data_seek($rsProductVars,0);
		while($row_rsProducts = mysql_fetch_assoc($rsProducts)) {
			$query_rsProductVars = 
					sprintf("SELECT * FROM productvars WHERE ProductID=%d", $row_rsProducts['ID']);
			$rsProductVars = mysql_query($query_rsProductVars, $dbGlobal);
			while($row_rsProductVars = mysql_fetch_assoc($rsProductVars)) {	//while level (2)
				mysql_free_result($rsVS);
				$query_rsVS = 
				sprintf("
					SELECT 
						*, (CAST(`Varsize` AS CHAR) | CAST('axaa' AS CHAR)) as xVS 
					FROM varsizes 
					WHERE PVarID=%d 
					ORDER BY xVS", $row_rsProductVars['ID']);
				$rsVS = mysql_query($query_rsVS, $dbGlobal);
				$totalRows_rsVS = mysql_num_rows($rsVS);
				if($totalRows_rsVS == 0 ) { $totalRows_rsVS = 1; }
				while($row_rsVS = mysql_fetch_assoc($rsVS)) { // while level (3)				
					//$pCurrentVSName = $row_rsVS['xVS'];
					$pCurrentVSName = $row_rsVS['VarSize'];
					echo "\t\t<td width='$vsarea_width' valign='middle' align='center'>$pCurrentVSName</td>\n";
				}; // end while level (3)
			} // end while level (2)
		}
		?>
  </tr>
  <tr height='21' class="fwdbalcolor">
  <?php
	//FORWARDED BAL
  	// from this point on, width is already set at vsizes column.
	$w_rowctr++; //first row.
  	//echo "\n\t<td valign='middle' align='center'>WDATE</td>";
    //echo "\n\t<td valign='middle' align='center'>WPAGE65</td>";
    echo "\n\t<td colspan=3 valign='middle' align='center'>BALANCE FORWARDED</td>\n";
  

	$row_rsProducts = mysql_data_seek($rsProducts, 0);
	$row_rsProductVars = mysql_data_seek($rsProductVars,0);
	while($row_rsProducts = mysql_fetch_assoc($rsProducts)) {
		$query_rsProductVars = 
				sprintf("SELECT * FROM productvars WHERE ProductID=%d", $row_rsProducts['ID']);
		$rsProductVars = mysql_query($query_rsProductVars, $dbGlobal);
		/*
		echo "<!--";
		echo $row_rsProducts['Name'];
		echo "-->";
		*/
		while($row_rsProductVars = mysql_fetch_assoc($rsProductVars)) {	//while level (2)
			/*
			echo "<!--";
			echo $row_rsProductVars['VariantName'];
			echo "-->";
			*/
			mysql_free_result($rsVS);
			$query_rsVS = 
			sprintf("
					SELECT 
						*, (CAST(`Varsize` AS CHAR) | CAST('axaa' AS CHAR)) as xVS 
					FROM varsizes 
					WHERE PVarID=%d 
					ORDER BY xVS", $row_rsProductVars['ID']);
			$rsVS = mysql_query($query_rsVS, $dbGlobal);
			$totalRows_rsVS = mysql_num_rows($rsVS);
			if($totalRows_rsVS == 0 ) { $totalRows_rsVS = 1; }
			//TODO: get from DR
			while($row_rsVS = mysql_fetch_assoc($rsVS)) { // while level (3)				
				$pCurrentVSName = sprintf("%d.%d.%d", 
					$row_rsProducts['ID'], $row_rsProductVars['ID'], $row_rsVS['ID']);
				//TODO: get from DR
				$q_fb_as = sprintf("SELECT 
								SUM(Quantity)
							FROM 
								drdetails
							LEFT JOIN dr
								ON drdetails.DRID = dr.ID
							WHERE 
								PID = '%d' 
								AND
								PVID = '%d' 
								AND
								VS = '%d' 
								AND
								dr.AcctType=4 
								AND
								dr.DRDate < '%d-%d-%d';", 
								$row_rsProducts['ID'], 
								$row_rsProductVars['ID'], 
								$row_rsVS['ID'],
								$fy, $fm, $fd);
				$q_fb_dr = sprintf("SELECT 
								SUM(Quantity)
							FROM 
								drdetails
							LEFT JOIN dr
								ON drdetails.DRID = dr.ID
							WHERE 
								PID = '%d' 
								AND
								PVID = '%d' 
								AND
								VS = '%d' 
								AND
								(dr.AcctType!=4 AND dr.AcctType < 8)
								AND
								dr.DRDate < '%d-%d-%d';", 
								$row_rsProducts['ID'], 
								$row_rsProductVars['ID'], 
								$row_rsVS['ID'],
								$fy, $fm, $fd);
								
				//die( $q_fb_dr . " | " . $q_fb_as);
				$res = mysql_query($q_fb_as, $dbGlobal) or die(mysql_query());
				$row = mysql_fetch_row($res);
				$past_as_sum = $row[0];
				
				mysql_free_result($res);
				
				$res = mysql_query($q_fb_dr, $dbGlobal) or die(mysql_query());
				$row = mysql_fetch_row($res);
				$past_dr_sum = $row[0];
				
				$fb_cell = number_format($past_as_sum - $past_dr_sum,0);
				
	  			echo "\t\t<td width='$vsarea_width' valign='middle' align='right'><b>$fb_cell</b></td>\n";				
			}; // end while level (3)
		} // end while level (2)
	}
	
		$q_fb_as_lc = sprintf("
			SELECT 
				SUM(DRAmount),  
				SUM(DRNetAmount)
			FROM `dr` WHERE DRDate < '%d-%d-%d' AND AcctType = 4 AND ProdGroup=%d;",
			$fy, $fm, $fd, $prodgroup
		);
		$q_fb_dr_lc = sprintf("
			SELECT 
				SUM(DRAmount),  
				SUM(DRNetAmount)
			FROM `dr` WHERE DRDate < '%d-%d-%d' AND (AcctType !=4 AND AcctType < 8)AND ProdGroup=%d;",
			$fy, $fm, $fd, $prodgroup
		);
						
		mysql_free_result($res);
		
		$res = mysql_query($q_fb_as_lc, $dbGlobal) or die(mysql_query());
		$row = mysql_fetch_row($res);
		$past_as_sum_lc = $row[0];
		$past_as_nsum_lc = $row[1];
		
		mysql_free_result($res);
		
		$res = mysql_query($q_fb_dr_lc, $dbGlobal) or die(mysql_query());
		$row = mysql_fetch_row($res);
		$past_dr_sum_lc = $row[0];
		$past_dr_nsum_lc = $row[1];
		
		$fb_sum = number_format($past_as_sum_lc - $past_dr_sum_lc, 2,'.',',');
		$fb_nsum = number_format($past_as_nsum_lc - $past_dr_nsum_lc, 2,'.',',');
		
		echo "\n\t<td valign='middle' align='right'>$fb_nsum</td>";
		echo "\n\t<td valign='middle' align='right'>$fb_nsum</td>";
		echo "\n\t<td valign='middle' align='center'>&nbsp;</td>";
	?>
  </tr>
	<?php
////////////////////////////////////////////
	$ctr = 0;
	$dr_count = 0;
	$total_Astocks = 0;
	
	$q_as = sprintf("
						SELECT * FROM `dr` 
						WHERE 
							`AcctType`=4 
								AND 
							`ProdGroup`=%d", 
						$prodgroup) . 
		$date_condition_sql . 
		" ORDER BY `DRDate` ASC, `DRNumber` ASC "; //AcctType 4 = AS
	$rs_as = mysql_query($q_as,$dbGlobal) or die(mysql_error() . $q_as);
	while(($row_as = mysql_fetch_assoc($rs_as)) && ($w_rowctr < $w_maxrows))
	{
		$dr_date = $row_as['DRDate'];
		$dr_number = $row_as['DRNumber'];
		$dr_acctname = $row_as['AccountName'];
		
		$dr_amt = number_format($row_as['DRAmount'],2,'.',',');
		$dr_netamt = number_format($row_as['DRNetAmount'],2,'.',',');
		
////////////////////////////////////////////
	?>
  <tr height='21' class="arstkscolor">
	<?php
				$w_rowctr++;
				//ARRIVED STOCKS
				// from this point on, width is already set at vsizes column.
				echo "\n\t<td valign='middle' align='center'>$dr_date</td>";
				echo "\n\t<td valign='middle' align='center'>$dr_number</td>";
				echo "\n\t<td valign='middle' align='center'>$dr_acctname</td>\n";	
				$row_rsProducts = mysql_data_seek($rsProducts, 0);
				$row_rsProductVars = mysql_data_seek($rsProductVars,0);
				while($row_rsProducts = mysql_fetch_assoc($rsProducts)) {
					$query_rsProductVars = 
							sprintf("SELECT * FROM productvars WHERE ProductID=%d", 
							$row_rsProducts['ID']);
					$rsProductVars = mysql_query($query_rsProductVars, $dbGlobal);
					while($row_rsProductVars = mysql_fetch_assoc($rsProductVars)) {	//while level (2)
						mysql_free_result($rsVS);
						$query_rsVS = 
						sprintf("
							SELECT 
								*, (CAST(`Varsize` AS CHAR) | CAST('axaa' AS CHAR)) as xVS 
							FROM varsizes 
							WHERE PVarID=%d 
							ORDER BY xVS", $row_rsProductVars['ID']);
						$rsVS = mysql_query($query_rsVS, $dbGlobal);
						$totalRows_rsVS = mysql_num_rows($rsVS);
						if($totalRows_rsVS == 0 ) { $totalRows_rsVS = 1; }
						while($row_rsVS = mysql_fetch_assoc($rsVS)) { // while level (3)				
							$pCurrentVSName = sprintf("%d.%d.%d", 
								$row_rsProducts['ID'], $row_rsProductVars['ID'], $row_rsVS['ID']);
							$q_as_d = sprintf("SELECT * FROM `drdetails` 
										WHERE 
											PID=%d AND 
											PVID=%d AND 
											VS=%d AND 
											DRID=%d",
											$row_rsProducts['ID'],
											$row_rsProductVars['ID'],
											$row_rsVS['ID'],
											$row_as['ID']);
							$rs_as_d = mysql_query($q_as_d,$dbGlobal);
							$row_as_d = mysql_fetch_assoc($rs_as_d);
							$as_cell = number_format($row_as_d['Quantity'],0,'.',',');
							if($as_cell == '0') { $as_cell = "&nbsp;"; }
							echo "\t\t<td width='$vsarea_width' valign='middle' align='right'>$as_cell</td>\n";				
						}; // end while level (3)
					} // end while level (2)
				}				
					echo "\n\t<td valign='middle' align='right'>&nbsp;</td>";
					echo "\n\t<td valign='middle' align='right'>$dr_netamt</td>";
					echo "\n\t<td valign='middle' align='center'>&nbsp;</td>";					
					$total_Astocks++;
	?>
  </tr>
	<?php
////////////////////////////////////////////
	$ctr++;
	} 
////////////////////////////////////////////
	?>
  <tr height='21' class="stkbalcolor">
	<?php
	//STOCK BALANCE
	$w_rowctr++;
  	// from this point on, width is already set at vsizes column.
  	//echo "\n\t<td valign='middle' align='center'>4/10</td>";
    //echo "\n\t<td valign='middle' align='center'>MS1065</td>";
    echo "\n\t<td colspan= 3 valign='middle' align='center'>STOCK BALANCE</td>\n";
	$offset = 3;
  	$i = 0 + $offset;
	$row_rsProducts = mysql_data_seek($rsProducts, 0);
	$row_rsProductVars = mysql_data_seek($rsProductVars,0);
	while($row_rsProducts = mysql_fetch_assoc($rsProducts)) {
		$query_rsProductVars = 
				sprintf("SELECT * FROM productvars WHERE ProductID=%d", $row_rsProducts['ID']);
		$rsProductVars = mysql_query($query_rsProductVars, $dbGlobal);
		/*
		echo "<!--";
		echo $row_rsProducts['Name'];
		echo "-->";
		*/
		while($row_rsProductVars = mysql_fetch_assoc($rsProductVars)) {	//while level (2)
			/*
			echo "<!--";
			echo $row_rsProductVars['VariantName'];
			echo "-->";
			*/
			mysql_free_result($rsVS);
			$query_rsVS = 
			sprintf("
					SELECT 
						*, (CAST(`Varsize` AS CHAR) | CAST('axaa' AS CHAR)) as xVS 
					FROM varsizes 
					WHERE PVarID=%d 
					ORDER BY xVS", $row_rsProductVars['ID']);
			$rsVS = mysql_query($query_rsVS, $dbGlobal);
			$totalRows_rsVS = mysql_num_rows($rsVS);
			if($totalRows_rsVS == 0 ) { $totalRows_rsVS = 1; }
			//TODO: get from DR
			while($row_rsVS = mysql_fetch_assoc($rsVS)) { // while level (3)				
				$pCurrentVSName = sprintf("%d.%d.%d", 
					$row_rsProducts['ID'], $row_rsProductVars['ID'], $row_rsVS['ID']);
				//TODO: get from DR
				$col = "";
				if( ($i/26) >= 1) { $col .= chr(64 + floor($i/26)); }
				$col .= chr( 65+($i%26) );
				$row_begin = 7; //const!
				$row_end = $row_begin + $total_Astocks;
	  			echo "\t\t<td width='$vsarea_width' valign='middle' align='right'><b>=SUM($col$row_begin:$col$row_end)</b></td>\n";
				$i++;			
			}; // end while level (3)
		} // end while level (2)
	}
		$row++;
		$col = "";
		if( ($i/26) >= 1) { $col .= chr(64 + floor($i/26)); }
		$col .= chr( 65+($i%26) );
		//echo "\n\t<td valign='middle' align='right'>&nbsp;</td>";
		$i++;
		$col = "";
		if( ($i/26) >= 1) { $col .= chr(64 + floor($i/26)); }
		$col .= chr( 65+($i%26) );
		echo "\n\t<td valign='middle' align='right'colspan=2>=SUM($col$row_begin:$col$row_end)</td>";
		echo "\n\t<td valign='middle' align='center'>&nbsp;</td>";
	?>
	</tr>
	<?php
////////////////////////////////////////////
	$ctr = 0;
	$q_dr = sprintf("
				SELECT * FROM `dr` 
				WHERE 
					(`AcctType`!=4 AND `AcctType`<8 )
						AND 
					`ProdGroup`=%d", 
				$prodgroup) . 
				$date_condition_sql . 
				" ORDER BY `DRDate` ASC
				"; //4 - AS
	$rs_dr = mysql_query($q_dr,$dbGlobal) or die(mysql_error());
	while(($row_dr = mysql_fetch_assoc($rs_dr)) || ($w_rowctr < $w_maxrows))
	{
		$dr_date = $row_dr['DRDate'];
		$dr_number = $row_dr['DRNumber'];
		$dr_acctname = $row_dr['AccountName'];
		
		$dr_amt = number_format($row_dr['DRAmount'],2,'.',',');
		$dr_netamt = number_format($row_dr['DRNetAmount'],2,'.',',');
////////////////////////////////////////////
	?>
  <tr height='21' class="drentcolor">
	<?php
				//DR!!
				// from this point on, width is already set at vsizes column.
				$w_rowctr++;
				
				// Check empty for jesus' display sakes.
				if(empty($dr_date)) { $dr_date = "&nbsp;"; }
				if(empty($dr_number)) { $dr_number = "&nbsp;"; }
				if(empty($dr_acctname)) { $dr_acctname = "&nbsp;"; }
				if(empty($dr_amt)) { $dr_amt = "&nbsp;"; }
				if(empty($dr_netamt)) { $dr_netamt = "&nbsp;"; }
				
				echo "\n\t<td valign='middle' align='center'>$dr_date</td>";
				echo "\n\t<td valign='middle' align='center'>$dr_number</td>";
				echo "\n\t<td valign='middle' align='center'>$dr_acctname</td>\n";
				$offset = 3;
				$i = 0 + $offset;
				$row_rsProducts = mysql_data_seek($rsProducts, 0);
				$row_rsProductVars = mysql_data_seek($rsProductVars,0);
				while($row_rsProducts = mysql_fetch_assoc($rsProducts)) {
					$query_rsProductVars = 
							sprintf("SELECT * FROM productvars WHERE ProductID=%d", $row_rsProducts['ID']);
					$rsProductVars = mysql_query($query_rsProductVars, $dbGlobal);
					while($row_rsProductVars = mysql_fetch_assoc($rsProductVars)) {	//while level (2)
						mysql_free_result($rsVS);
						$query_rsVS = 
						sprintf("
							SELECT 
								*, (CAST(`Varsize` AS CHAR) | CAST('axaa' AS CHAR)) as xVS 
							FROM varsizes 
							WHERE PVarID=%d 
							ORDER BY xVS", $row_rsProductVars['ID']);
						$rsVS = mysql_query($query_rsVS, $dbGlobal);
						$totalRows_rsVS = mysql_num_rows($rsVS);
						if($totalRows_rsVS == 0 ) { $totalRows_rsVS = 1; }
						
						while($row_rsVS = mysql_fetch_assoc($rsVS)) { // while level (3)				
							$pCurrentVSName = sprintf("%d.%d.%d", 
								$row_rsProducts['ID'], $row_rsProductVars['ID'], $row_rsVS['ID']);
							
							$q_dr_d = sprintf("SELECT * FROM `drdetails` 
										WHERE 
											PID=%d AND 
											PVID=%d AND 
											VS=%d AND 
											DRID=%d",
											$row_rsProducts['ID'],
											$row_rsProductVars['ID'],
											$row_rsVS['ID'],
											$row_dr['ID']);
							
							$rs_dr_d = mysql_query($q_dr_d,$dbGlobal);
							$row_dr_d = mysql_fetch_assoc($rs_dr_d);
							$dr_cell = number_format($row_dr_d['Quantity'],0,'.',',');
							if($dr_cell == '0') { $dr_cell = "&nbsp;"; }
							echo "\t\t<td width='$vsarea_width' valign='middle' align='right'>$dr_cell</td>\n";
							$i++;			
						}; // end while level (3)
					} // end while level (2)
				}
					$col = "";
					if( ($i/26) >= 1) { $col .= chr(64 + floor($i/26)); }
					$col .= chr( 64+($i%26) );
					$row = 9 + $dr_count;
					echo "\n\t<td valign='middle' align='right'>$dr_amt</td>";
					echo "\n\t<td valign='middle' align='right'>$dr_netamt</td>";
					echo "\n\t<td valign='middle' align='center'>&nbsp;</td>";
					$dr_count++;
	?>
	</tr>
	<?php
////////////////////////////////////////////
	$ctr++;
	}
////////////////////////////////////////////
	?>
  <tr height='21' class="offtkecolor">
	<?php
	//TOTAL BALANCE
  	// from this point on, width is already set at vsizes column.
  	//echo "\n\t<td valign='middle' align='center'>&nbsp;</td>";
    //echo "\n\t<td valign='middle' align='center'>&nbsp;</td>";
    echo "\n\t<td colspan=3 valign='middle' align='center'>OFFTAKE</td>\n";
	$offset = 3;
  	$i = 0 + $offset;
	$row_rsProducts = mysql_data_seek($rsProducts, 0);
	$row_rsProductVars = mysql_data_seek($rsProductVars,0);
	while($row_rsProducts = mysql_fetch_assoc($rsProducts)) {
		$query_rsProductVars = 
				sprintf("SELECT * FROM productvars WHERE ProductID=%d", $row_rsProducts['ID']);
		$rsProductVars = mysql_query($query_rsProductVars, $dbGlobal);
		
		while($row_rsProductVars = mysql_fetch_assoc($rsProductVars)) {	//while level (2)
			
			mysql_free_result($rsVS);
			$query_rsVS = 
			sprintf("
					SELECT 
						*, (CAST(`Varsize` AS CHAR) | CAST('axaa' AS CHAR)) as xVS 
					FROM varsizes 
					WHERE PVarID=%d 
					ORDER BY xVS", $row_rsProductVars['ID']);
			$rsVS = mysql_query($query_rsVS, $dbGlobal);
			$totalRows_rsVS = mysql_num_rows($rsVS);
			if($totalRows_rsVS == 0 ) { $totalRows_rsVS = 1; }
			
			while($row_rsVS = mysql_fetch_assoc($rsVS)) { // while level (3)				
				$pCurrentVSName = sprintf("%d.%d.%d", 
					$row_rsProducts['ID'], $row_rsProductVars['ID'], $row_rsVS['ID']);
				// lots of hacks here. 7 is the const
				$col = "";
				if( ($i/26) >= 1) { $col .= chr(64 + floor($i/26)); }
				$col .= chr( 65+($i%26) );
				$row_sb = 7 + $total_Astocks + 1;
				$row_begin = 7 + $total_Astocks + 2; //const!
				$row_end = $row_sb + $dr_count; 
				// ^^ 2 represents the st.bal row and the total bal row itself.
	  			echo "\t\t
					<td width='$vsarea_width' valign='middle' align='right'>
						<b>
						=SUM($col$row_begin:$col$row_end)
						</b>
					</td>\n";
				$i++;			
			}; // end while level (3)
		} // end while level (2)
	}
		$col = "";
		if( ($i/26) >= 1) { $col .= chr(64 + floor($i/26)); }
		$col .= chr( 65+($i%26) );
		$row++;
		echo "\n\t
		<td valign='middle' align='right'>
			<b>
			=SUM($col$row_begin:$col$row_end)
			</b>
		</td>";
		$i++;
		$col = "";
		if( ($i/26) >= 1) { $col .= chr(64 + floor($i/26)); }
		$col .= chr( 65+($i%26) );
		echo "\n\t
			<td valign='middle' align='right'>
			<b>
			=SUM($col$row_begin:$col$row_end)
			</b>
			</td>";
		echo "\n\t<td valign='middle' align='center'>&nbsp;</td>";
	?>
  </tr>

  <tr height='21' class="netbalcolor">
	<?php
	//TOTAL BALANCE
  	// from this point on, width is already set at vsizes column.
  	//echo "\n\t<td valign='middle' align='center'>&nbsp;</td>";
    //echo "\n\t<td valign='middle' align='center'>&nbsp;</td>";
    echo "\n\t<td colspan=3 valign='middle' align='center'>NET STOCK BALANCE</td>\n";
	$offset = 3;
  	$i = 0 + $offset;
	$row_rsProducts = mysql_data_seek($rsProducts, 0);
	$row_rsProductVars = mysql_data_seek($rsProductVars,0);
	while($row_rsProducts = mysql_fetch_assoc($rsProducts)) {
		$query_rsProductVars = 
				sprintf("SELECT * FROM productvars WHERE ProductID=%d", $row_rsProducts['ID']);
		$rsProductVars = mysql_query($query_rsProductVars, $dbGlobal);
		
		while($row_rsProductVars = mysql_fetch_assoc($rsProductVars)) {	//while level (2)
			
			mysql_free_result($rsVS);
			$query_rsVS = 
			sprintf("
					SELECT 
						*, (CAST(`Varsize` AS CHAR) | CAST('axaa' AS CHAR)) as xVS 
					FROM varsizes 
					WHERE PVarID=%d 
					ORDER BY xVS", $row_rsProductVars['ID']);
			$rsVS = mysql_query($query_rsVS, $dbGlobal);
			$totalRows_rsVS = mysql_num_rows($rsVS);
			if($totalRows_rsVS == 0 ) { $totalRows_rsVS = 1; }
			
			while($row_rsVS = mysql_fetch_assoc($rsVS)) { // while level (3)				
				$pCurrentVSName = sprintf("%d.%d.%d", 
					$row_rsProducts['ID'], $row_rsProductVars['ID'], $row_rsVS['ID']);
				// lots of hacks here. 7 is the const
				$col = "";
				if( ($i/26) >= 1) { $col .= chr(64 + floor($i/26)); }
				$col .= chr( 65+($i%26) );
				$row_sb = 7 + $total_Astocks + 1;
				$row_begin = 7 + $total_Astocks + 2; //const!
				$row_end = $row_sb + $dr_count; 
				// ^^ 2 represents the st.bal row and the total bal row itself.
	  			echo "\t\t
					<td width='$vsarea_width' valign='middle' align='right'>
						<b>
						=$col$row_sb-SUM($col$row_begin:$col$row_end)
						</b>
					</td>\n";
				$i++;			
			}; // end while level (3)
		} // end while level (2)
	}
		$col = "";
		if( ($i/26) >= 1) { $col .= chr(64 + floor($i/26)); }
		$col .= chr( 65+($i%26) );
		$row++;
		
		echo "\n\t
		<td valign='middle' align='right' colspan=2>
			<b>
			=$col$row_sb-SUM(";
		
		$i++;
		$col = "";
		if( ($i/26) >= 1) { $col .= chr(64 + floor($i/26)); }
		$col .= chr( 65+($i%26) );
		echo "$col$row_begin:$col$row_end)
			</b>
			</td>";
		echo "\n\t<td valign='middle' align='center'>&nbsp;</td>";
	?>
  </tr>
</table>
</body>
</html>
