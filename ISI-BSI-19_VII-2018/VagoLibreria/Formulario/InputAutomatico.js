//esta es una funcion que encontre en internet para verificar si es chrome
//ni se que hace, la verdad me da pereza verla, pero funciona
function __isChrome(){
	var isChromium = window.chrome;
	var winNav = window.navigator;
	var vendorName = winNav.vendor;
	var isOpera = typeof window.opr !== "undefined";
	var isIEedge = winNav.userAgent.indexOf("Edge") > -1;
	var isIOSChrome = winNav.userAgent.match("CriOS");

	return (isIOSChrome || 	(isChromium !== null &&
						  	typeof isChromium !== "undefined" &&
						  	vendorName === "Google Inc." &&
						  	isOpera === false &&
						  	isIEedge === false));
}

//funcion para cambiar el formato de la fecha.
//esto es un por desorden que hay en el formato del input en chrome.
function __darFormatoFecha(fecha){
	//despedazo la fecha
	fecha = fecha.split(/[-\/\.]/);

	//la reconstrullo como DD/MM/AAAA
	return fecha[2] + "/" + fecha[1] + "/" + fecha[0];
}

//funcion para crear el texto que identifica al input
//no retorna nada
function __crearTextoIdentificadorInput(textoIdentificadorInput, renglon){

	//creando el texto que identifica al input
	var textDelInput = document.createElement("SPAN");
	textDelInput.innerText = textoIdentificadorInput;

	//insertando el identificador del input
	var columna = document.createElement("TD");
	columna.appendChild(textDelInput);
	renglon.appendChild(columna);
}

//funcion para crear el input
//retorna el input
function __crearInput(identificador, tipo, inputAutomatico, extras, renglon){

	//crear el input
	var input;

	if (tipo.toLowerCase() == "select"){
		//creo el input de tipo selector
		input = document.createElement("SELECT");

		//le introdusco las extras como si fueran opciones pero me salto
		//las primeras dos por que son los datos por defecto del patron
		for (var i = 2; i < extras.length; i++) {
			//creando la opcion
			var option = document.createElement("option");
			//agregando el texto
			option.text = extras[i];
			//insertando la opcion al input
			input.add(option);
		}
	} else {
		//creando el input generico y poniendole el tipo
		input = document.createElement("INPUT");
		input.setAttribute("type", tipo);
	}

	//agregar identificadores
    input.setAttribute("name", identificador);
    input.setAttribute("id"  , identificador);

	//insertando el input
	var columna = document.createElement("TD");
	columna.appendChild(input);
	renglon.appendChild(columna);

	//añadir un listener al input, para que se autovalide cada
	//ves que el usuario lo modifique
	input.addEventListener('input', function (evt) { inputAutomatico.validar(); });

    return input;
} 

//funcion para crear el output
//retorna el output
function __crearOuput(renglon){

	//crear el output
	var output = document.createElement("SPAN");

	//insertar el ouput
	var columna = document.createElement("TD");
	columna.appendChild(output);
	renglon.appendChild(columna);

	return output;
}

function __agregarPropiedadValor(tipo, input){

	//primero verifico si es de fecha y si es chrome, por que el formato de la fecha cambia ¬¬
	if ( tipo.toLowerCase() == "date" && __isChrome() ) {
		//aca solo se varia el formato de la fecha antes de retornarlo
		return function() {
			return __darFormatoFecha(input.input.value);
		};
		//si el input es un "select", es necesario que retorne el index, no el valor
	} else if (tipo.toLowerCase() == "select") {
		return function() {
			return input.input.selectedIndex;
		};
	} else {
		//esta es la funcion sencilla que basicamente hace: "return value;"
		return function() {
			return input.input.value;
		};
	}
}

//extrae los datos de los patrones, esto puede ser facilmente solo los por defecto
//pero es posible que hayan más, este arreglo debe contenerlos, para controlar
//posibles irregularidades o dependientes.
function __extraerExtraDelInput(arregloInput){
	var arreglo = new Array();

	for (var i = 3; i < arregloInput.length; i++) {
		arreglo[i - 3] = arregloInput[i];
	}

	return arreglo;
}

