<?php

	class Model{
		private $return;

		public function getdata(){
			return $this->return;
		}

		public function __construct(){
			$this->data = '';
		}

		public function HaveIBeenPwned($info){
			echo "<section class='results_pwnd'>";
				$API_ROOT_URL = "https://haveibeenpwned.com/api/v2/";
				$emailToCheck = $info;
				 
				$url = $API_ROOT_URL."breachedaccount/". $emailToCheck;
			 
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLOPT_USERAGENT, 'Chrome/60.0.3112.113'	);
				$data = curl_exec($curl);
				curl_close($curl);
				
				//echo $this->data."<br>"."<br>";
				
				if ($data == NULL)
					$this->return .= "This account has not been pwned!";
				else
				{
			
				$decodedResults = json_decode($data);
			 
				if ($decodedResults == NULL)
				{
						$this->return .= "<h3>Some error occured:</h3>";

						switch (json_last_error()) 
						{
							case JSON_ERROR_NONE:
								$this->return .= "JSON_ERROR_NONE";
								break;
							case JSON_ERROR_DEPTH:
								$this->return .= "JSON_ERROR_DEPTH";
								break;
							case JSON_ERROR_STATE_MISMATCH:
								$this->return .= "JSON_ERROR_STATE_MISMATCH";
								break;
							case JSON_ERROR_CTRL_CHAR:
								$this->return .= "JSON_ERROR_CTRL_CHAR";
								break;
							case JSON_ERROR_SYNTAX:
								$this->return .= "JSON_ERROR_SYNTAX";
								break;
							case JSON_ERROR_UTF8:
								$this->return .= "JSON_ERROR_UTF8";
								break;
							case JSON_ERROR_RECURSION:
								$this->return .= "JSON_ERROR_RECURSION";
								break;
							case JSON_ERROR_INF_OR_NAN:
								$this->return .= "JSON_ERROR_INF_OR_NAN";
								break;
							case JSON_ERROR_UNSUPPORTED_TYPE;
								$this->return .= "JSON_ERROR_UNSUPPORTED_TYPE";
								break;
							case JSON_ERROR_INVALID_PROPERTY_NAME:
								$this->return .= "JSON_ERROR_INVALID_PROPERTY_NAME";
								break;
							case JSON_ERROR_UTF16:
								$this->return .= "JSON_ERROR_UTF16";
								break;
							default:
								$this->return .= "UNKNOWN JSON ERROR";
								break;
						}
				} // end of error checking on JSON decoding
				else
				{
					$this->return .= "Whoaps - Pwned!"."<br>"."<br>";
					
					$noOfBreaches = count($decodedResults);
					
					if ($noOfBreaches == 1)
					{
					$this->return .= "Pwned on ".$noOfBreaches." breached site."."<br>"."<br>";
					}
					else
					{
						$this->return .= "Pwned on ".$noOfBreaches." breached sites."."<br>"."<br>";
					}
					
					foreach ($decodedResults as $breach)
					{	
						$logoPath = $breach->LogoPath;
						
						$this->return .= "<img src=$logoPath style='width:30%; height:30%'/>"."<br>"."<br>";
					
						$description = $breach->Description;
						
						$this->return .= $description."<br>"."<br>";
						
						$this->return .= "Compromised data: ";
						for ($i=0; $i < sizeof($breach->DataClasses)-1; $i++)
						{
							$this->return .= $breach->DataClasses[$i].", ";
						}
						$this->return .= $breach->DataClasses[sizeof($breach->DataClasses)-1]."<br>"."<br>";
					}
					
					
					//echo "<\section>";
				}
				}
				$this->return .= "</section>";
		}

		public function SafeBrowsing($info){
		$url = $info;

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://safebrowsing.googleapis.com/v4/threatMatches:find?key=AIzaSyAN9Nc3okzr3_1rDHn5oSMj86bneLoCzl0",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "  {\"client\": {
				\"clientId\":      \"tehnologiiweb2019\",
				\"clientVersion\": \"1.5.2\"
				},
				\"threatInfo\": {
					\"threatTypes\":      [\"MALWARE\", \"SOCIAL_ENGINEERING\"],
					\"platformTypes\":    [\"WINDOWS\"],
					\"threatEntryTypes\": [\"URL\"],
					\"threatEntries\": [
					{\"url\": \"" . $url . "\"}
					]
				}
			}",
			CURLOPT_HTTPHEADER => array(
				"cache-control: no-cache",
				"content-type: application/json",
				"postman-token: b05b8d34-85f2-49cf-0f8e-03686a71e4e9"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			$this->return .= "cURL Error #:" . $err;
		} else {
			$this->return .= '<div style="margin-left: 3%;">';
			$json = json_decode($response, true);
			$count = 0;
			foreach ($json as $key) {
				$count += 1;
			}

			if ($count > 0){
				$matches = array();
				$matches = $json['matches'];
				$matches_no = count($matches);
				$this->return .= '<div style="font-weight: bold;font-size:30px;color: red">Found ' . $matches_no . ' threats. </div><br>';
				$this->return .= '<br>';

				if($matches_no != 0){
					foreach ($matches as $match) {	
						$this->return .= '<p>';
						$this->return .= 'Threat Type : ' . $match['threatType'];
						$this->return .= '<br>';
						$this->return .= 'Platform Type : ' . $match['platformType'];
						$this->return .= '<br>';
						$this->return .= 'Cache Druration : ' . $match['cacheDuration'];
						$this->return .= '<br>';
						$this->return .= 'Threat Entry Type : ' . $match['threatEntryType'];
						$this->return .= '<br>';
						$this->return .= '</p>';
					}
				}
			}
			else{
				$this->return .= '<div style="font-weight: bold;font-size:30px;color:green" >No threats found. The site is safe.</div> <br>';
			}

			$this->return .= '</div>';
		}	
	}
}

	class View{
		private $info;

		public function __construct($i){
			$this->info = $i;
		}

		public function getinfo(){
			return $this->info;
		}

		public function show($data){
			echo $data;
		}
	}

	class Controller{
		private $view;
		private $model;

		public function __construct($v, $m){
			$this->view = $v;
			$this->model = $m;
		}

		public function doHaveIBeenPwned(){
			$info = $this->view->getinfo();

			$this->model->HaveIBeenPwned($info);
			$data = $this->model->getdata();

			$this->view->show($data);
		}

		public function doSafeBrowsing(){
			$info = $this->view->getinfo();

			$this->model->SafeBrowsing($info);
			$data = $this->model->getdata();

			$this->view->show($data);
		}

	}

?>