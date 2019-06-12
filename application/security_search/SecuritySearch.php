<?php

	require_once("GoogleEngine.php");
	require_once("SecurityView.php");
	require_once("SecurityController.php");

	class SecuritySearch {

		public function __construct() {

			$googleEngine = new GoogleEngine("https://cse.google.com/cse.js?cx=", "012462141728568968124:ajqilsdfw3s");
			$securityView = new SecurityView();
			$securityController = new SecurityController($googleEngine, $securityView);

			$securityController->updateView();

		}

	}

?>