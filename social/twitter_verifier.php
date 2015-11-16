<?php
	// http://stackoverflow.com/questions/14192155/twitter-api-not-showing-old-tweets
	// the problem with this implementation is that we dont see tweets older than 1 week 
	include_once  __DIR__.'/../json_reader.php';
	require_once 'TwitterAPIExchange.php';
	include_once 'secrets.php';

	class TwitterVerifier {

		public static $base_url = 'https://api.twitter.com/1.1/';
		public static $search = 'search/tweets.json';
		public static $timeline = 'statuses/user_timeline.json';		
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
			$this->check_tweets_by_hashtag($asset_id,$expected_text,$username);
			if (!$this->verified) {
				$tweets = $this->get_tweets_from_timeline($username,$expected_text);
				$this->check_tweets_from_timeline($tweets,$expected_text,$username);
			}
		}

		private function get_tweets_by_hashtag($asset_id){
			$getfield = '?q=#'.$asset_id;
			$twitter = new TwitterAPIExchange(self::$settings);
			$json = ($twitter->setGetfield($getfield)
			             ->buildOauth(self::$base_url.self::$search, self::$requestMethod)
			             ->performRequest());

			$data = json_decode($json,TRUE);
			return $data['statuses'];
		}

		private function check_tweets_by_hashtag($asset_id,$expected_text,$username){
			$statuses_array = $this->get_tweets_by_hashtag($asset_id);
			foreach ($statuses_array as $key) {
				$txt = $key['text'];
				$user = $key['user']['screen_name'];
				if ($txt==$expected_text && $user==$username) {
					$this->verified = TRUE;					
				};
			}
		}

		private function check_tweets_from_timeline($statuses_array,$expected_text,$username){
			foreach ($statuses_array as $key) {
				$txt = $key['text'];
				$user = $key['user']['screen_name'];
				if ($txt==$expected_text && $user==$username) {
					$this->verified = TRUE;
					return;
				};
				$this->max_id = $key["id"];
			}
		}		

		private function get_tweets_from_timeline($username,$expected_text){
			if ($this->max_id > 0) {
				$getfield = '?count='.self::$batch.'&screen_name='.$username.'&max_id='.$this->max_id;
			} else {
				$getfield = '?count='.self::$batch.'&screen_name='.$username;	
			}
			$twitter = new TwitterAPIExchange(self::$settings);
			$json = ($twitter->setGetfield($getfield)
			             ->buildOauth(self::$base_url.self::$timeline, self::$requestMethod)
			             ->performRequest());
			$data = json_decode($json,TRUE);
			$count = count($data);
			$this->check_tweets_from_timeline($data,$expected_text,$username);
			if ($count == self::$batch && !$this->verified) {
				$this->get_tweets_from_timeline($username,$expected_text);
			}
		}

	}






?>