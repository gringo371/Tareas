<?php

	$matrix = array(
		array(
		  	"nombre",
		  	"text",
		  	"Nombre:",
		  	"/^[a-zA-Z\s]{5,50}$/",
		  	"El nombre solo puede conteneer letras y no deben ser más de 50 y no menos de 5"
		),
		array(
		  	"cedula",
		  	"text",
		  	"Cedula:",
		  	"/^.{3}$/",
		  	"La cedula judirica debe tener 3 caracteres.",
		  	"/^.{4}$/",
		  	"La cedula ficica debe tener 4 caracteres.",
		  	"/^.{5}$/",
		  	"La cedula extrangera debe tener 5 caracteres."
		),
		array(
		  	"tipoCedula",
		  	"select",
		  	"Tipo de cedula:",
		  	"/^.*$/",
		  	"Es necesario ingresar una dirección.",
		  	"Judirico",
		  	"Ficica",
		  	"Extrangera"
		),
		array( 
		  	"edad",
		  	"number",
		  	"Edad:",
		  	"/^[\d]{1,3}$/",
		  	"es necesario ingresar una edad"
		)
	);

	print_r ( json_encode($matrix) );

?>