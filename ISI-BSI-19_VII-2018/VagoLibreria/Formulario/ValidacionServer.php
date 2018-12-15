<?php

	//esta funcion valida los datos que vienen del
	//post con los datos que vienen de la matris
	
	//retorna un arreglo con:
	//[0] <- si todos los datos son validos
	//[1] <- un arreglo con la validacion de todos los datos
	function ValidarMatrisDeDatos($matrisDeDatos){

		$vector;
		$identificador;
		$patron;
		$dato;
		$resultado;

		//un vector para insertar los resultados
		//independientes de las validaciones
		$resultados = array();

		//inicialmente asumo que todo esta bien,
		//con una que este mal, esta variable
		//cambia a false y nunca se retorna a true
		$todoCorrecto = TRUE;

		//recorro la matris para validar la data
		foreach ($matrisDeDatos as $vector) {

			//extraigo el identificador
    		$identificador = $vector[0];
    		//extraigo el patron
    		$patron = $vector[3];

    		//verifico si exite la variable en el post
    		if( !isset( $_POST[$identificador] ) ){

    			//si el dato no se envio por el post
    			//pongo todos los resultados en falso
    			$todoCorrecto = FALSE;
    			$resultado = FALSE;

    		} else { //si existe el dato entonces procedo a validarlo

    			//extraigo la variable
    			$dato = $_POST[$identificador];

    			//valido el dato
    			$resultado = preg_match($patron, $dato);

    			//si no es valido entonces pongo en falso que todo esta bien
    			if (!$resultado){
    				$todoCorrecto = FALSE;
    			}
    		}
    		//independientemente de cual sea el resultado
    		//lo agrego al vector de resultados
    		$resultados[$identificador] = $resultado;
		}
		//retorno si todos estaban bien, y retorno
		//el vector con todos los resultados
		return array($todoCorrecto, $resultados);
	}// FIN ValidarMatrisDeDatos

?>