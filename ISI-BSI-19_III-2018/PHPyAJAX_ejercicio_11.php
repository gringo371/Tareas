<?php

	$numero1 = $_POST["numero1"];
	$numero2 = $_POST["numero2"];

	if($numero1 == ''){
		$numero1 = 0;
	}

	if($numero2 == ''){
		$numero2 = 0;
	}

	echo $numero1 + $numero2;

?>