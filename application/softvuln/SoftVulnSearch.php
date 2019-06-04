<?php

	require_once("Exploit.php");
	require_once("ExploitView.php");
	require_once("ExploitController.php");
	require_once("NotAnExploitException.php");
	require_once("NotAnExploitViewException.php");
	require_once("NotAnExploitListException.php");

	class SoftVulnSearch {

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
		 * maximum no. of shown page anchors at a time (for navigation to next query results)
		 */
		private $noOfShownPageLinks = 5;


		private function runShodanAPISearch() {

			/*
			 * get JSON file provided by Shodan's API (an example query would be: https://exploits.shodan.io/api/search?query=description=google&page=2&key={API_KEY})
			 */ 
			$results = file_get_contents($this->API_ROOT_URL . "description=" . $this->description . "&page=" . ceil(($this->currPage * $this->resultsPerPage) / $this->shodanResultsPerPage) . "&key=" . $this->API_KEY);

			/*
			 * decode JSON file into a useable PHP object
			 */
			$this->decodedResults = json_decode($results);


			/*
			 * check for errors on decoding
			 */
			if ($this->decodedResults == null) {
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

		}

		private function printResPageAnchors() {

			echo "<ul>";

			/*
			 * echo links to other pages containing further exploit results
			 */
			$currPageIndex = $this->currPage - 1;
			$lastShownPageLinkIndex = $currPageIndex + $this->noOfShownPageLinks - 1;

			$query = $_GET;
			$query["page"] = 1;
			$query = http_build_query($query);

			echo "<li><a href=" . $_SERVER["PHP_SELF"] . "?" . $query . ">" . " Page 1</a></li>";

			if ($currPageIndex > 0)
				echo "..." . "<br/>";

			for ($i = $currPageIndex + 1; $i < ceil($this->totalResults / $this->resultsPerPage); $i++) {
				if ($i === $lastShownPageLinkIndex) {
					if ($lastShownPageLinkIndex !== ceil($this->totalResults / $this->resultsPerPage) - 1) {
						echo "..." . "<br/>";
						$i = ceil($this->totalResults / $this->resultsPerPage) - 1;
					}
				}

				/*
			 	 * rebuild the GET query to change the page we are on
			  	 */
				$query = $_GET;
				$query["page"] = $i + 1;
				$query = http_build_query($query);

				echo "<li><a href=" . $_SERVER["PHP_SELF"] . "?" . $query . ">" . " Page " . ($i + 1) . "</a></li>";
			}

			echo "</ul>";

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

				$this->runShodanAPISearch();

				if ($this->decodedResults !== null) {
					try {

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

						$model = array();
						for ($i = (($this->currPage - 1) * $this->resultsPerPage) % $this->shodanResultsPerPage; $i < sizeof($this->decodedResults->matches) && $i < ($this->currPage - 1) * $this->resultsPerPage % $this->shodanResultsPerPage + $this->resultsPerPage; $i++) {

							$match = $this->decodedResults->matches[$i];
							array_push($model, new Exploit($match));

						}

						$view = new ExploitView();

						$controller = new ExploitController($model, $view);

						$controller->updateView();

						echo "</div>";

						$this->printResPageAnchors();

						} catch (NotAnExploitException | NotAnExploitListException | NotAnExploitViewException $e) {
							echo $e->errorMessage();
						}
				} // end of if decodedResults !== null

			} // end of isset($_GET['description']) branch 

		} // end of constructor

	} // end of SoftVulnSearch class


?>