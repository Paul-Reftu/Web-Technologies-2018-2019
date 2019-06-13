<?php

	require_once("ExploitSeeker.php");
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

		/*
		 * construct an object of type 'SoftVulnSearch', doing so running Shodan's API to get information w.r.t a particular given exploit and then providing the view to those results
		 */
		public function __construct() {

			$this->description = $_GET["description"];

			/* 
			 * get current page number - default is 1
			 */
			if (isset($_GET["page"]))
				$this->currPage = $_GET["page"];
			else
				$this->currPage = 1;

			$exploitSeeker = new ExploitSeeker($this->currPage, $this->resultsPerPage, $this->shodanResultsPerPage);

			$this->decodedResults = $exploitSeeker->runShodanAPISearch($this->description);

			if ($this->decodedResults !== null) {
				try {

					/*
				 	 * get the count of total exploits found through our query
				 	 */
					$this->totalResults = $this->decodedResults->total;

					$model = $exploitSeeker->getExploitList();

					$view = new ExploitView($this->description, $this->currPage, $this->noOfShownPageLinks, $this->totalResults, $this->resultsPerPage, $this->maxNoOfPropReadChars);

					$controller = new ExploitController($model, $view);

					$controller->updateView();

				} 
				catch (NotAnExploitException | NotAnExploitListException | NotAnExploitViewException $e) {
					echo $e->errorMessage();
				}
			} // end of if decodedResults !== null

		} // END of __construct()

} // end of SoftVulnSearch class

?>