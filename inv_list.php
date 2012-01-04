<html>
<head>
<title>Untitled Document</title>
<link href='styles/style.css' rel='stylesheet' type='text/css'>
<link href="css/tabledisp.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php /*
inv_list GET structure:
		DATE FROM: 	
			$fm, $fd, $fy
		DATE TO:	
			$tm, $td, $ty
		OPT. CONDITIONS:
			$showall
			$dateoff
		REQUIRED:
			$prodgroup
		
		dr.php?		prodgroup=$prodgroup&fm=$fm&fd=$fd&fy=$fy&tm=$tm&td=$td&ty=$ty&showall=$showall&dateoff=$dateoff
*/ ?>
<?php 
require_once('Connections/dbGlobal.php'); 
?>
<?php
	$dr_select_sql = "SELECT * FROM `dr` ";
	$productline_cond_sql = sprintf("ProdGroup=%d", $prodgroup);
	$date_condition_sql = sprintf(" DRDate >= '%d-%d-%d' AND DRDate <= '%d-%d-%d' ",
													$fy, $fm, $fd, $ty, $tm, $td );
	
	switch($order)
	{
	case 1:
		$order_by_sql = " ORDER BY  DRDate ASC";
		break;
	case 2:
		$order_by_sql = " ORDER BY  DRNumber ASC";
		break;
	case 3:
		$order_by_sql = " ORDER BY  AccountName ASC";
		break;
	case 4:
		$order_by_sql = " ORDER BY  AcctType DESC";
		break;
	case 5:
		$order_by_sql = " ORDER BY  ProdGroup ASC";
		break;
	case 6:
		$order_by_sql = " ORDER BY  DRAmount DESC";
		break;
	default:
		$order_by_sql = " ORDER BY  DRDate ASC, DRNumber ASC, AcctType DESC, AccountName ASC";
		break;
	}
	$whereclause_added = false;
	
	$query_a = $dr_select_sql;
	if( $showall=='true')
	{
		if(!($dateoff =='true'))	
		{
			if(!$whereclause_added)
			{
				$whereclause_added = true;
				$query_a .= " WHERE (AcctType > 7) AND ";		
			}
			$query_a .= $date_condition_sql;
		}
	}
	else
	{
		if(!($dateoff =='true'))	
		{
			if(!$whereclause_added)
			{
				$whereclause_added = true;
				$query_a .= " WHERE (AcctType > 7) AND ";		
			}
			$query_a .= $date_condition_sql;
		}
		if(!$whereclause_added)
		{
				$whereclause_added = true;
				$query_a .= " WHERE (AcctType > 7) AND ";		
		}
		else
			$query_a .= " AND ";
		$query_a .= $productline_cond_sql;
	}
	
	$query_a .= $order_by_sql;
	if($force=='true')
		$query_a = "SELECT * FROM `dr` WHERE `AcctType` >= 8;";
	$rsDRS = mysql_query($query_a, $dbGlobal) or die(mysql_error());
	echo $query_a;	
	$totalRows_rsDRS = mysql_num_rows($rsDRS);
	$f_date = date("F j, Y", mktime(0,0,0,$fm,$fd,$fy));
	$t_date = date("F j, Y", mktime(0,0,0,$tm,$td,$ty));
	$pline_names = array("Oishi","Oishi 2");
	$pline_title = $pline_names[$prodgroup-1];
	
	$get_str = "prodgroup=$prodgroup&fm=$fm&fd=$fd&fy=$fy&tm=$tm&td=$td&ty=$ty&showall=$showall&dateoff=$dateoff";
?>
<center>
	<a href="inv_list.php?force=true"><strong>! FORCE ALL !</strong></a>
	
</center>
<table width='710' border='0' cellpadding='0' cellspacing='0'>
  <!--DWLayoutTable-->
  <tr>
    <td height='24' colspan='6' valign='top'>Existing DR's : Showing <strong>All<?php if($showall!='true') { echo " " . $pline_title; } ?></strong> Products <?php if($dateoff !='true') { echo "From <strong>$f_date To $t_date</strong>"; } ?></td>
  </tr>
  <tr valign='middle'>
    <td width='78' height='24' align='center' valign='top'>
		<strong><a href="inv_list.php?order=1&<?php echo $get_str; ?>">DR Date</a></strong> </td>
    <td width='92' align='center' valign='top'>
		<strong><a href="inv_list.php?order=2&<?php echo $get_str; ?>">DR Number</a></strong> </td>
    <td width='233' align='center' valign='top'>
		<strong><a href="inv_list.php?order=3&<?php echo $get_str; ?>">Transaction Title</a> </strong></td>
    <td width='66' align='center' valign='top'>
		<strong><a href="inv_list.php?order=4&<?php echo $get_str; ?>">Type</a></strong> </td>
    <td width='108' align='center' valign='top'>
		<strong><a href="inv_list.php?order=5&<?php echo $get_str; ?>">Product Line</a> </strong></td>
    <td width='133' align='center' valign='top'>
		<strong><a href="inv_list.php?order=6&<?php echo $get_str; ?>">DR Amount</a></strong> </td>
  </tr>
  <?php 
  $ctr = 0;
  
  while ($row_dr = mysql_fetch_assoc($rsDRS)) {
  
  $drdate = $row_dr['DRDate'];
  $drno = $row_dr['DRNumber'];
  $tname = $row_dr['AccountName'];
  
  $tnamelink = sprintf("dr.php?prodcat=%d&id=%d", $row_dr['ProdGroup'], $row_dr['ID']);
  
  $prodline = $pline_names[$row_dr['ProdGroup']-1];
  $dramt = $row_dr['DRAmount'];
  $s_dramt = number_format($row_dr['DRAmount'],2,'.',',');
  $type_names = array("PNL","BKG","P BKG","AS","BKG+V","P BKG+V");
  $type = $type_names[$row_dr['AcctType']-1];
  if(($ctr%2) == 0) { $cl = "even"; } else { $cl = "odd"; }
  echo "
	  <tr valign='middle' class=$cl>
		<td height='24' align='center' valign='top'>
			<strong>$drdate</strong></td>
		<td align='right' valign='top'>
			$drno</td>
		<td align='center' valign='top'>
		<a href='$tnamelink'>$tname</a></td>
		<td align='center' valign='top'>
			$type</td>
		<td align='center' valign='top'>
			$prodline</td>
		<td align='right' valign='top'>
			$s_dramt</td>
	  </tr>
	  ";
	$ctr++;
  }  
  ?>
  <tr valign='middle'>
    <td height='22' colspan='6' valign='top' align='center'> ** End of records, <?php echo $totalRows_rsDRS ?> total. ** </td>
  </tr>
</table>
<?php mysql_free_result( $rsDRS ); mysql_close($dbGlobal); ?>

</body>
</html>
