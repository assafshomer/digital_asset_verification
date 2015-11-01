<?php
	include_once __DIR__.'/../json_reader.php';

	class DomainVerifier {

		public static $cdir = 'certs/'; #make sure to chmod 777 
		public static $certFileName = 'level';

		function DomainVerifier($json){
			$this->json = $json;
			$this->reader = new JsonReader($json);
			$this->asset_verified = false;
			$this->company_name = '';
			$this->ssl_verified = false;
			$this->url_matching = false;

			$this->verify_domain_json($json,$this->reader);
			$this->verify_asset_json($json,$this->reader);			
		}

		private function verify_domain_json($json,$reader){
			if (empty($json)) {return;};
			$url = $reader->get_path('domain,url');			
			if ($this->verify_url($url)) {
				return $this->verify_domain_by_url($url);
			} else {
				return;
			};
		}

		private function verify_url($url){
			$pattern = "/https:\/\/(\w*\.+)+/i";
			return preg_match($pattern, $url) ? TRUE : false;
		}

		private function verify_domain_by_url($url){
			$certificate_chain_length = $this->load_certificate_chain($url);
			$chain_data = $this->extract_chain_data($certificate_chain_length,$url);			
			$this->ssl_verified=$this->verify_chain($chain_data);
			$this->get_company_data($url);
			$this->url_matching = $this->match_urls($this->get_domain_from_url($url),$this->company_url);		
		}

		private function match_urls($url1,$url2){
			$s1=$this->truncate_first($url1);
			$s2=$this->truncate_first($url2);
			return ($s1==$s2 || $s2==$url1 || $s1==$url2)?TRUE:false;
		}

		private function truncate_first($url){
			$tmp=explode('.',$url);
			array_shift($tmp);
			return implode(".", $tmp);
		}

		private function verify_chain($array){
			$array=array_unique($array);
			if (count($array) == 1 && $array[0]=='good') {
				return true;
			} else {
				return false;
			};	
		}

		private function extract_chain_data($chain_length,$url){		
			$chain_data = array();
			$tag = str_replace('.', '_', $this->get_domain_from_url($url));	
			for ($x = 0; $x < $chain_length; $x++) {		
				$result=file_get_contents(self::$cdir.$tag.'_result'.$x.'.txt');
				preg_match("/0x\S+\s(\w+)\n/", $result,$matches);
				array_push($chain_data,$matches[1]);
			}
			return $chain_data;
		}

		private function get_company_data($url){
			chdir(dirname(__FILE__));
			$cmd = './get_company_data.sh '.$url.' '.self::$cdir.' '.self::$certFileName;
			$subject=exec($cmd);
			preg_match("/O=(.+)\//U",$subject,$matches);			
			$this->company_name = empty($matches[1]) ? 'No name found on certificate' : $matches[1];
			preg_match("/CN=(.+)/",$subject,$matches);
			$this->company_url=$matches[1];
		}

		private function load_certificate_chain($url){
			chdir(dirname(__FILE__));
			$cmd = './load_ssl_certificates.sh '.$url.' '.self::$cdir.' '.self::$certFileName;
			// echo $cmd;
			return exec($cmd);
		}

		private function get_domain_from_url($url){
			preg_match("/https:\/\/(.+)/", $url,$matches);
			return $matches[1];
		}

		private function verify_asset_json($json,$reader){
			if (empty($json)) {return;};			
			$path = $reader->get_path('domain,path');
			if (empty($path)) {return;};
			$url = $reader->get_path('domain,url');
			if (empty($url)) {return;};
			$aid = $reader->get_path('domain,aid');
			if (empty($aid)) {return;};
			$file = file_get_contents($url.'/'.$path);
			$regex="/^$aid\n|\n$aid\n|\n$aid$/";
			preg_match($regex,$file,$matches);
			$this->asset_verified = (trim($matches[0]) == $aid);
		}

	}


	

?>
