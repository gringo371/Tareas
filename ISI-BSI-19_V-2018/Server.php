<?php
	$correcto;
	function validarUnDato($dato, $reglas){
		$correcto = true;

		foreach ($reglas as $regla) {
			if ( !preg_match($regla, $dato) ){
				$correcto = false;
				break;
			}
		}
		return $correcto;
	}

	//reglas
 	$reglasEmail = Array( '/^[a-z]+@[a-z]+\.[a-z]+$/' );
 	$reglasOculto = Array( "/^$/" );
 	$reglasNombre = Array( "/^[A-Za-z][a-z]+\.[A-Za-z][a-z]+$/" );
 	$reglasBithday = Array( "/(^\d{1,2}\/\d{1,2}\/\d{2}$)|(^\d{1,2}\/\d{1,2}\/\d{4}$)/" );
 	$reglasContrasena = Array( "/^.*[A-Z].*$/", "/^.*[a-z].*$/", "/[^a-zA-Z\d&]/", "/^.*\d.*$/" );

	$email = $_GET["email"];
	$oculto = $_GET["oculto"];
	$nombre = $_GET["nombre"];
	$bithday = $_GET["bithday"];
	$contrasena = $_GET["contrasena"];

	if(	validarUnDato($email, $reglasEmail) &&
		validarUnDato($oculto, $reglasOculto) &&
		validarUnDato($nombre, $reglasNombre) &&
		validarUnDato($bithday, $reglasBithday) &&
		validarUnDato($contrasena, $reglasContrasena) ){

		echo "El formulario se ha completado correctamente";
	}
	else {
		echo "Ha habido un error inesperado";
	}
?>