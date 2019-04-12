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


		public function __construct() {
			if (isset($_GET["description"])) {
				$this->description = $_GET["description"];

				$this->results = file_get_contents($this->API_ROOT_URL . "description=" . $this->description . "&key=" . $this->API_KEY);
				$this->decodedResults = json_decode($this->results);
				$this->totalResults = $this->decodedResults->total;

				echo "Searching for " . $this->description . "..." . "<br/>";
				echo "Found " . $this->totalResults . " matches." . "<br/><br/>";

				foreach ($this->decodedResults->matches as $match) {
					echo "<h3>Exploit " . $match->_id . ":" . "</h2>";

					echo "<ul>";
					if (isset($match->author)) {
						if (is_array($match->author)) {
							foreach ($match->author as $author) {
								echo "<li>Author: " . $author . "</li>";
							}
						}
						else if (is_string($match->author)) {
							echo "<li>Author: " . $match->author . "</li>";
						}
					}
					if (isset($match->description)) {
						echo "<li>Description: " . $match->description . "</li>";
					}
					echo "</ul>";
				}

			}
			
		}
	}

	new Search();

?>