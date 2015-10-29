<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

include 'domain/domain_verifier.php';
include 'social/facebook_verifier.php';
include 'social/github_verifier.php';
include 'social/twitter_verifier.php';

// in prod
// $json = $_POST["json"];
// for local testing
$json_file_name = $_GET['name'];
$json = file_get_contents('../verify/test/fixtures/'.$json_file_name.'.json');

$twitter = new TwitterVerifier($json);
$facebook = new FacebookVerifier($json);
$github = new GithubVerifier($json); 
$domain = new DomainVerifier($json);

$domainCheck = array("company" => $domain->company_name, "ssl_verified" => $domain->ssl_verified, "url_matching" => $domain->url_matching, "asset_verified" => $domain->asset_verified);
$socialCheck["facebook"] = $facebook->verified;
$socialCheck["github"] = $github->verified;
$socialCheck["twitter"] = $twitter->verified;

$ret["domain"] = $domainCheck;
$ret["social"] = $socialCheck;

header('Content-Type: application/json');
echo json_encode($ret);
?>