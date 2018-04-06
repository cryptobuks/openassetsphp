<?php
require_once 'jsonRPCClient.php';
$MXT = new jsonRPCClient('http://mxt:CTtrFVdOGj5YsQNESBOc6XEWg2r5aP2ktt@127.0.0.1:51314/');
$transaction = $MXT->getrawtransaction('e8d33e2de3f5dc85cd750daf753356cd6c5d00557207e0b7ac401a2f1508df80',1); //genesis transaction
$op_return = $transaction['vout']['0']['scriptPubKey']['asm']; // pega o dado do vout 0 queh onde estara o OP_RETURN
$arr = explode(' ', $op_return); // transforma a string em array.
$texto = hex2bin($arr[1]);
if($arr[0] == 'OP_RETURN') {
  echo "<br> $texto \n";
} else {
  echo "<br> $op_return \n";
}
?>
