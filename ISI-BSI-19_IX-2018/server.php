<?php

	$metros = $_POST["metros"];

	if ( preg_match("/^\d+$/", $metros) ){
		echo $metros * 3,28084;
	} else {
		echo '###';
	}
?>



