<?php
// error_reporting(E_ALL);
//ini_set('display_errors', 1);

include 'asset_verifier.php';

$json = $_POST["json"];
$asset_id = $_POST["asset_id"];

$verifier = new AssetVerifier($asset_id,$json);

$ret["verifications"] = $verifier->verifications;

header('Content-Type: application/json');
echo json_encode($ret);



?>
