<?php 

function hexStringToByteString($hexString){
    $len=strlen($hexString);

    $byteString="";
    for ($i=0;$i<$len;$i=$i+2){
        $charnum=hexdec(substr($hexString,$i,2));
        $byteString.=chr($charnum);
    }

return $byteString;
}

// BCmath version for huge numbers
function bc_arb_encode($num, $basestr) {
    if( ! function_exists('bcadd') ) {
        Throw new Exception('You need the BCmath extension.');
    }

    $base = strlen($basestr);
    $rep = '';

    while( true ){
        if( strlen($num) < 2 ) {
            if( intval($num) <= 0 ) {
                break;
            }
        }
        $rem = bcmod($num, $base);
        $rep = $basestr[intval($rem)] . $rep;
        $num = bcdiv(bcsub($num, $rem), $base);
    }
    return $rep;
}

function bc_arb_decode($num, $basestr) {
    if( ! function_exists('bcadd') ) {
        Throw new Exception('You need the BCmath extension.');
    }

    $base = strlen($basestr);
    $dec = '0';

    $num_arr = str_split((string)$num);
    $cnt = strlen($num);
    for($i=0; $i < $cnt; $i++) {
        $pos = strpos($basestr, $num_arr[$i]);
        if( $pos === false ) {
            Throw new Exception(sprintf('Unknown character %s at offset %d', $num_arr[$i], $i));
        }
        $dec = bcadd(bcmul($dec, $base), $pos);
    }
    return $dec;
}


// base 58 alias
function bc_base58_encode($num) {   
    return bc_arb_encode($num, '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz');
}
function bc_base58_decode($num) {
    return bc_arb_decode($num, '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz');
}

//hexdec with BCmath
function bc_hexdec($num) {
    return bc_arb_decode(strtolower($num), '0123456789abcdef');
}
function bc_dechex($num) {
    return bc_arb_encode($num, '0123456789abcdef');
}

//step1 is just converting the 130-hex-character publickey in bytes
$publickey='02a19dc039741012cca7a39de214b526b0a60e70c17040cddba1848401f90b2dfc';
$step1=hexStringToByteString($publickey);
echo "\n step1 ".$publickey."<br>";

// step 2 sha256 hashes the publickey from step 1
$step2=hash("sha256",$step1);
echo "\n step2 ".$step2."<br>";

// step 3 ripemd160 hashes step2
$step3=hash('ripemd160',hexStringToByteString($step2));
echo "\n step3 ".$step3."<br>";

// step 4 is the tricky part: add 32(hexadecimal of 50) the version number to step 3
$step4='32'.$step3;
$step44='1332'.$step3;
$step444='2332'.$step3;
echo "\n step4 ".$step4."<br>";

// step 5 sha256 hash step4 
$step5=hash("sha256",hexStringToByteString($step4));
$step55=hash("sha256",hexStringToByteString($step44));
$step555=hash("sha256",hexStringToByteString($step444));
echo "\n step5 ".$step5."<br>";

// step 6 sha256 hash step 5
$step6=hash("sha256",hexStringToByteString($step5));
$step66=hash("sha256",hexStringToByteString($step55));
$step666=hash("sha256",hexStringToByteString($step555));
echo "\n step6 ".$step6."<br>";

// step 7 takes the first 8 characters (4 bytes) of step 6 as a checksum
$checksum=substr($step6,0,8);
$checksum1=substr($step66,0,8);
$checksum2=substr($step666,0,8);
echo "\n step7 ".$checksum."<br>";

// step 8 adds the checksum to step 4
$step8=$step4.$checksum;
$step88=$step44.$checksum1;
$step888=$step444.$checksum2;
echo "\n step8 ".$step8."<br>";

//step 9 converts step 8 into decimal
$step9=bc_hexdec($step8);
$step99=bc_hexdec($step88);
$step999=bc_hexdec($step888);

//step 10 encodes step 9 into base58
$step10=bc_base58_encode($step9);
$step11=bc_base58_encode($step99);
$step12=bc_base58_encode($step999);
echo "\n step9 ".$step10."\n<br>";
echo "\n step10 OA ".$step11."\n<br>";
echo "\n step11 OA-ID ".$step12."\n<br>";

?>
