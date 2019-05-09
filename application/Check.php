<?php

	class Check
	{
		private $emailToCheck;
		
		private $API_ROOT_URL = "https://haveibeenpwned.com/api/v2/";
		
		private $decodedResults;
		
		private $data;
		
		private $noOfBreaches;
		
		
		
		
		public function __construct()
		{
			
			 if (isset($_GET["email"]))
			 {
				echo "<section class='results_pwnd'>";
				 
				$this->emailToCheck = $_GET["email"];
				 
				$url = $this->API_ROOT_URL."breachedaccount/". $this->emailToCheck;
			 
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLOPT_USERAGENT, 'Chrome/60.0.3112.113'	);
				$this->data = curl_exec($curl);
				curl_close($curl);
				
				//echo $this->data."<br>"."<br>";
				
				if ($this->data == NULL)
					echo "This account has not been pwned!";
				else
				{
			
				$this->decodedResults = json_decode($this->data);
			 
				if ($this->decodedResults == NULL)
				{
						echo "<h3>Some error occured:</h3>";

						switch (json_last_error()) 
						{
							case JSON_ERROR_NONE:
								echo "JSON_ERROR_NONE";
								break;
							case JSON_ERROR_DEPTH:
								echo "JSON_ERROR_DEPTH";
								break;
							case JSON_ERROR_STATE_MISMATCH:
								echo "JSON_ERROR_STATE_MISMATCH";
								break;
							case JSON_ERROR_CTRL_CHAR:
								echo "JSON_ERROR_CTRL_CHAR";
								break;
							case JSON_ERROR_SYNTAX:
								echo "JSON_ERROR_SYNTAX";
								break;
							case JSON_ERROR_UTF8:
								echo "JSON_ERROR_UTF8";
								break;
							case JSON_ERROR_RECURSION:
								echo "JSON_ERROR_RECURSION";
								break;
							case JSON_ERROR_INF_OR_NAN:
								echo "JSON_ERROR_INF_OR_NAN";
								break;
							case JSON_ERROR_UNSUPPORTED_TYPE;
								echo "JSON_ERROR_UNSUPPORTED_TYPE";
								break;
							case JSON_ERROR_INVALID_PROPERTY_NAME:
								echo "JSON_ERROR_INVALID_PROPERTY_NAME";
								break;
							case JSON_ERROR_UTF16:
								echo "JSON_ERROR_UTF16";
								break;
							default:
								echo "UNKNOWN JSON ERROR";
								break;
						}
				} // end of error checking on JSON decoding
				else
				{
					echo "Whoaps - Pwned!"."<br>"."<br>";
					
					$this->noOfBreaches = count($this->decodedResults);
					
					if ($this->noOfBreaches == 1)
					{
					echo "Pwned on ".$this->noOfBreaches." breached site."."<br>"."<br>";
					}
					else
					{
						echo "Pwned on ".$this->noOfBreaches." breached sites."."<br>"."<br>";
					}
					
					foreach ($this->decodedResults as $breach)
					{	
						$logoPath = $breach->LogoPath;
						
						echo "<img src=$logoPath style='width:30%; height:30%'/>"."<br>"."<br>";
					
						$description = $breach->Description;
						
						echo $description."<br>"."<br>";
						
						echo "Compromised data: ";
						for ($i=0; $i < sizeof($breach->DataClasses)-1; $i++)
						{
							echo $breach->DataClasses[$i].", ";
						}
						echo $breach->DataClasses[sizeof($breach->DataClasses)-1]."<br>"."<br>";
					}
					
					
					//echo "<\section>";
				}
				}
				echo "</section>";
			 } // end of isset($_GET["email"]) if
			 
			 
		}
	}

	new Check();


?>