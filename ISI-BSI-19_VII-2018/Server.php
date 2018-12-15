<?php

	include "VagoLibreria/Formulario/ValidacionServer.php"; //validar en el server
	include "VagoLibreria/MySql/MySqlManager.php"; //para hacer las consultas

	$datos = array(
		array(
		  	"titulo",
		  	"text",
		  	"Titulo:",
		  	"/^.{1,40}$/",
		  	"El titulo no puede tener más de 40 caracteres."
		),
		array(
		  	"autor",
		  	"text",
		  	"Autor:",
		  	"/^.{1,30}$/",
		  	"El autor no puede tener más de 30 caracteres."
		),
		array(
		  	"editorial",
		  	"text",
		  	"Editorial:",
		  	"/^.{1,20}$/",
		  	"La editorial no puede tener más de 20 caracteres."
		),
		array( 
		  	"precio",
		  	"text",
		  	"Precio:",
		  	"/^\d{1,5}(\.\d{1,2})?$/",
		  	"Es necesario ingresar un número decimal, este puede tener maximo cinco y dos decimales, separados por un punto."
		)
	);

	$transaccion = $_POST["transaccion"];

	//0 -> para pedir la matris
	//1 -> ingresar
	//2 -> modificar
	//3 -> eliminar
	//4 -> select
	switch ($transaccion) {
		case 0:
			print_r ( json_encode($datos) );
			break;

		case 1:

			$titulo = $_POST["titulo"];
			$autor = $_POST["autor"];
			$editorial = $_POST["editorial"];
			$precio = $_POST["precio"];

			$consulta = "
				INSERT INTO Libro (titulo,     autor,    editorial,     precio)
				VALUES 			  ('$titulo', '$autor', '$editorial', '$precio')
			";
			$mensajeExitoso = "El libro \"$titulo\" se inserto correctamente.";
			$mensajeFallido = "No se pudo insertar el libro \"$titulo\".";

			$resultado = ejecutarConsulta($consulta, $mensajeExitoso, $mensajeFallido);
			
			break;
			
		case 2:

			$titulo = $_POST["titulo"];
			$autor = $_POST["autor"];
			$editorial = $_POST["editorial"];
			$precio = $_POST["precio"];

			$consulta = "
				UPDATE 	Libro
				SET 	autor = '$autor', 
						editorial = '$editorial'
				WHERE 	titulo = '$titulo'
			";
			$mensajeExitoso = "El libro \"$titulo\" se modifico correctamente.";
			$mensajeFallido = "No se pudo modificar el libro \"$titulo\".";

			$resultado = ejecutarConsulta($consulta, $mensajeExitoso, $mensajeFallido);
			
			break;

		case 3:

			$titulo = $_POST["titulo"];

			$consulta = "
				UPDATE 	Libro
				SET 	eliminado = 1
				WHERE 	titulo = '$titulo'
			";
			$mensajeExitoso = "El libro \"$titulo\" se elimino correctamente.";
			$mensajeFallido = "No se pudo eliminar el libro \"$titulo\".";

			$resultado = ejecutarConsulta($consulta, $mensajeExitoso, $mensajeFallido);
			
			break;

		case 4:
			
			$consulta = "
				SELECT 	titulo, autor, editorial, precio
				FROM 	Libro
				WHERE 	eliminado = 0
			";

			ejecutarSelectComoJson($consulta);
			break;
	}

?>