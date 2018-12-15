<?php
//obviamente que importo la libreria que pretendo probar
include "MySqlManager.php";


//cambio la base de datos a la que pretendo conectar
//esto para evitar destruir cualquier cosa importante en base de datos
global $__DATABASE__;
$__DATABASE__ = "mysql";

$DATA_BASE = "test_db_blackbox";


//creo la base de datos.
$consulta = "CREATE DATABASE $DATA_BASE;";
$mensajeExitoso = "Se creo la base de datos.";
$mensajeFallido = "No se pudo crear la base de datos.";
ejecutarConsulta($consulta, $mensajeExitoso, $mensajeFallido);
echo "<br>";

//cambio la base de datos a la que pretendo conectar
//esto para trabajar en la base de datos de la blackbox
$__DATABASE__ = $DATA_BASE;

//creo una tabla
$consulta = "
	CREATE TABLE Persona
	(
		nombre varchar(50),
		edad   int
	);
";
$mensajeExitoso = "Se creo la tabla \"Persona\".";
$mensajeFallido = "No se pudo crear la tabla \"Persona\".";
ejecutarConsulta($consulta, $mensajeExitoso, $mensajeFallido);
echo "<br>";



//AHORA EJECUCIONES SILENCIOSAS

//inserto un dato
$consulta = "
	INSERT INTO Persona (nombre, edad)
	VALUES 				('Luis', '15')
";
$resultado = ejecutarConsultaSilencioso($consulta);
if ($resultado == TRUE) {
	echo "El Luis con edad de 15 se inserto correctamente";
} else {
	echo "error: " . $resultado;
}
echo "<br>";

//AHORA EJECUCIONES UNA EJECUCION SONORA

//inserto otro dato
$consulta = "
	INSERT INTO Persona (nombre, 	edad)
	VALUES 				('Fiorella', '85')
";
$mensajeExitoso = "Se inserto el Fiorella  con edad de 85.";
$mensajeFallido = "No se pudo insertar Fiorella.";
ejecutarConsulta($consulta, $mensajeExitoso, $mensajeFallido);
echo "<br>";

//AHORA EJECUCIONES UNA EJECUCION SILENCIOSA

//muestro el contenido de la tabla persona
$consulta = "
	SELECT *
	FROM Persona
";
echo "Contenido de la tabla Persona:<br>";
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
echo "<br>";


//modifico la edad de Fiorella
$consulta = "
	UPDATE 	Persona
	SET 	edad = '30'
	WHERE 	nombre = 'Fiorella'
";
$mensajeExitoso = "Se acctualizo la edad de Fiorella a 30.";
$mensajeFallido = "No se acctualizo la edad de Fiorella.";
ejecutarConsulta($consulta, $mensajeExitoso, $mensajeFallido);
echo "<br>";


//muestro el contenido de la tabla persona
$consulta = "
	SELECT nombre, edad
	FROM Persona
";
echo "Contenido de la tabla Persona:<br>";
ejecutarSelectComoJson($consulta);
echo "<br>";

//cambio de nuevo la base de datos,
//para eliminar desde otra base de datos
$__DATABASE__ = "mysql";


//elimino la base de datos.
$consulta = "DROP DATABASE $DATA_BASE;";
$mensajeExitoso = "se elimino la base de datos.<br>";
$mensajeFallido = "no se pudo eliminar la base de datos.<br>";
ejecutarConsulta($consulta, $mensajeExitoso, $mensajeFallido);

?>