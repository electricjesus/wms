<?php 

///*
header("Content-Type:application/vnd.ms-excel")
//header("Content-Disposition: attachment; filename=Wisc001.xlsx"); 
/**/

?>

<html>
<head>
<title>WISCOR Pilot page</title>
<style type="text/css">
* { padding:0; margin:0; }

body {	color: #666666; background-color:#FFFFFF; font-family: "Calibri", Courier, monospace; 	font-size:83%; }
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
</style>
<?php require_once('Connections/dbGlobal.php'); ?>
<?php
	mysql_select_db($database_dbGlobal, $dbGlobal);
?>
</head>
<body>
<center>
  <h1>Weekly Inventory Sales & Control Report</h1>
</center>
Distributor: Seth D. Malaki <?php echo "\t"; ?> Area: Roxas City
<table width=100% border='1' cellpadding='0' cellspacing='0'>
  <tr height='21' >
    <td width='65' rowspan='3' valign='middle' align='center'>DR Date</td>
    <td width='65' rowspan='3' valign='middle' align='center'>DR Number</td>
    <td width='140' rowspan='3' valign='middle' align='center'>CUSTOMER</td>
	<?php
	// START PRODUCTS
		if(!isset($prodgroup))
			$query_rsProducts = sprintf("SELECT * FROM products");
		else
			$query_rsProducts = sprintf("SELECT * FROM products where ProdGroup=%d",$prodgroup);
		$rsProducts = mysql_query($query_rsProducts, $dbGlobal);
	?>
	<?php 	while( $row_rsProducts = mysql_fetch_assoc($rsProducts) ) { //while level (1)
			
			
			$query_rsProductVars = 
				sprintf("SELECT * FROM productvars WHERE ProductID=%d", $row_rsProducts['ID']);
			$query_rsVS = 
				sprintf("SELECT * FROM varsizes WHERE PID=%d", $row_rsProducts['ID']);
			$rsProductVars = mysql_query($query_rsProductVars, $dbGlobal);
			$rsVS = mysql_query($query_rsVS, $dbGlobal);
			$totalRows_rsVS = mysql_num_rows($rsVS);			
			if($totalRows_rsVS == 0 ) { $totalRows_rsVS = 1; }
			$pCurrentProdName = $row_rsProducts['Name'];
    		echo "
				<td valign='middle' align='center' colspan='$totalRows_rsVS' >
					$pCurrentProdName
				</td>
				";
		}; // end while level (1)
	?>
		<td width='70' rowspan='3' valign='middle' align='center'>Landed Cost</td>
		<td width='70' rowspan='3' valign='middle' align='center'>Selling Price</td>
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
				echo "\t\t<td align='middle' colspan='$totalRows_rsVS'>$pCurrentPVName</td>\n";
			}; // end while level (2)
		}
	?>
	  </tr>
	  <tr height='21'>
  <?php

  
  //don't forget to factor in the 'width' values.
  // 'base width' is proposed to be '200px'. FIX: setstate solid 50
  	$vsarea_width = 40;
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
			sprintf("SELECT * FROM varsizes WHERE PVarID=%d", $row_rsProductVars['ID']);
			$rsVS = mysql_query($query_rsVS, $dbGlobal);
			$totalRows_rsVS = mysql_num_rows($rsVS);
			if($totalRows_rsVS == 0 ) { $totalRows_rsVS = 1; }
			while($row_rsVS = mysql_fetch_assoc($rsVS)) { // while level (3)				
				$pCurrentVSName = $row_rsVS['VarSize'];
	  			echo "\t\t<td width='$vsarea_width' valign='middle' align='center'>$pCurrentVSName</td>\n";
			}; // end while level (3)
		} // end while level (2)
	}
	?>
  </tr>
  <tr height='21'>
  <?php
	//FWDED BAL
  	// from this point on, width is already set at vsizes column.
  	echo "\n\t<td valign='middle' align='center'>WDATE</td>";
    echo "\n\t<td valign='middle' align='center'>WPAGE65</td>";
    echo "\n\t<td valign='middle' align='center'>BALANCE FORWARDED</td>\n";
  

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
			sprintf("SELECT * FROM varsizes WHERE PVarID=%d", $row_rsProductVars['ID']);
			$rsVS = mysql_query($query_rsVS, $dbGlobal);
			$totalRows_rsVS = mysql_num_rows($rsVS);
			if($totalRows_rsVS == 0 ) { $totalRows_rsVS = 1; }
			//TODO: get from DR
			while($row_rsVS = mysql_fetch_assoc($rsVS)) { // while level (3)				
				$pCurrentVSName = sprintf("%d.%d.%d", 
					$row_rsProducts['ID'], $row_rsProductVars['ID'], $row_rsVS['ID']);
				//TODO: get from DR
				$fb_cell = rand(100, 200);
	  			echo "\t\t<td width='$vsarea_width' valign='middle' align='right'>$fb_cell</td>\n";				
			}; // end while level (3)
		} // end while level (2)
	}
	
		echo "\n\t<td valign='middle' align='right'>0.00</td>";
		echo "\n\t<td valign='middle' align='right'>N/A</td>";
		echo "\n\t<td valign='middle' align='center'>&nbsp;</td>";
	?>
  </tr>
	<?php