/*				--> Descripcion general <--
InputAutomatico es una clase que diseñada para estar contenida dentro de un tabla,
la idea es que sea tan autosuficiente como se pueda, ella misma valida el dato,
y muestra el mensaje de error, tambien ella misma contrulle el HTML necesario
para introducirse, en la tabla, o al menos ese es el enfoque que tiene.

                --> parametros <--
arregloInput	<- el arreglo que contiene toda la data necesaria para construir el input
tabla 			<- la tabla donde se debe de insertar todo el objeto
functionIrregularidadDeValidacion	<- funcion para manejar las validaciones
*/
function InputAutomatico(arregloInput, tabla){

	var identificador = arregloInput[0];
	var tipo = arregloInput[1];
	var textoIdentificadorInput = arregloInput[2];
	var patronPorDefecto = arregloInput[3];
	var mensajePatronPorDefecto = arregloInput[4];

	//INICIO CONSTRUCTOR
		//este "input" es para hacer referencia a this, sin usar el this,
		//es necesario para los listeners
		var input = this;

		//esta contiene un arreglo con el extra de parametros enviados desde el PHP
		input.extras = __extraerExtraDelInput(arregloInput);

		//creando el renglon para insertar todos los objetos,
		//y la inserto a la tabla
		input.renglon = document.createElement("TR");
		tabla.appendChild(input.renglon);

		//creando el texto que identifica al input
		__crearTextoIdentificadorInput(textoIdentificadorInput, input.renglon);

		//crear el input
		input.input = __crearInput(identificador, tipo, input, input.extras, input.renglon);

	    //crear el ouput
	    input.output = __crearOuput(input.renglon);

		//almacenar el patron, el mensaje de error y el identificador
		input.identificador = identificador;

		//solo por robustes,
		//esta funcion puede trabajar con mensaje de patron, o sin el ademas
		//ademas puede resivir el patron de tipo string y de tipo Regex
		input.establecerPatronYMensaje = function (patron, mensajePatron){
			//si hay un mensaje, entonces se establece, de lo contrario se queda el anterior
			//osea que si esta nulo, se ignora
			if(mensajePatron){
				input.mensajePatron = mensajePatron;
			}     

			//verifico si el patron es un Regex o un string
			if( typeof patron == "string" ) {

				//si es un string hay que arreglarlo para convertirlo en un Regex
				patron = patron.substring(1, patron.length - 1);

				//construllo el Regex a partir del patron
				patron = new RegExp(patron, "");
			}
			//se establece el patron
			input.patron = patron;
		}

		//establesco los primeros parametros enviados desde el arreglo
		//como el patron y el mensaje
		input.establecerPatronYMensaje(patronPorDefecto, mensajePatronPorDefecto);

	//FIN CONSTRUCTOR

	//INICIO FUNCIONES QUE NO SON FUNCIONES

		//el valor del input puede variar segun el tipo y el entorno
		//asi que esta funcion varia, pero debe retornar el valor del input
		input.valor = __agregarPropiedadValor(tipo, input);

	//FIN FUNCIONES QUE NO SON FUNCIONES

	//INICIO FUNCIONES

	//muestra el mensaje de error predefinido en el output
	input.mostrarError = function (){
		input.output.innerText = input.mensajePatron;
	};

	//limpia el mensaje de error del output
	input.limpiarError = function (){
		input.output.innerText = "";
	};

	//la funcion "irregularidadDeValidacion" controla las variaciones
	//que puedan haber en un input a la hora de validarlo, inicia vacio,
	//por que se asume que no hay irregularidades, desde el HTML en
	//cuestion se debe modificar esta formula para poder ejecutar las
	//irregularidades necesarias
	input.irregularidadDeValidacion = function(){};

	//valida el dato del input, automaticamente muestra
	//el mensaje e error en caso de pasar el patron
	//retorna true, si el valor es valido y falso en caso contrario
	input.validar = function (){

		//se ejecuta la funcion de irregularidad de validacion
		//estan funcion deberia hacer los cambios necesarios en la validacion
		input.irregularidadDeValidacion(input.extras);

		if( input.patron.test(input.input.value) ) {
			//Si el patron coinside con el dato
			//elimine cualquier posible mensaje
			//del output y retorno true
			input.limpiarError();
			return true;
		} else {
			//Si el patron no coinside con el
			//dato, muestro el mensaje predefinido
			//de error y reotrno falso
			input.mostrarError();
			return false;
		}
	};

	//limpia el valor del input
	input.limpiar = function(){
		input.input.value = "";
	};

	//elimina el objeto entero del html.
	//solo para aclarar, el renglon, con
	//las columnas, el input, output, TODO!!!
	input.destroy = function(){
		input.renglon.parentNode.removeChild(input.renglon);
	};
	//FIN FUNCIONES

} //FIN INPUT SIMPLE