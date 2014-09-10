<?php include 'Botones.php';
?>
<div id="login-dark-banner">
    <?php echo CHtml::image('../images/searchDocs.png', '', array('style' => 'height:50px;float:left')) ?>
    <h2>Buscar</h2>
</div>
<div id="login-dark-banner-wrap"></div>
<?php $this->pageTitle = Yii::app()->name; ?>
<div class="container">
    <div id="content">
	<!--	/******************************************************************
		    PRESENTACION BUSQUEDA POR TIPO DE DOCUMENTO
		*******************************************************************/-->
	<div id="first-search">
	    <div class="by-doc-type">
		<fieldset class="form">
		    <legend>Por tipo de documento</legend>
		    <div class="search">
			<div class="level-tag">Nivel 1</div>
			<div class="ddlsearch"><?php echo CHtml::dropDownList('docLevel1', '0', $docLevel1); ?></div>
		    </div>
		    <div class="search">
			<div class="level-tag">Nivel 2</div>
			<div class="ddlsearch"><?php echo CHtml::dropDownList('docLevel2', '0', $docLevel2); ?></div>
		    </div>
		    <div class="search">
			<div class="level-tag">Nivel 3</div>
			<div class="ddlsearch"><?php echo CHtml::dropDownList('docLevel3', '0', $docLevel3); ?></div>
		    </div>
		    <div class="search">
			<div class="level-tag">Nivel 4</div>
			<div class="ddlsearch"><?php echo CHtml::dropDownList('docLevel4', '0', $docLevel4); ?></div>
		    </div>
		    <div class="login-form" style="float:right;margin-right: 40px;">
			<button type="submit" name="Submit" onClick="toggleFirstSearch();">
			    <?php echo CHtml::image('../images/filter.png', 'Ingresar'); ?>
			    Continuar
			</button>
		    </div>
		</fieldset>
	    </div>
	    <!--/******************************************************************
		PRESENTACION BUSQUEDA GENERAL -POR CAMPO ESPECIAL
	    *******************************************************************/-->
	    <div class="by-doc-type">
		<fieldset class="form">
		    <legend>Búsqueda general</legend>
		    <div class="search">
			<div class="level-tag">Buscará en todos los campos definidos para búsqueda especial.</div>
			<div class="login-form" style="float:right;margin-right: 40px;">
			    <button type="submit" name="Submit2" onClick="toggleGeneralSearch();">
				<?php echo CHtml::image('../images/filter.png', 'Ingresar'); ?>
				Continuar
			    </button>
			</div>
		    </div>
		</fieldset>
	    </div>
	    <!--******************************************************************
	    PRESENTACION BUSQUEDA POR ROTULOS
	*******************************************************************/-->
	    <div class="by-doc-type">
		<fieldset class="form">
		    <legend>Por rótulos</legend>
		    <div class="search">
			<div class="level-tag">Nivel 1</div>
			<div class="ddlsearch"><?php echo CHtml::dropDownList('rotulo', '0', $rotulos); ?></div>
			<div class="login-form" style="float:right;margin-right: 40px;">
			    <button type="submit" onClick="toggleRotulosSearch()" name="Submit3" >
				<?php echo CHtml::image('../images/filter.png', 'Ingresar'); ?>
				Continuar
			    </button>
			</div>
		    </div>
		</fieldset>
	    </div>
	</div>






	<!--/******************************************************************
	    BUSQUEDA POR TIPO DE DOCUMENTO
	*******************************************************************/-->
	<!--BLOQUE DE DEFINICION POR NIVEL Y CARACTER-->
	<div id="second-search">
	    <div id="second-search-left">
		<?php
		$boton_doc = new Botones();
		$boton_doc->botonera('SearchDocs()');
		?>
		<!--		<div id="buttons" style="float:left;width: 260px;padding: 30px 0 0 0;">
							<div class="login-form" style="float:left;">
					<button class="back" style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="goBack()();">
									< ?php echo CHtml::image('../images/back.png', 'Ingresar'); ?>
					    Volver
					</button>
							</div>
				    <div class="login-form" style="float:left;">
					<button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="SearchDocs();">
					    < ?php echo CHtml::image('../images/xmag.png', 'Ingresar'); ?>
					    Buscar
					</button>
				    </div>
				</div>-->
		<div id="filters" style="float:left;">
		    <fieldset class="form" style="width:260px;">
			<legend>Se esta buscando por:</legend>
			<div id="filterLevel1" class="level-tag"></div>
			<div id="filterLevel2" class="level-tag"></div>
			<div id="filterLevel3" class="level-tag"></div>
			<div id="filterLevel4" class="level-tag"></div>
			<div id="searchType" style="width: 260px;float:left;padding: 20px 0 10px 0">
			    <div style="float:left;width:100%;padding: 0 0 10px 10px;"><?php echo CHtml::label('Tipo de Busqueda', 'searchType', array('style' => 'text-align: left;')) ?></div>
			    <?php echo CHtml::radioButtonList('searchType', '0', array('0' => 'Exacta', '1' => 'Parecida'), array('style' => 'width:50px;float:left;', 'labelOptions' => array('style' => 'width:60px;text-align: left;'), 'separator' => " ")) ?>
			</div>
		    </fieldset>
		</div>

		<!-- BLOQUE DE BUSSQUEDA POR METADATOS  -->

		<div id="filtersMeta" style="float:left;">
		    <div id="filter">
			<fieldset class="form" style="width:260px;">
			    <legend>Metadata de carátula</legend>
			    <div id="MetaCarats" style="width: 260px;"></div>
			</fieldset>
		    </div>
		    <div id="filtersOCR">
			<fieldset class="form" style="width:260px;">
			    <legend>Metadata de imagen</legend>
			    <div id="MetaOCRs" style="width: 260px;"></div>
			</fieldset>
		    </div>
		</div>
		<?php
		$boton_doc2 = new Botones();
		$boton_doc2->botonera('SearchDocs()');
		?>
		<!--		<div id="buttons" style="float:left;width: 260px;padding: 30px 0 0 0;">
				    <div class="login-form" style="float:left;">
					<button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="goBack();">
					    < ?php echo CHtml::image('../images/back.png', 'Ingresar'); ?>
					    Volver
					</button>
				    </div>
				    <div class="login-form" style="float:left;">
					<button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="SearchDocs();">
					    < ?php echo CHtml::image('../images/xmag.png', 'Ingresar'); ?>
					    Buscar
					</button>
				    </div>
				</div>-->
	    </div>
	    <!--BLOQUE DE AGRUPACION-->
	    <div id="second-search-right" style="padding-left:10px;">
		<div id="searchType" style="margin-left: 20px;width:100%;float:left;padding: 20px 0 0 0">
		    <div style="float:left;width:100%;padding: 0px;">
			<?php echo CHtml::label('Agrupar por', 'searchType', array('style' => 'text-align: left;')) ?>

			<?php echo CHtml::radioButtonList('groupType', '0', array('0' => 'Carátula', '1' => 'Imagen'), array('onClick' => 'checkImageSelection()', 'style' => 'width:50px;float:left;', 'labelOptions' => array('style' => 'width:60px;text-align: left;'), 'separator' => " ")) ?>

		    </div>
		</div>
		<fieldset class="form" style="width:auto">
		    <legend>Resultados</legend>
		    <div id="results">

		    </div>
		</fieldset>
	    </div>

	</div>
	<!--	/******************************************************************
	     BUSQUEDA GENERAL
	*******************************************************************/-->
	<div id="second-general-search" style="display:none;">
	    <div id="second-search-left">
			    <?php $boton_gral = new Botones();
			    $boton_gral->botonera('SearchGralDocs()')?>
