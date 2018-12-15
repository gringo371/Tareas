<?php

	$__correcto;
	
	//valida un unico dato con una regla
	function validarUnDato($dato, $regla){
		return !preg_match($regla, $dato);
	}

	//valida un unico dato con un conjunto de reglas
	function validarDatoConReglas($dato, $reglas){
		gloval $__correcto;
		$__correcto = true;

		foreach ($reglas as $regla) {
			if ( validarUnDato($dato, $regla) ){
				$__correcto = false;
				break;
			}
		}
		return $__correcto;
	}


?>