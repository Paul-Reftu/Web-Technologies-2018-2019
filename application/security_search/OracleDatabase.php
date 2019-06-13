<?php

	class OracleDatabase {

		private $username;
		private $password;
		private $connPath;
		private $conn;

		public function __construct($username, $password, $connPath) {

			$this->username = $username;
			$this->password = $password;
			$this->connPath = $connPath;

			$this->conn = oci_connect($username, $password, $connPath);

			if (!$this->conn) {

				/* TODO Exception checking */

			}
			else {

			}

		}

		public function closeConn() {

			oci_close($this->conn);

		}

		public function getConn() {

			return $this->conn;

		}

	}

?>