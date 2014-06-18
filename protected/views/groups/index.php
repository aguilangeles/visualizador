<div id="login-dark-banner">
			<?php echo CHtml::image('../images/users.png','',array('style'=>'height:50px;float:left'))?>
			<h2>Grupos</h2>
	</div>
	<div id="login-dark-banner-wrap"></div>
	<div class="container">
		<div id="content" style="padding: 0 20px 0 20px;width: 1140px;">
			<div id="buttons" class="login-form">
		<button type="submit"  name="Submit" onClick="addGroup();" style="margin:0;">
						<?php echo CHtml::image('../images/group_add.png','Agregar Grupo');?>
						Agregar Grupo
						</button></div>
			<div id="newGroup" style="display:none;float:left;width: 100%"></div>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'groups-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'emptyText'=>'No hay ningún registro',
	'nullDisplay'=>'N/A',
	'summaryText'=>'Mostrando {start}-{end}  de {count} resultados',
	'pager'=>array('nextPageLabel'=>'Siguiente',
	'prevPageLabel'=>'Anterior',
	'header'=>'Ir a página'),
	'columns'=>array(
		'group_id',
		'group_name',
		array(
			'class'=>'CButtonColumn',
			'header'=>'Acciones',
			'template' => '{update} {delete}',
			'deleteConfirmation'=>'Al eliminar el registro, también se borraran todos los datos asociados al mismo. ¿Desea continuar?',
			'buttons'  => array(
            'update' => array(
                'label' => 'Editar',
				'url'=>'"#"',
				'click' => 'function(){modGroup($(this).parent().parent().children(":first-child").text());}',
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
	function addGroup(){
		$("#groups-grid").toggle();
		$("#buttons").toggle();
		$.ajax({url: "<?php echo Yii::app()->request->hostinfo?>/groups/create",
		context: document.body,
		type: "POST",
		dataType:"text",
		success: function(data){
		$("#newGroup").empty();
		$("#newGroup").append(data);
		$("#newGroup").toggle();
	}
	});
		}
		function modGroup(id){
		$("#groups-grid").toggle();
		$("#buttons").toggle();
		$.ajax({url: "<?php echo Yii::app()->request->hostinfo?>/groups/update/"+id,
		context: document.body,
		type: "POST",
		dataType:"text",
		success: function(data){
		$("#newGroup").empty();
		$("#newGroup").append(data);
		$("#newGroup").toggle();
	}
	});
		}
</script>