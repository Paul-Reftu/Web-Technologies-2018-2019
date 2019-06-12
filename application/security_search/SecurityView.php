<?php

	require_once("GoogleEngine.php");

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

		}

	}


?>