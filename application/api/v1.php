<?php

	require_once($_SERVER["DOCUMENT_ROOT"] . "/Web-Tehnologies-2018-2019/application/soft_vuln/ExploitSeeker.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/Web-Tehnologies-2018-2019/application/soft_vuln/NvdCrawler.php");

	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

	/*
	 * class whose instance represents Security Alerter's API (version 1)
	 *
	 * currently supported requests:
	 *  GET /api/v1.php/exploits?description={exploit_description}
	 */
	class API_V1 {

		private $currPageName = "v1.php";
		private $exploitsResourceName = "exploits";


		public function __construct() {

			$uri = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

			if (!$this->isURIValid($uri)) {

				header("HTTP/1.1 404 Not Found");
				exit();		

			}

			$requestMethod = $_SERVER["REQUEST_METHOD"];

			switch ($_SERVER["REQUEST_METHOD"]) {

				case "GET": {

					if (!isset($_GET["description"])) {

						header("HTTP/1.1 400 Bad Request");
						exit();

					}
					else {

						$exploitSeeker = new ExploitSeeker(1, 5, 100);

						$shodanJson = $exploitSeeker->runShodanAPISearch($_GET["description"]);
						$exploits = $shodanJson->matches;

						/*
						 * Until an alternative solution to get the needed
						 *  references of an exploit is found, the following 
						 *  action displays unbelievably poor efficiency
						 *  (~1s/exploit), and is therefore impossible
						 *  to use in our API
						 */

						/*
						$nvdCrawler = new NvdCrawler(null);

						$i = 0;

						foreach ($exploits as &$exploit) {

							if ($i++ === 5)
								break;

							$nvdCrawler->setVulnCveId($exploit->cve[0]);

							$exploit->refs = $nvdCrawler->collectRefs();

						}

						$shodanJson->matches = $exploits;
						*/

						header("HTTP/1.1 200 OK");
						echo json_encode($shodanJson, JSON_PRETTY_PRINT);

						

					}

					break;
				} // end of case "GET"

				case "POST": {

					header("HTTP/1.1 403 Forbidden");
					exit();

				} // end of case "POST"

				case "PUT": {

					header("HTTP/1.1 403 Forbidden");
					exit();

				} // end of case "PUT"

				case "DELETE": {

					header("HTTP/1.1 403 Forbidden");
					exit();					

				} // end of case "DELETE"

				default: {

					header("HTTP/1.1 404 Not Found");
					exit();

				} // end of default case

			} // end of switch for http request method

		}

		private function findCurrPageName($list) {

			for ($i = 0; $i < sizeof($list); $i++)
				if ($list[$i] === $this->currPageName)
					return $i;

			return -1;

		}

		private function isURIValid($uri) {

			$uri_parts = explode("/", $uri);

			$pageNameIndex = $this->findCurrPageName($uri_parts);

			if (sizeof($uri_parts) - 1 === $pageNameIndex)
				return false;

			if ($uri_parts[$pageNameIndex + 1] !== $this->exploitsResourceName)
				return false;

			return true;

		}

	}

	new API_V1();

	

?>