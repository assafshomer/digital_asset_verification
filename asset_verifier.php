<?php

  include_once 'json_reader.php';
  include 'domain/domain_verifier.php';
  include 'social/facebook_verifier.php';
  include 'social/github_verifier.php';
  include 'social/twitter_verifier.php';

  class AssetVerifier {

    public static $domainDefault = array("company" => '', "ssl_verified" => false, "url_matching" => false, "asset_verified" => false);
    public static $socialDefault = array("facebook" => false, "github" => false, "twitter" => false);
    public static $prefix = 'Verifying issuance of colored coins asset with ID #';

     function AssetVerifier($asset_id,$json)
     {
        $this->verifications['domain'] = self::$domainDefault;
        $this->verifications['social'] = self::$socialDefault;
        if (empty(json_decode($json))) {return;};
        $this->json = $json;
        $this->reader = new JsonReader($json);
        $this->expected_text = self::$prefix.$asset_id;
        $this->verify($asset_id,$this->expected_text,$this->reader);
     }

     private function verify($asset_id,$expected_text,$reader){
        
        $domain = $this->verifications['domain'];
        $social = $this->verifications['social'];

        if ($reader->get_path('domain')) {
          $domain_verifier = new DomainVerifier($reader);
          $domain=array("company" => $domain_verifier->company_name, "ssl_verified" => $domain_verifier->ssl_verified, "url_matching" => $domain_verifier->url_matching, "asset_verified" => $domain_verifier->asset_verified);
        };

        if ($reader->get_path('social,facebook')) {
          $facebook_verifier = new FacebookVerifier($asset_id,$expected_text,$reader);
          $social['facebook']=$facebook_verifier->verified;
        };

        if ($reader->get_path('social,github')) {
          $github_verifier = new GithubVerifier($asset_id,$expected_text,$reader);
          $social['github']=$github_verifier->verified;
        };

        if ($reader->get_path('social,twitter')) {          
          $twitter_verifier = new TwitterVerifier($asset_id,$expected_text,$reader);
          $social['twitter']=$twitter_verifier->verified;
        };

        $this->verifications['social'] = $social;
        $this->verifications['domain'] = $domain;
         
     }


  } 

?>

