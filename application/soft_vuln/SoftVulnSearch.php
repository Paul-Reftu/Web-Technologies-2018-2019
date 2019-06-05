<?php

	require_once("Exploit.php");
	require_once("ExploitView.php");
	require_once("ExploitController.php");
	require_once("NotAnExploitException.php");
	require_once("NotAnExploitViewException.php");
	require_once("NotAnExploitListException.php");

	/*
	 * class whose instance performs searches w.r.t software vulnerabilities and that prints their information accordingly
	 *
	 * this class obeys the MVC design pattern
	 */
	class SoftVulnSearch {

		/*
		 * key that allows us to use the API 
		 * current key belongs to Paul Reftu
		 */
		const API_KEY = "X12vohw0JQtdIemto29dopVevQKhM8kB";
		/*
		 * root URL for our exploit queries
		 */
		const API_ROOT_URL = "https://exploits.shodan.io/api/search?";

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
		/*
		 * the maximum no. of read characters from a property's value
		 *  (prevents the echoing of very long description or code segments of an exploit)
		 */
		private $maxNoOfPropReadChars = 500;

		public function __construct() {

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

					$model = $this->getExploitList();

					$view = new ExploitView($this->description, $this->currPage, $this->noOfShownPageLinks, $this->totalResults, $this->resultsPerPage, $this->maxNoOfPropReadChars);

					$controller = new ExploitController($model, $view);

					$controller->updateView();

				} 
				catch (NotAnExploitException | NotAnExploitListException | NotAnExploitViewException $e) {
					echo $e->errorMessage();
				}
			} // end of if decodedResults !== null

		} // END of __construct()

		/*
		 * @return the list of exploits w.r.t the current object's decoded results
		 */
		private function getExploitList() {

			$exploitList = array();

			for ($i = (($this->currPage - 1) * $this->resultsPerPage) % $this->shodanResultsPerPage; $i < sizeof($this->decodedResults->matches) && $i < ($this->currPage - 1) * $this->resultsPerPage % $this->shodanResultsPerPage + $this->resultsPerPage; $i++) {

				$match = $this->decodedResults->matches[$i];
				array_push($exploitList, new Exploit($match));

			}

			return $exploitList;

		} // END of getExploitList()

		/*
		 * uses Shodan's API to perform an exploit search w.r.t the given description received through GET
		 */
		private function runShodanAPISearch() {

			/*
			 * get JSON file provided by Shodan's API (an example query would be: https://exploits.shodan.io/api/search?query=description=google&page=2&key={API_KEY})
			 */ 
			$results = file_get_contents(self::API_ROOT_URL . "query=" . $this->description . "&page=" . ceil(($this->currPage * $this->resultsPerPage) / $this->shodanResultsPerPage) . "&key=" . self::API_KEY);

			/*
			 * decode JSON file into a useable PHP object
			 */
			$this->decodedResults = json_decode($results);

			/*
			 * check for errors on decoding
			 */
			if ($this->decodedResults === null) {
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

		} // END of runShodanAPISearch()

} // end of SoftVulnSearch class

?>