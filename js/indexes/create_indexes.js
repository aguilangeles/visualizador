//indice para consultas por tipo y rotulo

db.getCollection("DatosVisu").ensureIndex({"CMETA_CAJA" : 1
	, "CMETA_ANIO" : 1
	, "CMETA_MES" : 1
	, "CMETA_LIQUIDACION" : 1
	, "CMETA_UNIDAD" : 1
	, "OCR_GRADO" : 1
	, "OCR_CODEST" : 1
	, "idPapel":1 }, {name:"indice_compuesto"},{background :true});

// indices para ordenamiento.
db.getCollection("DatosVisu").ensureIndex({"c1" : 1},{background :true});
db.getCollection("DatosVisu").ensureIndex({"IDC" : 1},{background :true});
db.getCollection("DatosVisu").ensureIndex({"idPapel" : 1},{background :true});
db.getCollection("DatosVisu").ensureIndex({"order" : 1},{background :true});
// Indices en caso de busquedas generales y por tipo con solo uno o dos campos

db.getCollection("DatosVisu").ensureIndex({"CMETA_CAJA" : 1}, {background :true});
db.getCollection("DatosVisu").ensureIndex({"CMETA_ANIO" : 1 }, {background :true});
db.getCollection("DatosVisu").ensureIndex({"CMETA_MES" : 1},{background :true});
db.getCollection("DatosVisu").ensureIndex({"CMETA_LIQUIDACION" : 1},{background :true});
db.getCollection("DatosVisu").ensureIndex({"CMETA_UNIDAD" : 1},{background :true});
db.getCollection("DatosVisu").ensureIndex({"OCR_GRADO" : 1},{background :true});
db.getCollection("DatosVisu").ensureIndex({"OCR_CODEST" : 1},{background :true});
