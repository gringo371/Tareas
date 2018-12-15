//funcion para pedir la matris de inputs
function __pedirDatos(link, datosDeEnvio, funcionParaEjecutarAlFinal, formulario){

	$.post(link, datosDeEnvio, function(respuesta){
		//tomo la respuesta resivido y la combierto en un arreglo.
		//este arreglo deberia tener toda la informacion necesario
		//para construir los inputs.
		var matrisInputs = JSON.parse(respuesta);

		//envio toda la data a continuar con el constructor
		__construirInputs(funcionParaEjecutarAlFinal, formulario, matrisInputs);
	});
}

//esta funcion es la continuacion del constructor del form manager
//el detalle es que el constructor del formulario es asincronico 
function __construirInputs(funcionParaEjecutarAlFinal, formulario, matrisInputs){

	for (arregloInput of matrisInputs) {

		//contrullo el input
		var input = new InputAutomatico
		(
			arregloInput,
			formulario.tabla
		);

		//agrego el input al listado inputs del form manager
		formulario.inputs[ arregloInput[0] ] = input;
	}
	//ejecuto la funcion asincronica del html
	funcionParaEjecutarAlFinal();

}

// constructor
function FormularioAutomatico(link, datosDeEnvio, tabla, funcionParaEjecutarAlFinal){

	//INICIO CONSTRUCTOR
		var formulario = this;

		//agrego el listado de inputs vacio, este se deberia de llenar en asincronia
		formulario.inputs = {};

		//guardo la tabla en la que el forma manager deberia trabajar
		formulario.tabla = tabla;

		__pedirDatos(link, datosDeEnvio, funcionParaEjecutarAlFinal, formulario);
	//FIN CONSTRUCTOR

	//INICIO FUNCIONES

		//retorna un input a partir de su identificador
		formulario.getInput = function(identificador){
			return formulario.inputs[identificador];
		}

		//fuerza a validar todos los inputs
		//retorna true si todos son validos, retorna false si al menos uno es invalido
		formulario.validar = function(){
			//declaro una bandera para poder validar
			var valido;

			//itero los inputs
			for (var identificador in formulario.inputs) {

				//verifico si el input es valido
				valido = formulario.inputs[identificador].validar();		

				//si el input no es valido termino el bucle
				if(!valido){
					break;
				}

			}
			//retorno la valides de todos los inputs
			return valido;
		};

		//extrae el identificador y el valor de todos los inputs,
		//y los agrega a un arreglo quedando como identificador=>valor
		//retorna una arreglo con todos los valores y identificadores de todos los inputs.
		formulario.extraerTodo = function(){

			//creo un arreglo para agregar los valores
			var arreglo = {}; 

			//itero los inputs
			for (var identificador in formulario.inputs) {

				//agrego el valor con la llave del
				//identificador del propio input
				arreglo[identificador] = formulario.inputs[identificador].valor();
			}

			//retorno el arreglo con todos los inputs
			return arreglo;
		}

		//limpia todos los inputs
		formulario.limpiar = function(){
			//itero los inputs
			for (input of formulario.inputs) {
				//llamo a la funcion de limpiar de los inputs
				input.limpiar();
			}
		}

		//destrulle un input a partir de su identificador
		formulario.destroyInput = function(identificador){


			formulario.inputs[identificador].destroy();

			formulario.inputs[identificador] = null;
		}	

		//funcion para destruir el formulario
		formulario.destroy = function(){
			//itero los inputs
			for (input of formulario.inputs) {
				//llamo a la funcion destroy de los inputs
				input.destroy();
			}
		}		

	//FIN FUNCIONES

} // FIN FORMULARIO AUTOMATICO
