<div id="login-dark-banner">
			<?php echo CHtml::image('../images/documents.png','',array('style'=>'height:50px;float:left'))?>
			<h2>OCR MetaData</h2>
	</div>
	<div id="login-dark-banner-wrap"></div>
	<div class="container">
		<div id="content" style="padding: 0 20px 0 20px;width: 1140px;">
			<div id="buttons" class="login-form">
		<button type="submit"  name="Submit" onClick="addMeta();" style="margin:0;">
						<?php echo CHtml::image('../images/add.png','Agregar MetaData');?>
						Agregar MetaData
						</button></div>
			<div id="newMeta" style="display:none;float:left;width: 100%"></div>
	<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'meta-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'emptyText'=>'No hay ningún registro',
	'nullDisplay'=>'N/A',
	'summaryText'=>'Mostrando {start}-{end}  de {count} resultados',
	'pager'=>array('nextPageLabel'=>'Siguiente',
		'prevPageLabel'=>'Anterior',
		'header'=>'Ir a página'),
	'columns'=>array(
		'ocr_meta_id',
		'ocr_meta_desc',
		'ocr_meta_label',
		array(            // display 'author.username' using an expression
                     'name'=>'Doc.doc_type_label',
                     'filter'=>CHtml::activeTextField($model, 'documento'),
                     ),		             
                array(
                        'name'=>'is_special',
                        'filter'=> array(0=>'NO',1=>'SI'),
                        'htmlOptions'=>array('style'=>'text-align:center;width:60px;'),
                        'value'=>'($data->is_special==1)?"SI":"NO"',
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
                                                                'click' => 'function(){modMeta($(this).parent().parent().children(":first-child").text());}',
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
	function addMeta(){
		$("#meta-grid").toggle();
		$("#buttons").toggle();
		$.ajax({url: "<?php echo Yii::app()->request->hostinfo?>/ocrmeta/create",
		context: document.body,
		type: "POST",
		dataType:"text",
		success: function(data){
		$("#newMeta").empty();
		$("#newMeta").append(data);
		$("#newMeta").toggle();
	}
	});
		}
		function modMeta(id){
		$("#meta-grid").toggle();
		$("#buttons").toggle();
		$.ajax({url: "<?php echo Yii::app()->request->hostinfo?>/ocrmeta/update/"+id,
		context: document.body,
		type: "POST",
		dataType:"text",
		success: function(data){
		$("#newMeta").empty();
		$("#newMeta").append(data);
		$("#newMeta").toggle();
	}
	});
		}
</script>