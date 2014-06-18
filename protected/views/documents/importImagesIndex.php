<div id="login-dark-banner">
	<?php echo CHtml::image('../images/documents.png','',array('style'=>'height:50px;float:left'))?>
	<h2>Gestionar IDCs</h2>
</div>
<div id="login-dark-banner-wrap"></div>
<div class="container">

	<a href='index' class="link" style="margin-top:20px;margin-left:45px;">Volver</a>

	<div id="content" style="padding: 0 0 0 20px;width: 1140px;">		
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'import-form',
			'enableAjaxValidation'=>true,
			'clientOptions'=>array('validateOnSubmit'=>true,),
			'htmlOptions'=>array('style'=>'width: 70%;margin: auto;')
			)); 
		?>

			<fieldset class="form" style="width: auto;padding: 20px 0 20px 0">
				<legend>Importar</legend>

				<div>
					<?php echo CHtml::label('Nombre IDC', 'name'); ?>
			        <?php echo CHtml::textField('name','',array('id'=>'name','title'=>'Ingrese el nombre del IDC'));?>
			        <span style="color:red">*<span>
				</div>

				<div>
					<div style="float:left">
						<?php echo CHtml::label('Tipo de Documento', 'docType'); ?>
				        <?php echo CHtml::dropDownList('docTypes', '0', $docTypes);?>
			        </div>
			        <div style="float:left">
						<?php echo CHtml::label('Es Plano:', 'plano'); ?>
	   					<?php echo CHtml::checkBox('plano', false); ?>
   					</div>
				</div>

				<div style="clear:both">
					<?php echo CHtml::label('Id Carátula', 'idIDC'); ?>
			        <?php echo CHtml::textField('idIDC','');?>
				</div>

				<div id="metadata">
					
				</div>

				<div>
					<?php echo CHtml::label('Ruta Origen', 'fromPath'); ?>
			        <?php echo CHtml::textField('fromPath','',array('id'=>'fromPath','title'=>'Ingrese la ruta de Origen'));?>
				</div>

				<div>
					<?php echo CHtml::label('Ruta Destino', 'toPath'); ?>
			        <?php echo CHtml::textField('toPath','',array('id'=>'toPath','title'=>'Ingrese la ruta de Destino'));?>
				</div>
				
				<button type="button" name="import" onclick="importImages()" style="float:right">Importar</button>
				<img id="imgLoadingImport" src="../images/loading.gif" alt="cargando" style="display:none">
				<div style="clear:both;margin-bottom:10px;"></div>
				<div id="importCont" class="resultCont">
				    <textarea id="importResult" class="result">
				    </textarea>
			    </div>
			    <span style="color:red;margin-left:10px;">(*) Para los IDCs de tipo Plano, el nombre no puede contener caracteres especiales.<span>
			</fieldset>
		<?php $this->endWidget(); ?>
    </div>
</div>

<script type="text/javascript">
	 $(document).ready(function() {
	    $('#docTypes').change(function() {
	       searchMeta();
	    });

	    $("#name").bind("keypress", function(event) { 
    		var charCode = event.which;
		    if (charCode <= 13) return true; 

		    var keyChar = String.fromCharCode(charCode); 
		    return /[a-zA-Z0-9_#\-]/.test(keyChar); 
		});

		$("#idIDC").bind("keypress", function(event) { 
    		var charCode = event.which;
		    if (charCode <= 13) return true; 

		    var keyChar = String.fromCharCode(charCode); 
		    return /[a-zA-Z0-9_\-]/.test(keyChar); 
		});
	 });

	function searchMeta(){
		
	    var docType = $("#docTypes").find(':selected').text();
	    $("#metadata").empty();

	    if($("#docTypes").val() != 0)
	    {
            $("#metadata").append('<img src="../images/loading.gif" alt="cargando" style="display:none">');

	        $.ajax({url: "<?php echo Yii::app()->request->hostinfo?>/documents/searchMeta",
				context: document.body,
				type: "POST",
				dataType:"text",
	            data:"docType=" + docType,
				success: function(data){
					$("#metadata").empty();
                    $("#metadata").append(data);
	        	}
	        });
	    }
	}

	function importImages(){
		
	    var docType = $("select[id*='docTypes']").find(':selected').text();
	    var name = $("#name").val();
	    var fromPath = $("#fromPath").val();
	    var toPath = $("#toPath").val();
	    var metas = "";
	    var plano = $("#plano").is(':checked');
	    var idIDC = $("#idIDC").val()

	    $("form input.metas").each(function( index ) {
	    	metas += $(this).attr("id") + ':' + $(this).val() + ',';
		});

	    if($("#docTypes").val() == 0)
	    {
	    	alert("Por favor seleccione un tipo de documento");
	    	return;
	    }

	    if(name == "")
	    {
	    	alert("Por favor ingrese un nombre para el IDC");
	    	return;
	    }

	    if(idIDC == "")
	    {
	    	alert("Por favor ingrese un ID para el IDC");
	    	return;
	    }

	   	if(fromPath == "")
		{
			alert("Por favor ingrese una ruta de origen");
			return;
		}

	    if(toPath == "")
	    {
	    	alert("Por favor ingrese una ruta de destino");
	    	return;
	    }

		$("#imgLoadingImport").show();
		$("#importCont").hide(500);

        $.ajax({url: "<?php echo Yii::app()->request->hostinfo?>/documents/importImages",
			context: document.body,
			type: "POST",
			dataType:"text",
            data:"docType=" + docType + "&name=" + name + "&fromPath=" + fromPath + "&toPath=" + toPath +
            	"&plano=" + plano + "&idIDC=" + idIDC + "&metas=" + metas,
            success: function(data){
				$("#imgLoadingImport").hide();
				$("#importResult").empty();
                $("#importResult").append(data);
				$("#importCont").show(500);
        	}
        });
	}
</script>