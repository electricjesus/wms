<?php
	require_once('Connections/dbGlobal.php'); 
	mysql_select_db($database_dbGlobal, $dbGlobal);
			
			
			$query_articles_join = 
			sprintf(
			"
				SELECT
				CONCAT(
				PRODUCTS.ID,'/',
				PRODUCTVARS.ID, '/',
				VARSIZES.ID) AS IDSet,
			
				CONCAT(
				PRODUCTS.Name, ' ',
				PRODUCTVARS.VariantName, ' ',
				VARSIZES.Varsize) AS Name,
				
				PRODUCTVARS.VariantName AS SubName,
				
				CONCAT(
				VARSIZES.PNL,'/',
				VARSIZES.BKG,'/',
				VARSIZES.PBKG,'/',
				VARSIZES.VATBKG,'/',
				VARSIZES.VATPBKG,'/',
				VARSIZES.LC ) AS InfoSet,
			
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
			ORDER BY Products.Name ASC, VariantName ASC, xVS ASC;

			", $prodcat);
			
			$rsProducts = mysql_query($query_articles_join, $dbGlobal);
			$totalRows_rsProducts = mysql_num_rows($rsProducts);
	
	$input = strtolower( $_GET['input'] );
	$len = strlen($input);
	
	
	$aResults = array();
	
	if ($len)
	{
		while($row = mysql_fetch_assoc($rsProducts))
		{
			if (
				(strtolower(substr(utf8_decode($row['Name']),0,$len)) == $input) ||
				(strtolower(substr(utf8_decode($row['SubName']),0,$len)) == $input)
			)
			{
				list($pnl,$bkg,$pbkg,$vbkg,$vpbkg,$lc) = split("/",$row['InfoSet']);
				$landed = $lc;
				switch($accttype)
				{
					case 1:					$price = $pnl;	break;
					case 2:					$price = $bkg;	break;
					case 3:					$price = $pbkg;	break;
					case 4:					$price = $lc;	break;
					case 5:					$price = $vbkg;	break;
					case 6:					$price = $vpbkg;break;
					case 7:					$price = $lc;	break;
					case 9:
					case 10:				$price = $pnl;	break;
					default:				$price = $lc;	break;
				}
				
				$infoset = "Price: P " . ($price) . " , Landed: P " . ($landed);				
				$aResults[] = array( 
					"id"=>$row['IDSet'] ,
					"value"=>htmlspecialchars($row['Name']), 
					"info"=>htmlspecialchars($infoset)					
					);
			}
		}
	}
	
	header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
	header ("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header ("Pragma: no-cache"); // HTTP/1.0	
	
	if (isset($_REQUEST['json']))
	{
		header("Content-Type: application/json");
	
		echo "{\"results\": [";
		$arr = array();
		for ($i=0;$i<count($aResults);$i++)
		{
			$arr[] = "
					{
					 	\"id\": \"". $aResults[$i]['id'] ."\", 
						\"value\": \"".$aResults[$i]['value'] . "\",
						\"info\": \"".$aResults[$i]['info'] . "\"
					}
					 ";
		}
		echo implode(", ", $arr);
		echo "]}";
	}
	else
	{
		header("Content-Type: text/xml");

		echo "<?xml version=\"1.0\" encoding=\"utf-8\" ?><results>";
		for ($i=0;$i<count($aResults);$i++)
		{
			echo "<rs id=\"".$aResults[$i]['id']."\" info=\"".$aResults[$i]['info']."\">".$aResults[$i]['value']."</rs>";
		}
		echo "</results>";
	}
?>