<!--		<div id="buttons" style="float:left;width: 260px;padding: 30px 0 0 0;">
		    <div class="login-form" style="float:left;">
			<button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="goBack();">
			    < ?php echo CHtml::image('../images/back.png', 'Ingresar'); ?>
			    Volver
			</button>
		    </div>
		    <div class="login-form" style="float:left;">
			<button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="SearchGralDocs();">
			    < ?php echo CHtml::image('../images/xmag.png', 'Ingresar'); ?>
			    Buscar
			</button>
		    </div>
		</div>-->
		<div id="searchBox">
		    <div id="filterField" style="float:left;">
			<fieldset class="form" style="width:260px;">
			    <legend>Búsqueda general</legend>
			    <div id="searchType" style="width: 260px;float:left;">
				<div style="float:left;width:100%;padding: 0 0 10px 10px;"><?php echo CHtml::label('Tipo de Busqueda', 'searchType', array('style' => 'text-align: left;')) ?></div>
				<?php echo CHtml::radioButtonList('searchGeneralType', '0', array('0' => 'Exacta', '1' => 'Parecida'), array('style' => 'width:50px;float:left;', 'labelOptions' => array('style' => 'width:60px;text-align: left;'), 'separator' => " ")) ?>
			    </div>
			    <div id="field" style="width: 250px;padding-left:10px">
				<?php echo CHtml::label('Buscar', 'searchField', array('style' => 'text-align:left;')) ?>
				<?php echo CHtml::textField('searchField') ?>
			    </div>
			</fieldset>
		    </div>
		</div>
		<div id="filterBox">
		    <div id="filter" style="float:left;">
			<fieldset class="form" style="width:260px;">
			    <legend>Restringir</legend>
			    <div id="filterRestict" style="width: 250px;padding-left:10px;">
			    </div>
			</fieldset>
		    </div>
		</div>
		     <?php $boton_gral2 = new Botones();
			   $boton_gral2->botonera('SearchGralDocs()')?>
