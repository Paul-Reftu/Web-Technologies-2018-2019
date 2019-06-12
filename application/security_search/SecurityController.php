<?php

	require_once("GoogleEngine.php");
	require_once("SecurityView.php");

	class SecurityController {

		private $model;
		private $view;

		/*
		 * TODO Exception checking
		 */
		public function __construct($model, $view) {

			$this->model = $model;
			$this->view = $view;

		}

		public function updateModel($newModel) {

			$this->model = $newModel;

		}

		public function updateView() {

			$this->view->printSecurityInfo($this->model);

		}

	}


?>