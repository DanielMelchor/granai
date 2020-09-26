var ArticulosList = [];

function addArticulo(pArticulo, pMedida, pUnidadMedida, pCantidad, pUnidades){
	var newArticulo = {
		articulo     : pArticulo,
		medida       : pMedida,
		unidadMedida : pUnidadMedida,
		cantidad     : pCantidad,
		unidades     : pUnidades
	};

	ArticulosList.push(newArticulo);
}