////////////////////////////////////////////
	$ctr = 0;
	$dr_count = 0;
	$total_Astocks = 0;
	do {
////////////////////////////////////////////
	?>
  <tr height='21'>
	<?php
				//ARRIVED STOCKS
				// from this point on, width is already set at vsizes column.
				echo "\n\t<td valign='middle' align='center'>4/10</td>";
				echo "\n\t<td valign='middle' align='center'>MS1065</td>";
				echo "\n\t<td valign='middle' align='center'>ARRIVED STOCKS</td>\n";	
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
						sprintf("SELECT * FROM varsizes WHERE PVarID=%d", $row_rsProductVars['ID']);
						$rsVS = mysql_query($query_rsVS, $dbGlobal);
						$totalRows_rsVS = mysql_num_rows($rsVS);
						if($totalRows_rsVS == 0 ) { $totalRows_rsVS = 1; }
						//TODO: get from DR
						
						while($row_rsVS = mysql_fetch_assoc($rsVS)) { // while level (3)				
							$pCurrentVSName = sprintf("%d.%d.%d", 
								$row_rsProducts['ID'], $row_rsProductVars['ID'], $row_rsVS['ID']);
							//TODO: get from DR
							$as_cell = rand(50, 99);
							echo "\t\t<td width='$vsarea_width' valign='middle' align='right'>$as_cell</td>\n";				
						}; // end while level (3)
					} // end while level (2)
				}
				
					echo "\n\t<td valign='middle' align='right'>0.00</td>";
					echo "\n\t<td valign='middle' align='right'>N/A</td>";
					echo "\n\t<td valign='middle' align='center'>&nbsp;</td>";
					
					$total_Astocks++;
	?>
  </tr>
	<?php
////////////////////////////////////////////
	$ctr++;
	} while( $ctr < 5 )
////////////////////////////////////////////
	?>
  <tr height='21'>
	<?php
	//STOCK BALANCE
  	// from this point on, width is already set at vsizes column.
  	echo "\n\t<td valign='middle' align='center'>4/10</td>";
    echo "\n\t<td valign='middle' align='center'>MS1065</td>";
    echo "\n\t<td valign='middle' align='center'>STOCK BALANCE</td>\n";
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
			sprintf("SELECT * FROM varsizes WHERE PVarID=%d", $row_rsProductVars['ID']);
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
				$row_begin = 6; //const!
				$row_end = $row_begin + $total_Astocks;
	  			echo "\t\t<td width='$vsarea_width' valign='middle' align='right'>=SUM($col$row_begin:$col$row_end)</td>\n";
				$i++;			
			}; // end while level (3)
		} // end while level (2)
	}
		$row++;
		$col = "";
		if( ($i/26) >= 1) { $col .= chr(64 + floor($i/26)); }
		$col .= chr( 65+($i%26) );
		echo "\n\t<td valign='middle' align='right'>=SUM($col$row_begin:$col$row_end)</td>";
		echo "\n\t<td valign='middle' align='right'>N/A</td>";
		echo "\n\t<td valign='middle' align='center'>&nbsp;</td>";
	?>
	</tr>
	<?php