<!--		<div id="buttons" style="float:left;width: 260px;padding: 30px 0 0 0;">
		    <div class="login-form" style="float:left;">
			<button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="goBack();">
			    < ?php echo CHtml::image('../images/back.png', 'Ingresar'); ?>
			    Volver
			</button>
		    </div>
		    <div class="login-form" style="float:left;">
			<button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="SearchGralDocs();">
			    < ?php echo CHtml::image('../images/xmag.png', 'Ingresar'); ?>
			    Buscar
			</button>
		    </div>
		</div>-->
	    </div>
	    <div id="second-search-right" style="padding-left:10px;padding-top:57px;">
		<fieldset class="form" style="width:auto">
		    <legend>Resultados</legend>
		    <div id="resultsGeneral">

		    </div>
		</fieldset>
	    </div>
	</div>
	<!--	/******************************************************************
		     BUSQUEDA POR ROTULOS
	********************************/-->
	<div id="second-rotulos-search" style="display:none;">
	    <div id="second-search-left">
<!--		<div id="buttons" style="float:left;width: 260px;padding: 30px 0 0 0;">
		    <div class="login-form" style="float:left;">
			<button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="goBack();">
			    < ?php echo CHtml::image('../images/back.png', 'Ingresar'); ?>
			    Volver
			</button>
		    </div>
		    <div class="login-form" style="float:left;">
			<button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="SearchRotulos();">
			    < ?php echo CHtml::image('../images/xmag.png', 'Ingresar'); ?>
			    Buscar
			</button>
		    </div>
		</div>-->
		    <?php $boton_rotulo = new Botones();
		    $boton_rotulo->botonera('SearchRotulos()')?>
		<div id="searchBox">
		    <div id="filtersRotulosCarat" style="float:left;">
			<fieldset class="form" style="width:260px;">
			    <legend>Búsqueda por rótulos</legend>
			    <div id="searchType" style="width: 260px;float:left;padding: 20px 0 10px 0">
				<div style="float:left;width:100%;padding: 0 0 10px;"><?php echo CHtml::label('Tipo de Busqueda', 'searchType', array('style' => 'text-align: left;')) ?></div>
				<?php echo CHtml::radioButtonList('searchRotType', '0', array('0' => 'Exacta', '1' => 'Parecida'), array('style' => 'width:50px;float:left;', 'labelOptions' => array('style' => 'width:60px;text-align: left;'), 'separator' => " ")) ?>
			    </div>
			    <div id="MetaRotulosCarats" style="width: 260px;"></div>
			</fieldset>
		    </div>
		</div>
	    </div>
	    <div id="second-search-right" style="padding-left:10px;padding-top:57px;">
		<fieldset class="form" style="width:auto">
		    <legend>Resultados</legend>
		    <div id="resultsRotulos">
		    </div>
		</fieldset>
	    </div>
	</div>
	<!--			Busqueda x rótulos-->
	<div id="result"></div>
	<?php
	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	  'id' => 'showImage',
	  // additional javascript options for the dialog plugin
	  'options' => array(
	    'title' => 'Imagen',
	    'modal' => TRUE,
	    'resizable' => FALSE,
	    'autoOpen' => false,
	  ),
	));
	echo '<div id="image" style="float:left;width:100%"></div>';
	$this->endWidget('zii.widgets.jui.CJuiDialog');

	$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
	  'id' => 'downloading',
	  'htmlOptions' => array('style' => 'background-color:rgb(239, 244, 250)'),
	  // additional javascript options for the dialog plugin
	  'options' => array(
	    'title' => 'Descarga',
	    'modal' => TRUE,
	    'resizable' => FALSE,
	    'autoOpen' => false,
	  ),
	));
	echo '<div id="progress" style="float:left;width: 100%;text-align:center">' . CHtml::image("/images/ajax-loader.gif") . '</div>';
	$this->endWidget('zii.widgets.jui.CJuiDialog');
	?>
    </div></div>

