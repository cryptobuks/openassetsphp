<?php
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
ini_set('xdebug.var_display_max_depth', -1);

require_once "./vendor/autoload.php";

use youkchan\OpenassetsPHP\Openassets;

// É uma amostra rápida. Se o nó completo estiver em execução, podemos emitir o ativo usando esta amostra
// Atualmente apenas monacoin, litecoin testnet é suportado
// Por favor insira informações sobre o rpc do seu monacoind, litecoind.
$setting = array(
    "rpc_user" => "martex",
    "rpc_password" => "CTtrFVdOGj5YsQNESBOc6XEWg2r5aP2ktt",
    "rpc_port" => 41314
);

$openassets = new Openassets($setting);

// Se o endereço onde o utxo existe, você pode obter o saldo com get_balance
// Endereço (oa_address) para Openassets pode ser obtido.
// troca o ativo usando oa_address
// o ativo já foi emitido, o asset_id desse ativo também pode ser obtido. Isso é necessário no momento do envio do recurso.
var_dump($openassets->get_balance());

$from_oa_address = "b6MkQhog4fWBdXEVm9q2TttPyCQHxoqcHJX";

// A quantidade de ativos para emitir
$quantity = 600;

// Descreva os metadados em uma data posterior
$metadata = "u=http://google.com";

// É a comissão de mainchain
$fee = 50000;

var_dump($openassets->issue_asset($from_oa_address,$quantity, $metadata,null ,$fee));
$to_oa_address = "b6MkQhog4fWBdXEVm9q2TttPyCQHxoqcHJX";

$asset_id = "25WW93kaa8rSCRJbabQFKt8TGMAbbgkojFUz";

var_dump($openassets->send_asset($from_oa_address,$asset_id , $quantity, $to_oa_address, $fee));
