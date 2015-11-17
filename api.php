<?php
include 'asset_verifier.php';
$json = $_REQUEST["json"];
if (gettype($json) == "array") $json = json_encode($json);
$asset_id = $_REQUEST["asset_id"];
$verifier = new AssetVerifier($asset_id,$json);
$ret["verifications"] = $verifier->verifications;
header('Content-Type: application/json');
echo json_encode($ret);
?>