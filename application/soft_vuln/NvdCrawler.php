<?php

	/**
	 * @author Paul-Reftu
	 */

	require_once($_SERVER["DOCUMENT_ROOT"] . "/Web-Tehnologies-2018-2019/application/auxiliaries/simplehtmldom_1_9/simple_html_dom.php");

	/**
	 * class whose instance represents a crawler that collects
	 *  information w.r.t a given set of exploits - information
	 *  found in the National Vulnerability Database
	 */
	class NvdCrawler {

		/*
		 * the url to the source of the exploit information
		 */
		const NVD_ROOT_URL = "https://nvd.nist.gov/vuln/detail/";
		/*
		 * the id of the 'div' container that contains the information we seek w.r.t a given exploit
		 *
		 * THIS ID MAY CHANGE IN THE HOST WEBSITE - IF THAT HAPPENS, THAT WILL BE A PRIMARY SOURCE OF ERROR
		 */
		const NVD_REFERENCE_DIV_ID = "p_lt_WebPartZone1_zoneCenter_pageplaceholder_p_lt_WebPartZone1_zoneCenter_VulnerabilityDetail_VulnFormView_VulnHyperlinksPanel";

		/*
		 * the Common Vulnerability ID of the currently sought exploit
		 */
		private $vulnCveId;

		/**
		 * @param $vulnCveId
		 * construct an object of type 'NvdCrawler' w/ the given CVE ID
		 */
		public function __construct($vulnCveId) {

			$this->vulnCveId = $vulnCveId;

		} // END of __construct()

		/**
		 * @return an array of references w.r.t the exploit identified by the currently-declared 'vulnCveId' (i.e, the CVE id of said exploit), or null if $this->vulnCveId is not set (i.e, if it is null)
		 *
		 * crawl through the National Vulnerability Database
		 *  and obtain helpful references w.r.t a particular exploit
		 */
		public function collectRefs() {

			if ($this->vulnCveId !== null) {

				$cve = $this->vulnCveId;

				$references = array();

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

				return $references;

			} // end of '$this->vulnIds !== null' conditional

			return null;

		} // END of collectRefs()

		/**
		 * @return the CVE id of the target vulnerability of this crawler
		 */
		public function getVulnCveId() {

			return $this->vulnCveId;

		} // END of getVulnCveId()

		/**
		 * @param $vulnCveId
		 */
		public function setVulnCveId($vulnCveId) {

			$this->vulnCveId = $vulnCveId;

		} // END of setVulnCveId()

	} // END of NvdCrawler class

?>