<?php

	include_once  __DIR__.'/../json_reader.php';
	include_once 'secrets.php';

	class TwitterVerifier {

		public static $host = 'https://api.twitter.com';
		public static $twitter_consumer_key = TWITTER_CONSUMER_KEY;
		public static $twitter_consumer_secret = TWITTER_CONSUMER_SECRET;
		public static $token_file_path = 'twitter_bearer_token.txt';
		public static $prefix = 'Verifying issuance of colored coins asset with asset_id:';
		var $verified;

		function TwitterVerifier($json){
			$this->fetch_bearer_token(self::$token_file_path);
			$this->json = $json;
			$this->reader = new JsonReader($json);
			$this->twitter_verify_asset($json,$this->reader);			
		}

		private function twitter_verify_asset($json,$reader){
			$pid = $reader->get_path('social,twitter,pid');
			if (!$pid) {$this->verified = false;};		
			$tweet_content = $this->get_tweet($pid);
			$expected_content = $this->get_expected_text($json,$reader);
			$this->verified = ($tweet_content==$expected_content);
		}		

		private function get_raw_tweet_by_id($bearer_token, $tweet_id){
			$endpoint = '/1.1/statuses/show.json';
			$url = self::$host.$endpoint;
			$params = '?id='.$tweet_id;
			$headers = array( 
				"GET ".$endpoint.$params." HTTP/1.1", 
				"Host: api.twitter.com", 
				"User-Agent: colu Twitter Application-only OAuth App v.1",
				"Authorization: Bearer ".$bearer_token
			);
			$formed_url = $url.$params;
			$ch = curl_init();  
			curl_setopt($ch, CURLOPT_URL,$formed_url);  
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			$retrievedhtml = curl_exec ($ch); 
			curl_close($ch); 
			return $retrievedhtml;		
		}

		private function parse_tweet($raw_tweet){
			$tmp = json_decode($raw_tweet,TRUE);
			$error_message = $tmp['errors'][0]['message'];
			if (strlen($error_message)>0) {
				return $error_message;
			} else {
				return $tmp['text'];
			};	
		}

		private function get_tweet($tweet_id){
			$bearer_token = $this->fetch_bearer_token(self::$token_file_path);
			$tweet = $this->get_raw_tweet_by_id($bearer_token,$tweet_id);
			// $tweet = get_tweet_by_id($bearer_token,'649137197539');
			try {
				return $this->parse_tweet($tweet);
			} catch (Exception $e) {
			    echo 'Caught exception: ',  $e->getMessage(), "\n";
			};	
		}

		private function get_expected_text($json,$reader){
			$aid = $reader->get_path('social,twitter,aid');
			return self::$prefix.' ['.$aid.']';
		}

		function get_bearer_token(){
			$encoded_consumer_key = urlencode(self::$twitter_consumer_key);
			$encoded_consumer_secret = urlencode(self::$twitter_consumer_secret);
			$bearer_token = $encoded_consumer_key.':'.$encoded_consumer_secret;
			$base64_encoded_bearer_token = base64_encode($bearer_token);
			$url = "https://api.twitter.com/oauth2/token"; // url to send data to for authentication
			$headers = array( 
				"POST /oauth2/token HTTP/1.1", 
				"Host: api.twitter.com", 
				"User-Agent: jonhurlock Twitter Application-only OAuth App v.1",
				"Authorization: Basic ".$base64_encoded_bearer_token,
				"Content-Type: application/x-www-form-urlencoded;charset=UTF-8"
			); 
			$ch = curl_init();  
			curl_setopt($ch, CURLOPT_URL,$url);  
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
			curl_setopt($ch, CURLOPT_POST, 1); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials"); 
			$header = curl_setopt($ch, CURLOPT_HEADER, 1); 
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$retrievedhtml = curl_exec ($ch); 
			curl_close($ch); 
			// echo "retrievedhtml: [".$retrievedhtml."]<br/>"; 
			$output = explode("\n", $retrievedhtml);
			// var_dump($output);
			$bearer_token = '';
			foreach($output as $line)
			{
				if($line === false)
				{
					// there was no bearer token
				}else{
					$a = json_decode($line,TRUE);
					if (is_array($a) && array_key_exists('access_token', $a)) {
						$bearer_token = json_decode($line,TRUE)['access_token'];	
					 }			
				}
			};
			return $bearer_token;
		}

		// get it from file, or from twitter api if file is empty
		function fetch_bearer_token($path){
			$path = dirname(__FILE__).'/'.$path;
			$bearer_token_file = fopen($path, "w+") or die("Unable to open file! [".$path."]");
			$size = filesize($path);
			if ($size > 0) {
				$bearer_token = fread($bearer_token_file,$size);
			} else {		
				$bearer_token = $this->get_bearer_token();
				fwrite($bearer_token_file,$bearer_token); 
			};
			fclose($bearer_token_file);	
			return $bearer_token;
		}

	}






?>