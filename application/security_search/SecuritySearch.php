<?php

	/**
	 * @author Paul-Reftu
	 */

	require_once("GoogleEngine.php");
	require_once("SecurityView.php");
	require_once("SecurityController.php");

	/**
	 * class whose instance represents the security info. search functionality of the app.
	 */ 
	class SecuritySearch {

		/**
		 * initialize a Google search engine for our search capabilities, as well as the MVC that handles everything
		 */
		public function __construct() {

			$googleEngine = new GoogleEngine("https://cse.google.com/cse.js?cx=", "012462141728568968124:ajqilsdfw3s");
			$securityView = new SecurityView();
			$securityController = new SecurityController($googleEngine, $securityView);

			$securityController->updateView();

		} // END of __construct()

	} // end of SecuritySearch class

?>