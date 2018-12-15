


function __crearColumna(text, renglon){
	var columna = document.createElement("TD");
	columna.innerText = text;
	renglon.appendChild(columna);
}

function __crearTitulo(text, renglon){
	var columna = document.createElement("TH");
	columna.innerText = text;
	renglon.appendChild(columna);
}


function TablaAutomatica(serverLink, tabla){
	var self = this;
	
	self.tabla = tabla;
	self.serverLink = serverLink;


	//esta funcion elimina limpia toda la data que exista
	self.limpiar = function(){

		while (self.tabla.firstChild) {
		    self.tabla.removeChild(self.tabla.firstChild);
		}
	}

	self.crearTitulos = function (datos){
		//creo el renglon y lo inserto a la tabla
		var renglon = document.createElement("TR");
		self.tabla.appendChild(renglon);

		//itero los titulos
		for (var titulo in datos) {

			//creo titulos y los inserto a al renglon
			__crearTitulo(titulo, renglon);
		}

	} //FIN crearTitulo

	self.agregarRenglon = function (datos){


		//itero los datos
		for (var unDato of datos){
			//insertando el renglon como tal
			var renglon = document.createElement("TR");
			self.tabla.appendChild(renglon);

			for (var identificador in unDato) {

				//creo una columna con el texto que contengan los datos
				__crearColumna(unDato[identificador], renglon);

			}
		}


	} // FIN agregarRenglon

		self.listar = function(datos){

		$.post(self.serverLink, datos, function(respuesta){
			//tomo la respuesta resivido y la combierto en un arreglo.
			//este arreglo se supone que es la data a listar.

			
			alert(datos);


			try {

				


				var dataAListar = JSON.parse(respuesta);

				//limpio la tabla
				self.limpiar();

				if(dataAListar.length > 0){
					//agrego titulos
					self.crearTitulos(dataAListar[0]);
					
					//agrego el contenido
					self.agregarRenglon(dataAListar);
				}

			} catch (e){
				//Si hay algun error se limpia la tabla
				self.limpiar();

				var span = document.createElement("SPAN");
				span.innerText = "Ha ocurrido un error inesperado.";
				self.tabla.appendChild(span);

			}

		});
	}

} //FIN TablaAutomatica