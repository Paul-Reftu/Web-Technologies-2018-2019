<?php

	/**
	 * @author Paul-Reftu
	 */

	require_once("GoogleEngine.php");
	require_once("SecurityView.php");

	/**
	 * class whose instance represents the controller w.r.t the security article/info. functionality of the application
	 */
	class SecurityController {

		private $model;
		private $view;

		/**
		 * @param $model
		 * @param $view
		 */
		public function __construct($model, $view) {

			$this->model = $model;
			$this->view = $view;

		} // END of __construct()

		/**
		 * @param $newModel
		 * update the current model
		 */
		public function updateModel($newModel) {

			$this->model = $newModel;

		} // END of updateModel()

		/**
		 * update the current view
		 */
		public function updateView() {

			$this->view->printSecurityInfo($this->model);

		} // END of updateView()

	} // end of SecurityController class


?>