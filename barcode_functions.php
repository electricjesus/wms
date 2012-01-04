<?php	
function ean13font($code)
{
	$len = strlen($code);
	if($len > 13)
		die("UPC EAN-13 code must not be greater than 13 digits!");
		
	$split_code = preg_split('//', $code, -1, PREG_SPLIT_NO_EMPTY);
	
	$arrcode = array(	
		$scode[0],
		"G",
		$scode[1],$scode[2],$scode[3],$scode[4],$scode[5],$scode[6],
		"G",
		$scode[7],$scode[8],$scode[9],$scode[10],$scode[11],$scode[12],
		"G");
	
	// HACK: Fixed Unicode difference 
	$diffarray = array(58,64,81,65,66,66,66,67,67,67);
	$resultarr= array(	
		chr($scode[0] + 224),
		chr(91),
		chr($scode[1] + 48),
		chr($scode[2] + 48),
		chr($scode[3] + 48),
		chr($scode[4] + 48),
		chr($scode[5] + 48),
		chr($scode[6] + 48),
		chr(124),
		chr($scode[7] + $diffarray[$scode[7]]),
		chr($scode[8] + $diffarray[$scode[8]]),
		chr($scode[9] + $diffarray[$scode[9]]),
		chr($scode[10] + $diffarray[$scode[10]]),
		chr($scode[11] + $diffarray[$scode[11]]),
		chr($scode[12] + $diffarray[$scode[12]]),
		chr(93));
		$result = implode("",$resultarr);
		
		return $result;
}

function printean13font_html($plaintext)
{
	echo "<font face='UPCEANXL' size=6>". $result . "</font><br><br>$result";
}	

if (isset($barcode39))
{
    unset($barcode39);
}
$barcode39['0']="nnnwwnwnn";
$barcode39['1']="wnnwnnnnw";
$barcode39['2']="nnwwnnnnw";
$barcode39['3']="wnwwnnnnn";
$barcode39['4']="nnnwwnnnw";
$barcode39['5']="wnnwwnnnn";
$barcode39['6']="nnwwwnnnn";
$barcode39['7']="nnnwnnwnw";
$barcode39['8']="wnnwnnwnn";
$barcode39['9']="nnwwnnwnn";
$barcode39['A']="wnnnnwnnw";
$barcode39['B']="nnwnnwnnw";
$barcode39['C']="wnwnnwnnn";
$barcode39['D']="nnnnwwnnw";
$barcode39['E']="wnnnwwnnn";
$barcode39['F']="nnnwwnwnn";
$barcode39['G']="nnnnnwwnw";
$barcode39['H']="wnnnnwwnn";
$barcode39['I']="nnwnnwwnn";
$barcode39['J']="nnnnwwwnn";
$barcode39['K']="wnnnnnnww";
$barcode39['L']="nnwnnnnww";
$barcode39['M']="wnwnnnnwn";
$barcode39['N']="nnnnwnnww";
$barcode39['O']="wnnnwnnwn";
$barcode39['P']="nnwnwnnwn";
$barcode39['Q']="nnnnnnwww";
$barcode39['R']="wnnnnnwwn";
$barcode39['S']="nnwnnnwwn";
$barcode39['T']="nnnnwnwwn";
$barcode39['U']="wwnnnnnnw";
$barcode39['V']="nwwnnnnnw";
$barcode39['W']="wwwnnnnnn";
$barcode39['X']="nwnnwnnnw";
$barcode39['Y']="wwnnwnnnn";
$barcode39['Z']="nwwnwnnnn";
$barcode39['-']="nwnnnnwnw";
$barcode39['.']="wwnnnnwnn";
$barcode39[' ']="nwwnnnwnn";
$barcode39['*']="nwnnwnwnn";
$barcode39['$']="nwnwnwnnn";
$barcode39['/']="nwnwnnnwn";
$barcode39['+']="nwnnnwnwn";
$barcode39['%']="nnnwnwnwn";

function generate_barcode($info)
{
    global $barcode39;
    $im;
    //quiet zone=10x
    //$L=($C+2)*(3*$N+6)*$X+($C+1)*$I;
    //$L = length of symbol, not counting quiet zone in mils.
    //$C = Number of data characters
    //$X = $X dimension, width of the smallest unit in mills.
    //$N = Wide to narrow multiple (Use 3.0 if your code had a 3 to 1 ratio)
    //$I= intercharacter gap.  max I=5.3 X for X<10  if X>10 I=(>3X,53)

    $info="*".$info."*";
    $X=1;
    $N=3;
    $I=5.3*$X;
    $C=strlen($info);
    $L=($C+2)*(3*$N+6)*$X+($C+1)*$I+20*$X;  //last bit for the quiet zones
    $info= strtoupper ($info);

    $im = @ImageCreate ($L,50)
    or die ("Cannot Initialize new GD image stream");
    $white=ImageColorAllocate ($im, 255, 255, 255);//back ground color
    $black=ImageColorAllocate ($im, 0, 0, 0);//bar colors
    //after info has been uppercased, procedure moves through each letter
    //matches it to the corresponding 'wnwnwn' entry,
    //then draws a series of wide and narrow bands 
    //which are the barcode.
    $position=10*$X;
    for($i=0;$i<$C;$i++) //loop through the string
    {
        $ic=$info[$i];
        $bs=$barcode39[$ic];
        $bar=true;
        for($j=0;$j<strlen($bs);$j++) //loop through the matching entry to draw
        {
            if($bar)//drawing a bar
            {
                if($bs[$j]=='w')
                {
                    imagefilledrectangle ($im, $position, 0, $position+$N*$X-1, 50, $black);
                    $position+=$N*$X;
                }
                if($bs[$j]=='n')
                {
                    imagefilledrectangle ($im, $position, 0, $position+$X-1, 50, $black);
                    $position+=$X;
                }
            }

            else//not drawing a bar
            {
                if($bs[$j]=='w') $position+=$N*$X;
                if($bs[$j]=='n') $position+=$X;
            }
            if($bar) $bar=false;  //turn bars off and on
            else $bar=true;
        }//end of barcode characters
    
        $position+=$X;
    }//end of info
    return $im;
}
function ImageBarcode39($barcode)
{
	$default_barcode="NONE";
	$default_barcode=$barcode;
	$im=generate_barcode($default_barcode);
	Header("Content-type: image/png"); 
	ImagePng($im)
}
?>