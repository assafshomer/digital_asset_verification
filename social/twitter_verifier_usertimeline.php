<?php

	include_once  __DIR__.'/../json_reader.php';
	require_once 'TwitterAPIExchange.php';
	include_once 'secrets.php';

	class TwitterVerifier {

		public static $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		public static $requestMethod = 'GET';		
		public static $settings = array(
    	'oauth_access_token' => TWITTER_OAUTH_ACCESS_TOKEN,
    	'oauth_access_token_secret' => TWITTER_OAUTH_ACCESS_TOKEN_SECRET,
    	'consumer_key' => TWITTER_CONSUMER_KEY,
    	'consumer_secret' => TWITTER_CONSUMER_SECRET
		);
		public static $batch = 200;

		var $verified = false;

		function TwitterVerifier($asset_id,$expected_text,$reader){
			$this->max_id = 0;
			$username = $reader->get_path('social,twitter,username');
			if (!$username) {$this->verified = false;};		
			$tweets = $this->get_tweets($username,$expected_text);
			$this->check_tweets($tweets,$expected_text,$username);	
		}

		private function get_tweets($username,$expected_text){
			if ($this->max_id > 0) {
				$getfield = '?count='.self::$batch.'&screen_name='.$username.'&max_id='.$this->max_id;
			} else {
				$getfield = '?count='.self::$batch.'&screen_name='.$username;	
			}
			// $getfield = '?count=5&screen_name='.$username.'&max_id='.$max_id;
			$twitter = new TwitterAPIExchange(self::$settings);
			$json = ($twitter->setGetfield($getfield)
			             ->buildOauth(self::$url, self::$requestMethod)
			             ->performRequest());
			$data = json_decode($json,TRUE);
			// var_dump($data);
			$count = count($data);
			// echo "count".$count."\n";
			$this->check_tweets($data,$expected_text,$username);
			if ($count == self::$batch && !$this->verified) {
				// echo "verified[".$this->verified."]\n";
				$this->get_tweets($username,$expected_text);
			}
		}

		private function check_tweets($statuses_array,$expected_text,$username){
			foreach ($statuses_array as $key) {
				$txt = $key['text'];
				$user = $key['user']['screen_name'];
				// echo "checking post by [".$user."]\n";
				// echo "txt [".$txt."]\n";
				// echo "expected_text [".$expected_text."]\n";
				// echo "user [".$user."]\n";
				// echo "username [".$username."]\n";
				if ($txt==$expected_text && $user==$username) {
					$this->verified = TRUE;
					return;
				};
				$this->max_id = $key["id"];
			}
		}

	}






?>