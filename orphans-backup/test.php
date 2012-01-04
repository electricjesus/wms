<?php 
$i = 0;
while($i < 44) // $i represents cell count
{
	if( ($i/26) >= 1) { echo chr(64 + floor($i/26)); }
	echo chr( 65+($i%26) ); 
	echo " ";
	$i++;
}
?>