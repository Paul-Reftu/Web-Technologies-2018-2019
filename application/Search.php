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
		 * no. of shown results per page
		 */
		private $resultsPerPage = 5;
		/*
		 * current page no.
		 */
		private $currPage;
		/*
		 * no. of results per page for Shodan's API (currently 100)
		 */
		private $shodanResultsPerPage = 100;


		/*
		 * Cross Site Scripting (XSS) - proof method that echoes given property from an exploit (e.g Author, Source etc)
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

				/* 
				 * get current page number - default is 1
				 */
				if (isset($_GET["page"]))
					$this->currPage = $_GET["page"];
				else
					$this->currPage = 1;

				/*
				 * get JSON file provided by Shodan's API (an example query would be: https://exploits.shodan.io/api/search?query=description=google&page=2&key={API_KEY})
				 */ 
				$this->results = file_get_contents($this->API_ROOT_URL . "description=" . $this->description . "&page=" . ceil(($this->currPage * $this->resultsPerPage) / $this->shodanResultsPerPage) . "&key=" . $this->API_KEY);

				/*
				 * decode JSON file into a useable PHP object
				 */
				$this->decodedResults = json_decode($this->results);


				/*
				 * check for errors on decoding
				 */
				if ($this->decodedResults == NULL) {
					echo "<h3>No results found</h3>";

					switch (json_last_error()) {
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
				else {
					/*
				 	 * get the count of total exploits found through our query
				 	 */
					$this->totalResults = $this->decodedResults->total;

					/*
				     * start echoing the query results
				 	 */

					echo "<div class='searchResults'>";

					echo "<div>";
					echo "Searching for " . $this->description . "..." . "<br/>";
					echo "Found " . $this->totalResults . " matches." . "<br/><br/>";
					echo "</div>";

					/*
				 	 * echo each individual exploit along with its information
				  	 */
					for ($i = (($this->currPage - 1) * $this->resultsPerPage) % $this->shodanResultsPerPage; $i < sizeof($this->decodedResults->matches) && $i < ($this->currPage - 1) * $this->resultsPerPage % $this->shodanResultsPerPage + $this->resultsPerPage; $i++) {
						$match = $this->decodedResults->matches[$i];

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

					echo "<ul>";
				} // end of ELSE branch of error checking on JSON decoding (i.e, no error occurred)


				/*
				 * echo links to other pages containing further exploit results
				 */
				for ($i = 0; $i < ceil($this->totalResults / $this->resultsPerPage); $i++) {
					/*
				 	 * rebuild the GET query to change the page we are on
				  	 */
					$query = $_GET;
					$query["page"] = $i + 1;
					$query = http_build_query($query);

					echo "<li><a href=" . $_SERVER["PHP_SELF"] . "?" . $query . ">" . " Page " . ($i + 1) . "</a></li>";
				}

				echo "</ul>";
			} // end of IF statement checking whether $_GET["description"] is set or not
		} // end of constructor function

	} // end of Search class


	new Search();

?>