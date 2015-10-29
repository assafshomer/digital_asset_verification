<?php

	include_once __DIR__.'/../json_reader.php';
	include_once 'secrets.php';
	
	class FacebookVerifier {
		public static $host = 'https://graph.facebook.com';
		public static $fb_app_token = FACEBOOK_APP_TOKEN;
		public static $prefix = 'Verifying issuance of colored coins asset with asset_id:';
		var $verified;

		function FacebookVerifier($json){
			$this->json = $json;
			$this->reader = new JsonReader($json);
			$this->fb_verify_asset($json,$this->reader);			
		}

		private function fb_verify_asset($json,$reader){
			$uidx = $reader->get_path('social,facebook,uid');
			$pidx = $reader->get_path('social,facebook,pid');
			if (!$pidx || !$uidx) {$this->verified = false;};
			$postx = $this->get_post($uidx,$pidx);
			$post_contentx = $this->parse_post($postx);
			$expected_contentx = $this->get_expected_text($json,$reader);
			$this->verified = ($post_contentx==$expected_contentx);
		}

		private function get_post($uid,$pid){
			$endpoint = '/'.$uid.'_'.$pid;
			$url = self::$host.$endpoint;
			$params = '?access_token='.self::$fb_app_token;
			$formed_url = $url.$params;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$formed_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$retrievedhtml = curl_exec ($ch);
			curl_close($ch); 
			return $retrievedhtml;		
		}

		private function parse_post($raw_post){
			$tmp = json_decode($raw_post,TRUE);
			$error_message = $tmp['errors'][0]['message'];
			if (strlen($error_message)>0) {
				return $error_message;
			} else {
				return $tmp['message'];
			};	
		}

		private function get_expected_text($json,$reader){
			$aid = $reader->get_path('social,facebook,aid');
			return self::$prefix.' ['.$aid.']';
		}


	}



?>