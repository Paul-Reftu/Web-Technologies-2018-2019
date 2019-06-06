<?php

	require_once($_SERVER["DOCUMENT_ROOT"] . "/Web-Tehnologies-2018-2019/application/soft_vuln/ExploitSeeker.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/Web-Tehnologies-2018-2019/application/soft_vuln/NvdCrawler.php");

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

	/*
	 * class whose instance represents Security Alerter's API (version 1)
	 *
	 * currently supported requests:
	 *  GET /api/v1.php/exploits?description={exploit_description}[&page={page_no}][&format={response_format}]
	 *
	 * Remark: No. of returned exploits is maximum 100 per page;
	 *  Currently supported response formats: json, html
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

			$exploitId = $this->getExploitId($uri);

			switch ($_SERVER["REQUEST_METHOD"]) {

				case "GET": {

					if ($exploitId === null && !isset($_GET["description"])) {

						header("HTTP/1.1 400 Bad Request");
						exit();

					}
					else {

						if ($exploitId === null) {

							if (isset($_GET["page"]))
								$exploitPage = $_GET["page"];
							else
								$exploitPage = 1;

							$exploitSeeker = new ExploitSeeker($exploitPage, null, null);

							$shodanJson = $exploitSeeker->runShodanAPISearch($_GET["description"]);

						}
						else {

							$exploitSeeker = new ExploitSeeker(1, null, null);

							$shodanJson = $exploitSeeker->runShodanAPISearch($exploitId)->matches[0];

							if (sizeof($shodanJson->cve) === 0 || $shodanJson->cve[0] !== $exploitId)
								$shodanJson = null;

						}

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

						if (!isset($_GET["format"])) {

							header("Content-Type: application/json; charset=UTF-8");
							header("HTTP/1.1 200 OK");

							echo json_encode($shodanJson, JSON_PRETTY_PRINT);

						}
						else {

							switch ($_GET["format"]) {

								case "json": {

									header("Content-Type: application/json; charset=UTF-8");
									header("HTTP/1.1 200 OK");

									echo json_encode($shodanJson, JSON_PRETTY_PRINT);

									break;

								} // end of case json

								case "html": {

									header("Content-Type: text/html; charset=UTF-8");
									header("HTTP/1.1 200 OK");

									echo $this->jsonToHTML($shodanJson);

									break;

								} // end of case html

								default: {

									header("Content-Type: application/json; charset=UTF-8");
									header("HTTP/1.1 200 OK");

									echo json_encode($shodanJson, JSON_PRETTY_PRINT);

									break;

								} // end of default case

							} // end of switch for response format

						}		

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

		private function getExploitId($uri) {

			if (!$this->isURIValid($uri))
				return null;

			$uri_parts = explode("/", $uri);

			$pageNameIndex = $this->findCurrPageName($uri_parts);

			if (sizeof($uri_parts) - 1 === $pageNameIndex + 1)
				return null;

			return $uri_parts[$pageNameIndex + 2];

		}

		private function jsonToHTML($json) {

			$html = "<table style='border: 4px dashed aqua'>";

			foreach ($json as $key => $val) {

				$html .= "<tr style='border: 1px solid black' valign='top'>";

				if (!is_numeric($key)) {

					$html .= "<td style='border: 1px solid black'>" . $key . ":</td>";
					$html .= "<td style='border: 1px solid black'>";

				}
				else {

					$html .= "<td style='border: 1px solid black' colspan='2'>";

				}

				if (is_object($val) || is_array($val)) {

					$html .= $this->jsonToHTML($val);

				}
				else {

					$html .= $val;

				}

				$html .= "</td>";

				$html .= "</tr>";

			}

			$html .= "</table>";

			return $html;

		}

	}

	new API_V1();

	

?>