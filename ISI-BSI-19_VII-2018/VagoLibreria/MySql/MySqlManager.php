<?php
// esta libreria controla todas las conexion a la base de datos,
// para la felicidad y facilidad de la mayor de todo el mundo,
// desde aqui se puede cambiar los datos de entrada a la base
//de datos de todas las paginas web, o procedimientos del server.

	$__HOST__ = "localhost";
	$__USER__ = "root";
	$__PASSWORD__ = "";
	$__DATABASE__ = "ISI-BSI-19_VII-2018";

	//funcion para crear el SQL basico
	function inicializarSQL(){
		global $__HOST__, $__USER__, $__PASSWORD__, $__DATABASE__;

		//creo la conexion
		$conexion = new mysqli($__HOST__, $__USER__, $__PASSWORD__, $__DATABASE__);

		//verifico si no fallo
		if (!$conexion) {
		    die("Error de conexion");
		}

		//de nuevo verifico si no fallo
		if ($conexion->connect_error) {
		    die("Error de conexion: <br>" . $conexion->connect_error);
		}

		//pongo el charset
		$conexion->set_charset("utf8");

		//retorno la conexion
		return $conexion;
	} //fin inicializarSQL

	/*Esta funcion ejecuta alguna consulta a la base de datos,
	validando si la consulta tubo o no un efecto en la base de datos.
	Esta planeada para usarse en cualquier consulta que ejecute
	modificiaciones en la base de datos
	No retorna nada, solo muestra los mensajes enviados.
		$consulta       <- consulta a ejecutar
		$mensajeExitoso <- mensaje en caso de que haya sido exitosa la consulta
		$MensajeFallido <- mensaje en caso de que haya fallado la consulta
	*/
	function ejecutarConsultaSilencioso($consulta){
		//creo el desorden del sql
		$conexion = inicializarSQL();

		//ejecuto la consulta
		$resultado = $conexion->query($consulta);

		//verifico si sirvio
		if ($resultado === TRUE) {

			//verifico si hubo algun cambio en base de datos.
			if(($conexion->affected_rows > 0)){
				//si hubieron cambios, retorno TRUE
				return TRUE;
			} else {
				//si no hubieron cambios retorno FALSE
				return FALSE;
			}

		} else { //si no sirvio del todo, devuelvo el mensaje de el sql
			return $conexion->error;
		}

		//cierro la conexion
		$conexion->close();
	} // fin ejecutarConsultaSilencioso

	/*Esta funcion ejecuta alguna consulta a la base de datos,
	validando si la consulta tubo o no un efecto en la base de datos.
	Esta planeada para usarse en cualquier consulta que ejecute
	modificiaciones en la base de datos
	No retorna nada, solo muestra los mensajes enviados.
		$consulta       <- consulta a ejecutar
		$mensajeExitoso <- mensaje en caso de que haya sido exitosa la consulta
		$MensajeFallido <- mensaje en caso de que haya fallado la consulta
	*/
	function ejecutarConsulta($consulta, $mensajeExitoso, $MensajeFallido){

		//ejecuto la consulta
		$resultado = ejecutarConsultaSilencioso($consulta);

		//verifico si sirvio
		if ( is_string($resultado) ){
			//si retorna un string, es por que salio 
			//del todo mal y muestro el mensaje
			echo($MensajeFallido . " <br>por que: <br>" . $resultado);

		} else if ($resultado == FALSE) {
			//si retorna FALSE, solo muestro el mensaje fallido,
			//por que no se en este punto que salio mal
			echo($MensajeFallido);

		} else {
			//si retorna TRUE, fue exitoso y
			//muestro el mensaje de exito
			echo($mensajeExitoso);
		}

	} // fin ejecutarConsulta

	//Esta funcion ejecuta alguna consulta a la base de datos.
	//Esta planeada para usarse con SELECT.
	//Retorna un array con los resultados
		//array[0] <- si fue exitoso o no
		//array[1] <- el resultado de la consulta o el mensaje de error
	//$consulta    <- consulta a ejecutar
	function ejecutarSelectSilencioso($consulta){
		//creo el desorden del sql
		$conexion = inicializarSQL();

		//ejecuto la consulta
		$resultado = $conexion->query($consulta);

		$respuesta;

		//verifico si no hay errores antes de imprimir
		if($resultado){
			//creo un vector con el resultado
			//correcto y el resultado del sql
			$respuesta = array(
				true,
				$resultado
			);

		} else {
			///creo un vector con el resultado
			//incorrecto y el error del sql
			$respuesta = array(
				false,
				$conexion->error
			);
		}

		//cierro la conexion
		$conexion->close();

		return $respuesta;
	} // fin ejecutarSelectSilencioso

	//Toma un resultado de una consulta select y
	//extrae los datos para combertirlo en un json
	function desdeSqlResouldHastaJson ($resultado){
		$tupla;
		$conjuntoDeDatos = array();

		//extraigo las tuplas del resultado del sql
		while ( $tupla = $resultado->fetch_assoc() ) {

			//meto las tuplas en un array
			$conjuntoDeDatos[] = $tupla;
		}
		//devuelvo el array como un json
		$respuestaJson = json_encode($conjuntoDeDatos);

		return $respuestaJson;
	} // fin desdeSqlResouldHastaJson

	//Esta funcion ejecuta alguna consulta a la base de datos.
	//Esta planeada para usarse con SELECT.
	//Retorna el un jason del resultado enviado por la base de datos.
	//$consulta       <- consulta a ejecutar
	function ejecutarSelectComoJson($consulta){

		//ejecuto la consulta
		$resultado = ejecutarSelectSilencioso($consulta);

		//verifico el resultado de la respuesta
		if($resultado[0]){
			//combierto el resltado de la consulta a Json
			$respuestaJson = desdeSqlResouldHastaJson($resultado[1]);

			//imprimo la consulta
			print $respuestaJson;
		} else {
			//imprimo el error
			echo "Error: " . $resultado[1]; 
		}
	} // fin ejecutarSelectComoJson

?>