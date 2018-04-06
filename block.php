<?php
require_once 'jsonRPCClient.php';
$MXT = new jsonRPCClient('http://mxt:CTtrFVdOGj5YsQNESBOc6XEWg2r5aP2ktt@127.0.0.1:51314/');
$block = $MXT->getblock($_POST['block']);
$arr_txid = $block['tx'];
foreach($arr_txid as $txid) {
   $transaction = $MXT->getrawtransaction($txid,1);
   $op_return = $transaction['vout']['0']['scriptPubKey']['asm']; // pega o dado do vout 0 queh onde estara o OP_RETURN
   $arr = explode(' ', $op_return); // transforma a string em array.
   if($arr[0] == 'OP_RETURN') {
     $texto = hex2bin($arr[1]);
     echo "$texto \n";
   } else {
     echo "$txid $op_return \n";
   }
}
?>
