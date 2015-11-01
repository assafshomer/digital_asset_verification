<?php

  include_once 'json_reader.php';

  class AssetVerifier {

    public static $domainDefault = array("company" => '', "ssl_verified" => false, "url_matching" => false, "asset_verified" => false);
    public static $socialDefault = array("facebook" => false, "github" => false, "twitter" => false);

     function AssetVerifier($json) 
     {
        $this->json = $json;
        $this->reader = new JsonReader($json);
        $this->result['domain'] = self::$domainDefault;
        $this->result['social'] = self::$socialDefault;
        $this->verify($json,$this->reader);
     }

     private function verify($json,$reader){
        if (empty(json_decode($json))) {return;};
        
        $domain = $this->result['domain'];
        $social = $this->result['social'];

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

        $this->result['social'] = $social;
        $this->result['domain'] = $domain;
         
     }


  } 

?>

