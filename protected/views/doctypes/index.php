<div id="login-dark-banner">
			<?php echo CHtml::image('../images/documents.png','',array('style'=>'height:50px;float:left'))?>
			<h2>Documentos</h2>
	</div>
	<div id="login-dark-banner-wrap"></div>
	<div class="container">
		<div id="content" style="padding: 0 20px 0 20px;width: 1140px;">
			<div id="buttons" class="login-form">
		<button type="submit"  name="Submit" onClick="addDoc();" style="margin:0;">
						<?php echo CHtml::image('../images/add.png','Agregar Documento');?>
						Agregar Documento
						</button></div>
			<div id="newDoc" style="display:none;float:left;width: 100%"></div>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'doc-types-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'emptyText'=>'No hay ningún registro',
	'nullDisplay'=>'N/A',
	'summaryText'=>'Mostrando {start}-{end}  de {count} resultados',
	'pager'=>array('nextPageLabel'=>'Siguiente',
	'prevPageLabel'=>'Anterior',
	'header'=>'Ir a página'),
	'columns'=>array(
		'doc_type_id',
		'doc_type_desc',
		'doc_type_label',
		'doc_type_level',		           
                array(
                        'name'=>'enabled',
                        'filter'=> array(0=>'NO',1=>'SI'),
                        'htmlOptions'=>array('style'=>'text-align:center;width:60px;'),
                        'value'=>'($data->enabled==1)?"SI":"NO"',
                     ),
                array(
			'class'=>'CButtonColumn',
			'header'=>'Acciones',
			'template' => '{update} {delete}',
			'deleteConfirmation'=>'Al eliminar el registro, también se borraran todos los datos asociados al mismo. ¿Desea continuar?',
			'buttons'  => array(
                                            'update' => array(
                                                              'label' => 'Editar',
                                                              'url'=>'"#"',
                                                              'click' => 'function(){modDoc($(this).parent().parent().children(":first-child").text());}',
                                                             ),
                                            'delete' => array(
                                                              'label' => 'Borrar',
                                                             )
                                            ),
                     ),
		),
)); ?>
		</div>
	</div>
<script type="text/javascript">
	function addDoc(){
		$("#doc-types-grid").toggle();
		$("#buttons").toggle();
		$.ajax({url: "<?php echo Yii::app()->request->hostinfo?>/doctypes/create",
		context: document.body,
		type: "POST",
		dataType:"text",
		success: function(data){
		$("#newDoc").empty();
		$("#newDoc").append(data);
		$("#newDoc").toggle();
	}
	});
		}
		function modDoc(id){
		$("#doc-types-grid").toggle();
		$("#buttons").toggle();
		$.ajax({url: "<?php echo Yii::app()->request->hostinfo?>/doctypes/update/"+id,
		context: document.body,
		type: "POST",
		dataType:"text",
		success: function(data){
		$("#newDoc").empty();
		$("#newDoc").append(data);
		$("#newDoc").toggle();
	}
	});
		}
</script>