<?php

	include_once  __DIR__.'/../json_reader.php';
	require_once 'TwitterAPIExchange.php';
	include_once 'secrets.php';

	class TwitterVerifier {

		public static $url = 'https://api.twitter.com/1.1/search/tweets.json';
		public static $requestMethod = 'GET';
		public static $prefix = 'Verifying issuance of colored coins asset with ID #';
		public static $settings = array(
    	'oauth_access_token' => TWITTER_OAUTH_ACCESS_TOKEN,
    	'oauth_access_token_secret' => TWITTER_OAUTH_ACCESS_TOKEN_SECRET,
    	'consumer_key' => TWITTER_CONSUMER_KEY,
    	'consumer_secret' => TWITTER_CONSUMER_SECRET
		);

		var $verified = false;

		function TwitterVerifier($asset_id,$json){
			$this->json = $json;			
			$this->reader = new JsonReader($json);
			$this->twitter_verify_asset($asset_id,$this->reader);			
		}

		private function twitter_verify_asset($asset_id,$reader){
			$username = $reader->get_path('social,twitter,username');
			if (!$username) {$this->verified = false;};		
			$tweets = $this->get_tweets($asset_id);
			$this->check_tweets($tweets,$asset_id,$username);
		}		

		private function get_tweets($asset_id){
			$getfield = '?q=#'.$asset_id;
			$twitter = new TwitterAPIExchange(self::$settings);
			$json = ($twitter->setGetfield($getfield)
			             ->buildOauth(self::$url, self::$requestMethod)
			             ->performRequest());

			$data = json_decode($json,TRUE);
			return $data['statuses'];
		}

		private function check_tweets($statuses_array,$asset_id,$username){
			$expected_content = $this->get_expected_text($asset_id);
			foreach ($statuses_array as $key) {
				$txt = $key['text'];
				$user = $key['user']['screen_name'];
				// echo "checking post by [".$user."]\n";
				if ($txt==$expected_content && $user==$username) {
					$this->verified = TRUE;					
				};
			}
		}


		private function get_expected_text($asset_id){
			return self::$prefix.$asset_id;
		}



	}






?>