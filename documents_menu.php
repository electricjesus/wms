<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SDM Documents &amp; ReportsCentre</title>
<link href="styles/style.css" rel="stylesheet" type="text/css" />
</head>
<?php if(!isset($item)) { $item='inventory.php'; } ?>
<?php if(!isset($datemode)) { $datemode='all'; } ?>
<body>
<form action="<?php echo $item ?>" method="post" target="mainFrame2">
<p>
	<strong>Document Centre</strong>
	<br /><br />
	1.) Pick an Item to view: 
</p>
<p>
	<a href="?item=inventory.php&">
	&bull; 
	<?php if($item=='inventory.php') { echo '<strong>'; } ?>
		View Current Inventory
	<?php if($item=='inventory.php') { echo '</strong>'; } ?>
	</a><br />
	<a href="?item=as.php&">
	&bull; 
	<?php if($item=='as.php') { echo '<strong>'; } ?>
		Arrived Stocks
	<?php if($item=='as.php') { echo '</strong>'; } ?>
	</a><br />	
</p>
	<p>2.) Select Options:<br />
	  <br />
	Product line:
		<select name="prodgroup" style="width:100px">
		  <option value="1">Oishi</option>
		  <option value="2">Oishi 2</option>
		  </select>
	  <br />
	  Time: <a href="?item=<?php echo $item ?>&datemode=all">
	  <?php if($datemode=='all') { echo '<strong>'; } ?>All<?php if($datemode=='all') { echo '</strong>'; } ?>
	  </a> | 
	  <a href="?item=<?php echo $item ?>&datemode=range">
	  <?php if($datemode=='range') { echo '<strong>'; } ?>Ranged<?php if($datemode=='range') { echo '</strong>'; } ?>
	  </a><br />
	  <br />
	<?php if($datemode!= 'range') { ?>
	<em><strong>• Showing all records</strong></em><br>	
	<?php } else { ?>
	  <div style="position:relative" id="tl_ranged">Date Range: <br />
	<em>From:</em> <br />
	<?php $mn = date("n"); $dj = date("j"); ?>
	<select id="fm" name="fm" style="width:40;"><?php for ($i = 1; $i <= 12; $i++) echo "<option value=$i>$i</option>";	?></select>
	<select id="fd" name="fd" style="width:40;"><?php for ($i = 1; $i <= 31; $i++) echo "<option value=$i>$i</option>";	?></select>
	<select id="fy" name="fy" style="width:80;"><?php $yearrange = 40;
		for ($i = date("Y") - $yearrange; $i <= date("Y") + $yearrange; $i++)
		{
			if($i == date("Y")) { $seltxt = "selected"; } else { $seltxt = ""; }
			echo "<option value=$i $seltxt>$i</option>";
		}
	?>
	</select>	
	<br />
	<em>To:</em>
	<br />
	<select id="tm" name="tm" style="width:40;"><?php for ($i = 1; $i <= 12; $i++) echo "<option value=$i>$i</option>"; ?></select>
	<select id="td" name="td" style="width:40;"><?php for ($i = 1; $i <= 31; $i++) echo "<option value=$i>$i</option>"; ?></select>
	<select id="ty" name="ty" style="width:80;"><?php $yearrange = 40;
		for ($i = date("Y") - $yearrange; $i <= date("Y") + $yearrange; $i++)
		{
			if($i == date("Y")) { $seltxt = "selected"; } else { $seltxt = ""; }
			echo "<option value=$i $seltxt>$i</option>";
		}
	?>
	</select>	
	<SCRIPT TYPE="text/javascript">
		document.getElementById('fm').value = <?php echo $mn - 1?>;
		document.getElementById('fd').value = <?php echo $dj ?>;

		document.getElementById('tm').value = <?php echo $mn ?>;
		document.getElementById('td').value = <?php echo $dj ?>;
	</SCRIPT>
	</div>
	<br />
	<?php } ?>
	<strike>
	<br />
	  Limit to 
	  <input name="textfield" type="text" size="3" maxlength="3" disabled="disabled" />
	  items per page <br />
	</strike>
	<br>
	  <label>
	  <input type="checkbox" name="xls" value="true" />
	  &nbsp;View in excel?</label>
	  <br /><br />
	  Then click here to proceed:<br />
	  <input type="submit" name="Submit" value="View / Export Document" />
	  <input type="hidden" name="datemode" value="<?php echo $datemode ?>" />
<form>
</p>
<a href="http://localhost/phpmyadmin/export.php?lang=en-utf-8&server=1&collation_connection=utf8_general_ci&export_type=server&db_select%5B%5D=nutri&what=sql&header_comment=&sql_compat=NONE&sql_structure=structure&sql_auto_increment=1&use_backquotes=1&sql_data=data&showcolumns=yes&max_query_size=50000&hexforbinary=yes&sql_type=insert&latex_caption=yes&latex_structure=structure&latex_structure_caption=Structure+of+table+__TABLE__&latex_structure_continued_caption=Structure+of+table+__TABLE__+%28continued%29&latex_structure_label=tab%3A__TABLE__-structure&latex_data=data&latex_showcolumns=yes&latex_data_caption=Content+of+table+__TABLE__&latex_data_continued_caption=Content+of+table+__TABLE__+%28continued%29&latex_data_label=tab%3A__TABLE__-data&latex_replace_null=%5Ctextit%7BNULL%7D&csv_data=csv_data&export_separator=%3B&enclosed=%22&escaped=%5C&add_character=%5Cr%5Cn&csv_replace_null=NULL&excel_data=excel_data&excel_replace_null=NULL&excel_edition=win&htmlexcel_data=htmlexcel_data&htmlexcel_replace_null=NULL&htmlword_structure=structure&htmlword_data=data&htmlword_replace_null=NULL&xml_data=xml_data&filename_template=__SERVER__%20SDMBackup%20%m%d%Y%T%20<?php echo date("H i") ?>&remember_template=on&compression=none&asfile=sendit">
OTHER: Create SQL Backup
</a>
</form>
</body>

</html>
