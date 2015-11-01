<?php

  include_once 'json_reader.php';
  include 'domain/domain_verifier.php';
  include 'social/facebook_verifier.php';
  include 'social/github_verifier.php';
  include 'social/twitter_verifier.php';

  class AssetVerifier {

    public static $domainDefault = array("company" => '', "ssl_verified" => false, "url_matching" => false, "asset_verified" => false);
    public static $socialDefault = array("facebook" => false, "github" => false, "twitter" => false);

     function AssetVerifier($json) 
     {
        $this->json = $json;
        $this->reader = new JsonReader($json);
        $this->verifications['domain'] = self::$domainDefault;
        $this->verifications['social'] = self::$socialDefault;
        $this->verify($json,$this->reader);
     }

     private function verify($json,$reader){
        if (empty(json_decode($json))) {return;};
        
        $domain = $this->verifications['domain'];
        $social = $this->verifications['social'];

        if ($reader->get_path('domain')) {
          $domain_verifier = new DomainVerifier($this->json);
          $domain=array("company" => $domain_verifier->company_name, "ssl_verified" => $domain_verifier->ssl_verified, "url_matching" => $domain_verifier->url_matching, "asset_verified" => $domain_verifier->asset_verified);
        };

        if ($reader->get_path('social,facebook')) {
          $facebook_verifier = new FacebookVerifier($this->json);
          $social['facebook']=$facebook_verifier->verified;
        };

        if ($reader->get_path('social,github')) {
          $github_verifier = new GithubVerifier($this->json);
          $social['github']=$github_verifier->verified;
        };

        if ($reader->get_path('social,twitter')) {
          $twitter_verifier = new TwitterVerifier($this->json);
          $social['twitter']=$twitter_verifier->verified;
        };

        $this->verifications['social'] = $social;
        $this->verifications['domain'] = $domain;
         
     }


  } 

?>

