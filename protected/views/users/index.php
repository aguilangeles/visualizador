<div id="login-dark-banner">
			<?php echo CHtml::image('../images/users.png','',array('style'=>'height:50px;float:left'))?>
			<h2>Usuarios</h2>
	</div>
	<div id="login-dark-banner-wrap"></div>
<?php $this->pageTitle=Yii::app()->name; ?>
	<div class="container">
		<div id="content" style="padding: 0 20px 0 20px;width: 1140px;">
			<div id="buttons" class="login-form">
		<button type="submit"  name="Submit" onClick="addUser();" style="margin:0;">
						<?php echo CHtml::image('../images/user.png','Agregar Usuario');?>
						Agregar Usuario
						</button></div>
			<div id="newUser" style="display:none;float:left;width: 100%"></div>
<?php //$this->widget('zii.widgets.CListView', array(
//	'dataProvider'=>$dataProvider,
//	'itemView'=>'//users//_view',
//));
//?>

			<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'users-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'emptyText'=>'No hay ningún registro',
	'nullDisplay'=>'N/A',
	'summaryText'=>'Mostrando {start}-{end}  de {count} resultados',
	'pager'=>array('nextPageLabel'=>'Siguiente',
	'prevPageLabel'=>'Anterior',
	'header'=>'Ir a página'),
	'columns'=>array(
		'userId',
		'userName',		             
                array(
                        'name'=>'is_admin',
                        'filter'=> array(0=>'NO',1=>'SI'),
                        'htmlOptions'=>array('style'=>'text-align:center;width:60px;'),
                        'value'=>'($data->is_admin==1)?"SI":"NO"',
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
                                                               'click' => 'function(){modUser($(this).parent().parent().children(":first-child").text());}',
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
	function addUser(){
		$("#users-grid").toggle();
		$("#buttons").toggle();
		$.ajax({url: "<?php echo Yii::app()->request->hostinfo?>/users/create",
		context: document.body,
		type: "POST",
		dataType:"text",
		success: function(data){
		$("#newUser").empty();
		$("#newUser").append(data);
		$("#newUser").toggle();
	}
	});
		}
		function modUser(id){
		$("#users-grid").toggle();
		$("#buttons").toggle();
		$.ajax({url: "<?php echo Yii::app()->request->hostinfo?>/users/update/"+id,
		context: document.body,
		type: "POST",
		dataType:"text",
		success: function(data){
		$("#newUser").empty();
		$("#newUser").append(data);
		$("#newUser").toggle();
	}
	});
		}
</script>