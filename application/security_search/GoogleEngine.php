<?php

	class GoogleEngine {

		private $baseUrl;
		private $cx;

		public function __construct($baseUrl, $cx) {

			$this->baseUrl = $baseUrl;
			$this->cx = $cx;

		}

		public function getBaseUrl() {

			return $this->baseUrl;

		}

		public function getCx() {

			return $this->cx;

		}

		public function setBaseUrl($baseUrl) {

			$this->baseUrl = $baseUrl;

		}

		public function setCx($cx) {

			$this->cx = $cx;

		}

	}

?>