////////////////////////////////////////////
	$ctr = 0;
	do {
////////////////////////////////////////////
	?>
  <tr height='21'>
	<?php
				//DR!!
				// from this point on, width is already set at vsizes column.
				
				echo "\n\t<td valign='middle' align='center'>4/10</td>";
				echo "\n\t<td valign='middle' align='center'>DR0608</td>";
				echo "\n\t<td valign='middle' align='center'>Customer Name</td>\n";
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
						sprintf("SELECT * FROM varsizes WHERE PVarID=%d", $row_rsProductVars['ID']);
						$rsVS = mysql_query($query_rsVS, $dbGlobal);
						$totalRows_rsVS = mysql_num_rows($rsVS);
						if($totalRows_rsVS == 0 ) { $totalRows_rsVS = 1; }
						//TODO: get from DR
						while($row_rsVS = mysql_fetch_assoc($rsVS)) { // while level (3)				
							$pCurrentVSName = sprintf("%d.%d.%d", 
								$row_rsProducts['ID'], $row_rsProductVars['ID'], $row_rsVS['ID']);
							//TODO: get from DR
							$dr_cell = rand(10, 30);
							echo "\t\t<td width='$vsarea_width' valign='middle' align='right'>$dr_cell</td>\n";
							$i++;			
						}; // end while level (3)
					} // end while level (2)
				}
					$col = "";
					if( ($i/26) >= 1) { $col .= chr(64 + floor($i/26)); }
					$col .= chr( 64+($i%26) );
					$row = 9 + $dr_count;
					echo "\n\t<td valign='middle' align='right'>500.00</td>";
					echo "\n\t<td valign='middle' align='right'>N/A</td>";
					echo "\n\t<td valign='middle' align='center'>&nbsp;</td>";
					$dr_count++;
	?>
	</tr>
	<?php
////////////////////////////////////////////
	$ctr++;
	} while( $ctr < 5 )
////////////////////////////////////////////
	?>
  <tr height='21'>
	<?php
	//TOTAL BALANCE
  	// from this point on, width is already set at vsizes column.
  	echo "\n\t<td valign='middle' align='center'>&nbsp;</td>";
    echo "\n\t<td valign='middle' align='center'>&nbsp;</td>";
    echo "\n\t<td valign='middle' align='center'>TOTAL BALANCE</td>\n";
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
			sprintf("SELECT * FROM varsizes WHERE PVarID=%d", $row_rsProductVars['ID']);
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
				$row_sb = 6 + $total_Astocks + 1;
				$row_begin = 6 + $total_Astocks + 2; //const!
				$row_end = $row_sb + $dr_count; 
				// ^^ 2 represents the st.bal row and the total bal row itself.
	  			echo "\t\t<td width='$vsarea_width' valign='middle' align='right'>=$col$row_sb-SUM($col$row_begin:$col$row_end)</td>\n";
				$i++;			
			}; // end while level (3)
		} // end while level (2)
	}
		$col = "";
		if( ($i/26) >= 1) { $col .= chr(64 + floor($i/26)); }
		$col .= chr( 65+($i%26) );
		$row++;
		echo "\n\t<td valign='middle' align='right'>=$col$row_sb-SUM($col$row_begin:$col$row_end)</td>";
		echo "\n\t<td valign='middle' align='right'>N/A</td>";
		echo "\n\t<td valign='middle' align='center'>&nbsp;</td>";
	?>
  </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
