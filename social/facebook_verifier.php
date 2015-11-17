<?php

	include_once __DIR__.'/../json_reader.php';
	include_once 'secrets.php';
	
	class FacebookVerifier {
		public static $host = 'https://graph.facebook.com';
		public static $fb_app_token = FACEBOOK_APP_TOKEN;		
		public static $link_prefix = 'https://www.facebook.com/permalink.php';

		function FacebookVerifier($asset_id,$expected_text,$reader){
			$this->verified = false;
			$this->page_id = $reader->get_path('social,facebook,page_id');
			if (!($this->page_id)) {return;};			
			$posts = $this->get_page_posts();
			$data = json_decode($posts,TRUE)["data"];
			foreach ($data as $key => &$value) {
				$msg = $value["message"];
				if ($msg == $expected_text) {					
					// $this->verified = TRUE;
					$tmp = explode('_',$value["id"]);
					$this->verified = self::$link_prefix."?story_fbid=$tmp[1]&id=$tmp[0]";
				};
			};			
		}

		function get_page_posts(){
			// 486034634907283/feed?fields=message,from,created_time&until=2015-11-01&since=2015-10-01 if we need to filter further. Also date('Y-m-d', strtotime('October 12 12:44pm'));
			$endpoint = '/'.$this->page_id.'/posts/';
			$url = self::$host.$endpoint.'?access_token='.self::$fb_app_token;
			// $params = "&fields=comments";
			$formed_url = $url.$params;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$formed_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$retrievedhtml = curl_exec ($ch);
			curl_close($ch);			
			return $retrievedhtml;
		}

	}



?>