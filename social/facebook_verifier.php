<?php

	include_once __DIR__.'/../json_reader.php';
	include_once 'secrets.php';
	
	class FacebookVerifier {
		public static $host = 'https://graph.facebook.com';
		public static $fb_app_token = FACEBOOK_APP_TOKEN;
		public static $page_id = '486034634907283';
		var $verified = false;

		function FacebookVerifier($asset_id,$expected_text,$reader){
			$comment_id = $reader->get_path('social,facebook,comment_id');
			$fb_id = $reader->get_path('social,facebook,id');
			$story_fbid = $reader->get_path('social,facebook,story_fbid');
			if (!($comment_id && $fb_id && $story_fbid)) {$this->verified = false;};			
			$feed = $this->search_page_feed();
			$data = json_decode($feed,TRUE)["data"];
			// var_dump($data);
			foreach ($data as $key => &$value) {
				if ($value["id"] == $fb_id.'_'.$story_fbid) {
					$comment_data = $value["comments"]["data"];
					// var_dump($comment_data);
					foreach ($comment_data as $ckey => &$cvalue) {
						$cmt_id = explode('_',$cvalue["id"])[1];
						if ($cmt_id == $comment_id) {
							// echo "found comment [".$cvalue["message"]."]\n";
							$this->verification_comment = $cvalue["message"];
						};
					};					
				}
				// $cmt_id = explode('_',$value["comments"]["data"][0]["id"])[0];
				// $cmt = $value["comments"]["data"][0]["message"];
				// if ($cmt_id == '493110580866355') {
				// 	echo "this is the relevant comment [".$cmt."]\n";
				// }
				// var_dump($value);
				// $uid = $value["from"]["id"];
				// echo "uid [".$uid."]\n";
				// if ($uid == $userid) {
				// 	var_dump($value["message"]);
				// }
				// var_dump($value["from"]["id"]);
				// var_dump($value["message"]);
				// var_dump(explode('_',$value["comments"]["data"][0]["id"])[0]);
				// var_dump($value["comments"]["data"][0]["message"]);
			};
			
			// $post_contentx = $this->parse_feed($postx);
			// $expected_comment = self::$prefix.$asset_id;
			// echo "expected comment [".$expected_comment."]\n";
			$this->verified = ($this->verification_comment==$expected_text);		
		}

		function search_page_feed(){			
			// 486034634907283/feed?fields=message,from,created_time&until=2015-11-01&since=2015-10-01 if we need to filter further. Also date('Y-m-d', strtotime('October 12 12:44pm'));
			$endpoint = '/'.self::$page_id.'/feed/';
			$url = self::$host.$endpoint.'?access_token='.self::$fb_app_token;
			$params = "&fields=comments";
			$formed_url = $url.$params;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$formed_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$retrievedhtml = curl_exec ($ch);
			curl_close($ch);
			return $retrievedhtml;
		}

		// private function parse_feed($raw_post){
		// 	$tmp = json_decode($raw_post,TRUE);
		// 	$error_message = $tmp['errors'][0]['message'];
		// 	if (strlen($error_message)>0) {
		// 		return $error_message;
		// 	} else {
		// 		return $tmp['data'];
		// 	};	
		// }

	}



?>