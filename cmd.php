<?php
// error_reporting(E_ALL);
//ini_set('display_errors', 1);

// $url = 'https://www.bankofamrica.com/assets/asset.txt';
$url = 'balsjdf;alsjdf';

$pu = parse_url($url);
var_dump($pu);
// echo $pu['scheme']."\n";
// echo $pu['host']."\n";
// echo $pu['path']."\n";
// echo $pu['scheme'].'://'.$pu['host']."\n";

// function get_domain_from_url($url){
// 	preg_match("/https:\/\/(.+)/", $url,$matches);
// 	return $matches[1];
// };

// echo "foo:".get_domain_from_url($url);

?>