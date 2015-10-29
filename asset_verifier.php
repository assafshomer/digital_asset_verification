<?php

  include_once 'json_reader.php';

  class AssetVerifier {

    public static $domainDefault = array("company" => '', "ssl_verified" => false, "url_matching" => false, "asset_verified" => false);
    public static $socialDefault = array("facebook" => false, "github" => false, "twitter" => false);

     function AssetVerifier($json) 
     {
        $this->json = $json;
        $this->reader = new JsonReader($json);
        $this->domain = self::$domainDefault;
        $this->social = self::$socialDefault;
        $this->verify($json,$this->reader);
     }

     private function verify($json,$reader){
        if (empty(json_decode($json))) {return;};
        $social = $this->social;

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

        $this->social = $social;
         
     }


  } 

?>

