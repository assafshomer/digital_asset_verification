<?php
	// http://stackoverflow.com/questions/14192155/twitter-api-not-showing-old-tweets
	// the problem with this implementation is that we dont see tweets older than 1 week 
	include_once  __DIR__.'/../json_reader.php';
	require_once 'TwitterAPIExchange.php';
	include_once 'secrets.php';

	class TwitterVerifier {

		public static $url = 'https://api.twitter.com/1.1/search/tweets.json';
		public static $requestMethod = 'GET';		
		public static $settings = array(
    	'oauth_access_token' => TWITTER_OAUTH_ACCESS_TOKEN,
    	'oauth_access_token_secret' => TWITTER_OAUTH_ACCESS_TOKEN_SECRET,
    	'consumer_key' => TWITTER_CONSUMER_KEY,
    	'consumer_secret' => TWITTER_CONSUMER_SECRET
		);

		var $verified = false;

		function TwitterVerifier($asset_id,$expected_text,$reader){
			$username = $reader->get_path('social,twitter,username');
			if (!$username) {$this->verified = false;};		
			$tweets = $this->get_tweets($asset_id);
			$this->check_tweets($tweets,$expected_text,$username);	
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

		private function check_tweets($statuses_array,$expected_text,$username){
			foreach ($statuses_array as $key) {
				$txt = $key['text'];
				$user = $key['user']['screen_name'];
				if ($txt==$expected_text && $user==$username) {
					$this->verified = TRUE;					
				};
			}
		}

	}






?>