<?php

	require_once("GoogleEngine.php");
	require_once("OracleDatabase.php");

	class SecurityView {

		public function __construct() {}

		public function printSecurityInfo($googleEngine) {

			echo "<script>
				  (function() {
				    var cx = '" . $googleEngine->getCx() . "';
				    var gcse = document.createElement('script');
				    gcse.type = 'text/javascript';
				    gcse.async = true;
				    gcse.src = '" . $googleEngine->getBaseUrl() . "' + cx;
				    var s = document.getElementsByTagName('script')[0];
				    s.parentNode.insertBefore(gcse, s);
				  })();
				</script>
				<gcse:search></gcse:search>";

			echo "
				<form action=\"security.php\" method=\"POST\">
					Predict me! <br/>

					<input type=\"text\" name=\"keyword\"/>
					<input type=\"submit\"/> <br/>
				</form>
				<br/>
			";

			if (isset($_POST["keyword"])) {

				$keyword = $_POST["keyword"];

				$db = new OracleDatabase("STUDENT", "student0", "localhost/XE");
				$dbConn = $db->getConn();

				$query = "
						BEGIN
							getPredictions(:key, 5, :cursor);
						END;
						"
				;

				$stmt = oci_parse($dbConn, $query);

				$predictionsCursor = oci_new_cursor($dbConn);
				oci_bind_by_name($stmt, ":key", $keyword);
				oci_bind_by_name($stmt, ":cursor", $predictionsCursor, -1, OCI_B_CURSOR);

				oci_execute($stmt);

				echo "Predictions: " . "<br/><br/>";

				if (oci_execute($stmt)) {

					oci_execute($predictionsCursor, OCI_DEFAULT);

					echo "<table border='1'>";
					while ($rs = oci_fetch_array($predictionsCursor, OCI_RETURN_NULLS+OCI_ASSOC)) {

						echo "<tr>";

						foreach ($rs as $row) {

							echo "<td>" . ($row !== null ? htmlentities($row, ENT_QUOTES) : "&nbsp") . "</td>";

						}

						echo "</tr>";

					}
					echo "</table>";

				}

			}

		}

	}


?>