<?php

	require_once($_SERVER["DOCUMENT_ROOT"] . "/Web-Tehnologies-2018-2019/application/auxiliaries/simplehtmldom_1_9/simple_html_dom.php");

	/*
	 * class whose instance represents a crawler that collects
	 *  information w.r.t a given set of exploits - information
	 *  found in the National Vulnerability Database
	 */
	class NvdCrawler {

		const NVD_ROOT_URL = "https://nvd.nist.gov/vuln/detail/";
		const NVD_REFERENCE_DIV_ID = "p_lt_WebPartZone1_zoneCenter_pageplaceholder_p_lt_WebPartZone1_zoneCenter_VulnerabilityDetail_VulnFormView_VulnHyperlinksPanel";

		private $vulnCveId;

		/*
		 * TODO Exception checking
		 */
		public function __construct($vulnCveId) {

			if ($vulnCveId !== null) {

				$this->vulnCveId = $vulnCveId;

			}

		}

		/*
		 * TODO Exception checking
		 *
		 * crawl through the National Vulnerability Database
		 *  and obtain helpful references w.r.t a particular exploit
		 */
		public function collectRefs() {

			if ($this->vulnCveId !== null) {

				//$startTime = microtime(true);

				$cve = $this->vulnCveId;

				$references = array();

				if ($cve !== null) {

					$html = file_get_html(self::NVD_ROOT_URL . $cve);

					$div = $html->find("div[id=" . self::NVD_REFERENCE_DIV_ID . "]", 0);

					$ref_table = $div->find("table", 0);

					$ref_table_body = $ref_table->find("tbody", 0);

					foreach ($ref_table_body->find("tr") as $ref_table_body_row) {

						$ref_table_body_row_data = $ref_table_body_row->find("td", 0);

						$ref_anchor = $ref_table_body_row_data->find("a", 0);

						$ref_href = $ref_anchor->href;

						array_push($references, $ref_href);

					} // end of loop through table rows

				} // end of '$cve !== null' conditional

				/*
				$endTime = microtime(true);
				$elapsedTime = $endTime - $startTime;

				if ($elapsedTime < 0.3)
					$markColor = "green";
				else if ($elapsedTime < 0.6)
					$markColor = "orange";
				else 
					$markColor = "red";

				$mark = "<span style='color:" . $markColor . "'>" . $elapsedTime . "</span>";

				echo "Time taken for reference collection: " . $mark . "<br/>";
				*/

				return $references;

			} // end of '$this->vulnIds !== null' conditional

			return null;

		} // END of collectRefs()

		public function getVulnCveId() {

			return $this->vulnCveId;

		}

		public function setVulnCveId($vulnCveId) {

			$this->vulnCveId = $vulnCveId;

		}

	} // END of NvdCrawler class

?>