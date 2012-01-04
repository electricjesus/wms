<?php
	require_once('Connections/dbGlobal.php'); 
	mysql_select_db($database_dbGlobal, $dbGlobal);
	$accttypestr_a = array('Panel','City Bkg','Prov Bkg','Arvd Stocks','City Bkg + VAT','Prov Bkg + VAT','Tie-up');
			
			$query_accounts = 
			sprintf( "
				SELECT
				  AccountName,
				  Address,
				  AcctType,
				  Terms,
				  Count(*) as TotalTrans,
				  SUM(DRAmount) as TotalTransA,
				  SUM(DRNetAmount) as TotalTransNetA
				
				FROM `dr`
				GROUP BY AccountName
				ORDER BY AccountName ASC
			");
			
			$rsAccounts = mysql_query($query_accounts, $dbGlobal);
				
	$input = strtolower( $_GET['input'] );
	$len = strlen($input);
	
	
	$aResults = array();
	
	if ($len)
	{
		while($row = mysql_fetch_assoc($rsAccounts))
		{
			if (strtolower(substr(utf8_decode($row['AccountName']),0,$len)) == $input)
			{
				$aResults[] = array( 
					"id"=>$row['AcctType'] ,
					"terms"=>htmlspecialchars($row['Terms']),
					"value"=>htmlspecialchars($row['AccountName']), 					
					"info"=>htmlspecialchars($accttypestr_a[$row['AcctType']-1])
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
					 	\"id\": ". $aResults[$i]['id'] .", 
						\"terms\": ". $aResults[$i]['terms'] .", 
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