<div id="meta_form" class="hidden"></div>

<script type="text/javascript">
    var docLevel1Id;
    var docLevel2Id;
    var docLevel3Id;
    var docLevel4Id;
    var searchType;
    var groupType;
    var rotuloId;
    var rotate_angle = 0;
    var ultAnchoImg;

    function getImage(path, widthSize, doctype, docSubtype, meta, cmeta)
    {
	$("#image").empty();
	$("#image").css("text-align", "center");
	$("#image").html('<img src="../images/ajax-loader.gif" />');
	$.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/site/view",
	    context: document.body,
	    type: "POST",
	    data: "path=" + path + "&widthsize=" + widthSize + "&doctype=" + doctype + "&docSubtype=" + docSubtype,
	    dataType: "text",
	    success: function(data) {
		$("#image").empty();
		getSubImages(path, widthSize, doctype, docSubtype, meta, cmeta);
		getPrevImage(path, widthSize, doctype, docSubtype, meta, cmeta);
		getNextImage(path, widthSize, doctype, docSubtype, meta, cmeta);
		$("#image-meta").empty();
		if (meta != "false")
		    $("#image-meta").append(meta);
		$("#image-path").empty();
		$("#image-path").append("Ruta: " + path);
		$("#image-cmeta").empty();
		$("#image-cmeta").append(cmeta);
		if (docSubtype == "OZ") {
		    $("#image").css("margin-left", "0 50px 0 50px");
		}
		else {
		    $("#image").css("margin", "0 50px 0 50px");
		}
		$("#image").append(data);
	    }});
    }



    function exportZIP(id)
    {
	$("#downloading").dialog({title: "Exportando a ZIP"})
	$("#downloading").dialog("open");
	var query = $("#query_" + id).html();
//                window.open("<?php echo Yii::app()->request->hostinfo ?>/site/exportZIP/?conditions="+query);
	$.ajax({url: "/zip/exportZIP",
	    context: document.body,
	    type: "POST",
	    data: "conditions=" + query,
	    dataType: "text",
	    success: function(data) {
		$("#downloading").dialog("close");
		window.open("/zip/GetZip/?fileName=" + data);
	    }
	});
    }

    function exportPDF(id)
    {
	$("#downloading").dialog({title: "Exportando a PDF"})
	$("#downloading").dialog("open");
	var query = $("#query_" + id).html();
	$.ajax({url: "/pdf/exportPDF",
	    context: document.body,
	    type: "POST",
	    data: "conditions=" + query,
	    dataType: "text",
	    success: function(data) {
		$("#downloading").dialog("close");
		window.open("/pdf/GetPdf/?fileName=" + data);
	    }
	});
    }


    /**
     * Comprueba que al menos exista un filtro de metadato con valor.
     */
    function checkGroupType() {
	if ($("#groupType_1").attr("checked")) {
	    var $inputs = $(":input.metadata");
	    var values = new Array();
	    var x = 0;
	    $inputs.each(function() {
		values[x] = $(this).val();
		x++;
	    });
	    for (i in values) {
		if (values[i] != '')
		    return true;
	    }
	    return false;
	}
	return true;
    }



    function goBack() {
	window.location.replace("<?php echo Yii::app()->request->hostinfo ?>");
    }

    function toogleCaratVisibility(id)
    {
	if ($("#check_set_" + id).is(':checked'))
	{
	    action = "show";
	}
	else
	{
	    action = "hide";
	}
	var query = $("#query_" + id).html();
	var fields = $("#fields_" + id).html();
	//CrtVisibController
	$.ajax({url: "/visible/toggleCaratVisibility",
	    context: document.body,
	    type: "POST",
	    data: "id=" + id + "&query=" + query + "&fields=" + fields + "&action=" + action,
	    dataType: "json",
	    success: function(data) {
		if (action == 'show') {
		    $(".check_" + id).attr('checked', true);
		}
		else {
		    $(".check_" + id).attr('checked', false);
		}
		$("#imageData" + id).html(data.image);
		alert(data.message);
	    }
	});
    }

    function toogleImageVisibility(id, currIndex)
    {
	if ($("#check_" + id + "_" + currIndex).is(':checked'))
	{
	    action = "show";
	}
	else
	{
	    action = "hide";
	}
	var imageList = $("#imageList" + id).html();
	$.ajax({url: "/visibleimg/toggleImageVisibility",
	    context: document.body,
	    type: "POST",
	    data: "imageList=" + imageList + "&currIndex=" + currIndex + "&action=" + action,
	    dataType: "text",
	    success: function(data) {
		alert(data);
	    }
	});
    }

    function checkImageSelection() {
	if (checkGroupType()) {
	    SearchDocs();
	}
	else {
	    alert('Si va a agrupar por imagen, debe filtrar su búsqueda con al menos 1 metadato de imagen.');
	    $("#groupType_0").attr("checked", "checked");
	}
    }
    $(document).ready(function() {
	$("#meta_form").dialog({
	    autoOpen: false,
	    modal: true,
	    resizable: true,
	    title: "Modificar Metadatos",
	    width: 550
	});
    });

    function openMetaForm(id) {
	$("#meta_form").dialog("option", "buttons", [
	    {text: "Actualizar", click: function() {
		    editImage(id);
		}},
	    {text: "Cancelar", click: function() {
		    $(this).dialog("close");
		}}
	]);
	getMetaData(id);
    }
    function getMetaData(id) {

	$.ajax({
	    url: "<?php echo Yii::app()->request->hostinfo ?>/ocrmeta/GetImageMeta",
	    type: "POST",
	    data: {mongo_id: id},
	    dataType: "json",
	    success: function(data) {
		$("#meta_form").html(data.html);
		$("#meta_form").dialog("open");
	    }
	});
    }
    function editImage(id) {
	var inputs = $('#new_meta_form :input');
	var newData = {};
	$.each(inputs, function( ) {
	    $(newData).attr(this.id, $(this).val());

	});

	$.ajax({
	    url: "<?php echo Yii::app()->request->hostinfo ?>/ocrmeta/editImageMeta",
	    context: document.body,
	    type: "POST",
	    data: {mongo_id: id, new_data: newData},
	    dataType: "json",
	    success: function(data) {
		if (data.success) {
		    updateGridValues(id, newData, "meta_form");
		}
		else
		{
		    alert(data.message);
		    $("#meta_form").dialog("close");
		}
	    }
	});
    }
</script>