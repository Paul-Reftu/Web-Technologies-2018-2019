<?php

	/**
	 * @author Paul-Reftu
	 */

	/**
	 * class whose instance represents the usage info. of a Google search engine
	 */
	class GoogleEngine {

		private $baseUrl;
		private $cx;

		/**
		 * @param $baseUrl
		 * @param $cx
		 */
		public function __construct($baseUrl, $cx) {

			$this->baseUrl = $baseUrl;
			$this->cx = $cx;

		} // END of __construct()

		/**
		 * @return the base URL of the search engine
		 */
		public function getBaseUrl() {

			return $this->baseUrl;

		} // END of getBaseUrl()

		/**
		 * @return the identifier of the search engine
		 */
		public function getCx() {

			return $this->cx;
 
		} // END of getCx()

		/**
		 * @param $baseUrl
		 */
		public function setBaseUrl($baseUrl) {

			$this->baseUrl = $baseUrl;

		} // END of setBaseUrl()

		/**
		 * @param $cx
		 */
		public function setCx($cx) {

			$this->cx = $cx;

		} // END of setCx()

	} // end of GoogleEngine class

?>