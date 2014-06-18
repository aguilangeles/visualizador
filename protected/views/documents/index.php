<div id="login-dark-banner">
	<?php echo CHtml::image('../images/documents.png','',array('style'=>'height:50px;float:left'))?>
	<h2>Gestionar IDCs</h2>
</div>
<div id="login-dark-banner-wrap"></div>
<div class="container">
	<div id="content" style="padding: 0 0 0 20px;width: 1140px;">		
		<div id="importForm" style="loat:left;width: 100%">
        	<?php echo $this->renderPartial('_import'); ?>
        </div>
        <div id="deleteForm" style="loat:left;width: 100%">
        	<?php echo $this->renderPartial('_delete', array("model" => $model, "idcs" => $idcs, 
        													"fromDate" => $fromDate, "toDate" => $toDate,"name" => $name,
        													"metaVal" => $metaVal,"searchType" => $searchType,
        													"docTypes" => $docTypes,
        													"docType" => $docType, "metaColumns" => $metaColumns)); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
	 $(document).ready(function() {
	 	if($('input:radio:checked').val() == 1)
	 	{
	 		$('#dateFilter').hide();
	       	$('#metaFilter').show();
	 	}

	 	if($('input:radio:checked').val() == 2)
	 	{
	 		$('#dateFilter').hide();
	       	$('#nameFilter').show();
	 	}

	    $('#searchType').change(function() {
	    	if($('input:radio:checked').val() == 0)
	 		{
	 			$('#nameFilter').hide(250);
	       		$('#metaFilter').hide(250);
	 			$('#dateFilter').show(250);
	 		}

	 		if($('input:radio:checked').val() == 1)
	 		{
	 			$('#dateFilter').hide(250);
	 			$('#nameFilter').hide(250);
	       		$('#metaFilter').show(250);
	 		}

	 		if($('input:radio:checked').val() == 2)
	 		{
	 			$('#dateFilter').hide(250);
	       		$('#metaFilter').hide(250);
	       		$('#nameFilter').show(250);
	 		}
	    });
	 });

	function importIDC(){

	    var path = $("#idcPath").val();

	    if(path != "")
	    {
			$("#imgLoadingImport").show();
			$("#importCont").hide(500);

	        $.ajax({url: "<?php echo Yii::app()->request->hostinfo?>/documents/import",
				context: document.body,
				type: "POST",
				dataType:"text",
	            data:"path=" + path,
				success: function(data){
					$("#imgLoadingImport").hide();
					$("#importResult").empty();
                    $("#importResult").append(data);
					$("#importCont").show(500);
	        	}
	        });
	    }
	    else
	    {
	    	alert("Debe ingresar una ruta para proceder");
	    }

	}

	function deleteIDCs(){
		
		var IDCs = [];

	    $("form input:checkbox:checked").each(function( index ) {
	    	if($(this).val() != 1)
	    	{
  				IDCs.push($(this).val());
  			}
		});

	    if(IDCs.length != 0)
	    {
	    	if(!confirm('Al eliminar el/los registro/s, también se borraran todos los datos asociados. ¿Desea continuar?')) return false;

			$("#imgLoadingDelete").show();
			$("#deleteCont").hide(500);

	        $.ajax({url: "<?php echo Yii::app()->request->hostinfo?>/documents/delete",
				context: document.body,
				type: "POST",
				dataType:"text",
	            data: {IDCs:IDCs},
				success: function(data){
					$("#imgLoadingDelete").hide();
					$("#deleteResult").empty();
                    $("#deleteResult").append(data);

					$("form input:checkbox:checked").each(function( index ) {
				    	if($(this).val() != 1)
	    				{
							$(this).parent().parent().remove();
						}
					});
	        	}
	        });
	    }
	    else
	    {
	    	alert("No se a seleccionado ningún IDC");
	    }

	}

	function deleteIDC(){
		
	    var idc = $("#idIDC").val();

	    if(idc != "")
	    {
			if(!confirm('Al eliminar el IDC, también se borraran todos los datos asociados. ¿Desea continuar?')) return false;

			$("#imgLoadingDeleteIDC").show();
			$("#importCont").hide(500);

	        $.ajax({url: "<?php echo Yii::app()->request->hostinfo?>/documents/deleteIDC",
				context: document.body,
				type: "POST",
				dataType:"text",
	            data:"idc=" + idc,
				success: function(data){
					$("#imgLoadingDeleteIDC").hide();
					$("#deleteIDCResult").empty();
                    $("#deleteIDCResult").append(data);
	        	}
	        });
	    }
	    else
	    {
	    	alert("Debe ingresar un ID de IDC para proceder");
	    }

	}
</script>