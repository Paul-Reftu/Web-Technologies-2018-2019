<?php

	/*
	 * @author="Paul Reftu"
	 */

	class Search {
		
		/*
		 * key that allows us to use the API 
		 * current key belongs to Paul Reftu
		 */
		private $API_KEY = "X12vohw0JQtdIemto29dopVevQKhM8kB";
		/*
		 * root URL for our exploit queries
		 */
		private $API_ROOT_URL = "https://exploits.shodan.io/api/search?query=";
		/*
		 * ID of the exploit
		 */
		private $id;
		/*
		 * author of the exploit
		 */
		private $author;
		/*
		 * Bugtraq ID of the exploit
		 */
		private $bid;
		/*
		 * the code of the exploit
		 */
		private $code;
		/*
		 * Common Vulnerability and Exposures ID of the exploit
		 */
		private $cve;
		/*
		 * date when the exploit was released
		 */
		private $date;
		/*
		 * description of the exploit
		 */
		private $description;
		/*
		 * Microsoft Security Bulletin ID of the exploit
		 */
		private $msb;
		/*
		 * Open Source Vulnerability Database ID of the exploit
		 */
		private $osvdb;
		/*
		 * the operating system that the exploit targets
		 */
		private $platform;
		/*
		 * the port number of the affected service if the exploit is remote
		 */
		private $port;
		/*
		 * the category of the exploit (dos, exploit, local, remote, shellcode, webapps)
		 */
		private $type;
		/*
		 * where the exploit was found (e.g ExploitDB)
		 */
		private $source;
		/*
		 * total no. of exploit results
		 */
		private $totalResults;
		/*
		 * the query results in JSON format
		 */
		private $results;
		/*
		 * decoded version of the query results
		 */
		private $decodedResults;


		/*
		 * Cross Site Scripting (XSS) - proof method that echoes given  property from an exploit
		 */
		public function echoProperty($prop, $propName) {
			if (is_array($prop)) {
				foreach ($prop as $subProp) {
					echo "<li>" . $propName . ": " . htmlspecialchars($subProp, ENT_QUOTES, "UTF-8") . "</li>";
				}
			}
			else if (is_string($prop)) {
				echo "<li>" . $propName . ": " . htmlspecialchars($prop, ENT_QUOTES, "UTF-8") . "</li>";
			}
		}


		public function __construct() {

			if (isset($_GET["description"])) {
				$this->description = $_GET["description"];

				$this->results = file_get_contents($this->API_ROOT_URL . "description=" . $this->description . "&key=" . $this->API_KEY);
				$this->decodedResults = json_decode($this->results);
				$this->totalResults = $this->decodedResults->total;

				echo "<div class='searchResults'>";

				echo "<div>";
				echo "Searching for " . $this->description . "..." . "<br/>";
				echo "Found " . $this->totalResults . " matches." . "<br/><br/>";
				echo "</div>";

				
				foreach ($this->decodedResults->matches as $match) {
					echo "<div>";
					echo "<h3>Exploit " . $match->_id . ":" . "</h3>";

					echo "<ul>";

					if (isset($match->author)) 
						$this->echoProperty($match->author, "Author");
					if (isset($match->bid))
						$this->echoProperty($match->bid, "Bugtraq (B) id:");
					if (isset($match->code))
						$this->echoProperty($match->code, "Code");
					if (isset($match->cve))
						$this->echoProperty($match->cve, "Common Vulnerability and Exposures (CVE) id");
					if (isset($match->date))
						$this->echoProperty($match->date, "Release date");
					if (isset($match->description))
						$this->echoProperty($match->description, "Description");
					if (isset($match->msb))
						$this->echoProperty($match->msb, "Microsoft Security Bulletin (MSB) id");
					if (isset($match->osvdb))
						$this->echoProperty($match->osvdb, "Open Source Vulnerability Database (OSVDB) id");
					if (isset($match->source))
						$this->echoProperty($match->source, "Source");
					if (isset($match->platform))
						$this->echoProperty($match->platform, "Target platform");
					if (isset($match->port))
						$this->echoProperty($match->port, "Port of affected service");
					if (isset($match->type))
						$this->echoProperty($match->type, "Exploit type");

					echo "</ul>";
					echo "</div>";
				}

				echo "</div>";
			}
		}

	}



	new Search();

?>