<?php require_once('Connections/dbGlobal.php'); ?>
<?php
	mysql_select_db($database_dbGlobal, $dbGlobal);
?>
<html>
<head>
<title>Untitled Document</title>
</head>
<table>
<?php
			$prodcat = 1;
			$query_articles_join = 
			sprintf(
			"
				SELECT 
					PRODUCTS.ID AS ProductID, 
					PRODUCTS.Name AS ProductName, 
				
					PRODUCTVARS.ID AS ProductVarID, 
					PRODUCTVARS.VariantName AS VariantName,
				 
					VARSIZES.ID AS VarSizeID, 
					VARSIZES.*
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
				
				WHERE 1 
				ORDER BY 
					PRODUCTS.ProdGroup ASC, 
					PRODUCTS.Name ASC, 
					PRODUCTVARS.VariantName, 
					VARSIZES.VarSize ASC ;
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
				$xprice_panel = number_format($rowArticles['PNL'], 2, '.', '');
				$xprice_booking = number_format($rowArticles['BKG'], 2, '.', '');
				$xprice_pbooking = number_format($rowArticles['PBKG'], 2, '.', '');				
				$xprice_landed = number_format($rowArticles['LC'], 2, '.', '');
				echo "<tr>";
				echo "<td>".$xpname . " " . $xpvname . "</td><td>" . strtolower($xvsname) ."</td>";
				echo "</tr>";
			}
?>
</table>
<body>
</body>
</html>
