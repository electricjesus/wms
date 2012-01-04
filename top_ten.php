<?php 
require_once('Connections/dbGlobal.php'); 
?>
<?php


	//RXS
	//control vars
	$month = 3;
	$year = 2008;
	$next_month = 2;
	$next_year = 2008;
	
	$varprodgrp = 2;
	$vardrdateyr = $year; 	//root year
	$vardrdatemo = $month;		//root mo
	//fixed
	$varactype_1 = 2;
	$varactype_2 = 5;
	$varlimitsqlres = 7;
	$topten_sql = sprintf("
							SELECT 
								`ProdGroup`,
								`AccountName`,
								SUM(`DRAmount`) as Amount,
								YEAR(`DRDate`), MONTH(`DRDate`)
							FROM `dr`
								WHERE `ProdGroup` = %d AND
								YEAR(`DRDate`) = %d AND MONTH(`DRDate`) = %d AND
								(`AcctType` = %d OR `AcctType` = %d)
							GROUP BY
								`AccountName`
							ORDER BY
								`Amount` DESC
							LIMIT %d;", 
								$varprodgrp, 
								$vardrdateyr, 
								$vardrdatemo, 
								$varactype_1,
								$varactype_2,
								$varlimitsqlres);
	$varprodgrp = 1;


	$rstop10 = mysql_query($topten_sql, $dbGlobal) or die( mysql_error());
	
	while ($rowtop = mysql_fetch_assoc($rstop10)) {
	$varacctname = $rowtop['AccountName'];
	if(substr(0,5,$varacctname) == "GOODY")
		$varacctname = "GOODY";
	$followup_sql = sprintf("SELECT SUM(`DRAmount`) as Amount
							FROM `DR` 
							WHERE 
								`AccountName` = '%s' AND 
								`ProdGroup` = %d AND 
								YEAR(`DRDate`) = %d AND MONTH(`DRDate`) = %d
							LIMIT 1;",
								$varacctname, $varprodgrp, $vardrdateyr, $vardrdatemo);
	$vardrdateyr = $next_year;
	$vardrdatemo = $next_month;
	$varprodgrp = 2;
	printf("<pre>	$followup_sql<br>$followup_sql2<br>$followup_sql4</pre>");
	$followup_sql2 = sprintf("SELECT SUM(`DRAmount`) as Amount
							FROM `DR` 
							WHERE 
								`AccountName` = '%s' AND 
								`ProdGroup` = %d AND 
								YEAR(`DRDate`) = %d AND MONTH(`DRDate`) = %d
							LIMIT 1;",
								$varacctname, $varprodgrp, $vardrdateyr, $vardrdatemo);
	$varprodgrp = 1;
	$followup_sql4 = sprintf("SELECT SUM(`DRAmount`) as Amount
							FROM `DR` 
							WHERE 
								`AccountName` = '%s' AND 
								`ProdGroup` = %d AND 
								YEAR(`DRDate`) = %d AND MONTH(`DRDate`) = %d
							LIMIT 1;",
								$varacctname, $varprodgrp, $vardrdateyr, $vardrdatemo);
	$rsbot = mysql_query($followup_sql, $dbGlobal) or die( mysql_error() . $followup_sql);
	$rowbot = mysql_fetch_assoc($rsbot);
	$rsleft = mysql_query($followup_sql2, $dbGlobal) or die( mysql_error() . $followup_sql);
	$rsright = mysql_query($followup_sql4, $dbGlobal) or die( mysql_error() . $followup_sql);
	$rowleft = mysql_fetch_assoc($rsleft);
	$rowright = mysql_fetch_assoc($rsright); 
	echo $rowtop['AccountName'] . ",";
	echo $rowtop['Amount'] . ",";
	echo $rowbot['Amount'] . ",";
	echo $rowtop['Amount'] + $rowbot['Amount'] . ",";
	echo $rowleft['Amount'] . ",";
	echo $rowright['Amount'] . "," ;
	echo $rowleft['Amount'] + $rowright['Amount'] . ",";
	echo $rowtop['Amount'] + $rowbot['Amount'] + $rowleft['Amount'] + $rowright['Amount'];
	echo '<br />';

	}
	
	echo "<br /><br />";
							
	$vardrdateyr = $year; 	//root year
	$vardrdatemo = $month;		//root mo
	$varactype_1 = 3;
	$varactype_2 = 6;	
	
	$topten_sql = sprintf("
							SELECT 
								`ProdGroup`,
								`AccountName`,
								SUM(`DRAmount`) as Amount,
								YEAR(`DRDate`), MONTH(`DRDate`)
							FROM `dr`
								WHERE `ProdGroup` = %d AND
								YEAR(`DRDate`) = %d AND MONTH(`DRDate`) = %d AND
								(`AcctType` = %d OR `AcctType` = %d)
							GROUP BY
								`AccountName`
							ORDER BY
								`Amount` DESC
							LIMIT %d;", 
								$varprodgrp, 
								$vardrdateyr, 
								$vardrdatemo, 
								$varactype_1,
								$varactype_2,
								$varlimitsqlres);
	$varprodgrp = 1;


	$rstop10 = mysql_query($topten_sql, $dbGlobal) or die( mysql_error());
	
	while ($rowtop = mysql_fetch_assoc($rstop10)) {
	$varacctname = $rowtop['AccountName'];
	if(substr(0,5,$varacctname) == "GOODY")
		$varacctname = "GOODY";
	$followup_sql = sprintf("SELECT SUM(`DRAmount`) as Amount
							FROM `DR` 
							WHERE 
								`AccountName` = '%s' AND 
								`ProdGroup` = %d AND 
								YEAR(`DRDate`) = %d AND MONTH(`DRDate`) = %d
							LIMIT 1;",
								$varacctname, $varprodgrp, $vardrdateyr, $vardrdatemo);
	$vardrdateyr = $next_year;
	$vardrdatemo = $next_month;
	$varprodgrp = 2;
	$followup_sql2 = sprintf("SELECT SUM(`DRAmount`) as Amount
							FROM `DR` 
							WHERE 
								`AccountName` = '%s' AND 
								`ProdGroup` = %d AND 
								YEAR(`DRDate`) = %d AND MONTH(`DRDate`) = %d
							LIMIT 1;",
								$varacctname, $varprodgrp, $vardrdateyr, $vardrdatemo);
	$varprodgrp = 1;
	$followup_sql4 = sprintf("SELECT SUM(`DRAmount`) as Amount
							FROM `DR` 
							WHERE 
								`AccountName` = '%s' AND 
								`ProdGroup` = %d AND 
								YEAR(`DRDate`) = %d AND MONTH(`DRDate`) = %d
							LIMIT 1;",
								$varacctname, $varprodgrp, $vardrdateyr, $vardrdatemo);
	$rsbot = mysql_query($followup_sql, $dbGlobal) or die( mysql_error() . $followup_sql);
	$rowbot = mysql_fetch_assoc($rsbot);
	$rsleft = mysql_query($followup_sql2, $dbGlobal) or die( mysql_error() . $followup_sql);
	$rsright = mysql_query($followup_sql4, $dbGlobal) or die( mysql_error() . $followup_sql);
	$rowleft = mysql_fetch_assoc($rsleft);
	$rowright = mysql_fetch_assoc($rsright); 
	echo $rowtop['AccountName'] . ",";
	echo $rowtop['Amount'] . ",";
	echo $rowbot['Amount'] . ",";
	echo $rowtop['Amount'] + $rowbot['Amount'] . ",";
	echo $rowleft['Amount'] . ",";
	echo $rowright['Amount'] . "," ;
	echo $rowleft['Amount'] + $rowright['Amount'] . ",";
	echo $rowtop['Amount'] + $rowbot['Amount'] + $rowleft['Amount'] + $rowright['Amount'];
	echo '<br />';

	}
	
?>
