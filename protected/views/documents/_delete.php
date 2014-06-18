<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'delete-form',
	'enableAjaxValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,)
	)); 
?>

	<fieldset class="form" style="width: auto;padding: 20px 0 20px 0">
		<legend>Borrar</legend>

		<div id="secSearch">

			<div style="float:left;margin-left: 45px;">
				<?php echo CHtml::label('Tipo de Documento', 'docType'); ?>
		        <?php echo CHtml::dropDownList('docType', $docType, $docTypes);?>
	        </div>

			<div id="searchType" style="margin-left:70px">
				<div style="float:left;width:100%;padding: 0 0 10px;">
				<?php echo CHtml::label('Tipo de Busqueda', 'searchType',array('style'=>'text-align: left;')) ?></div>
				<?php echo CHtml::radioButtonList('searchType', $searchType, 
					array('0'=>'Por Fecha','1'=>'Por Metadatos','2'=>'Por Nombre'),
					array('style'=>'width:30px;float:left;','labelOptions'=>
						array('style'=>'width:100px;text-align: left;'),'separator'=>" ")) ?>
			</div>
			<div style="clear:both;margin-bottom:10px;"></div>

			<div id="dateFilter" style="margin-left:40px">
				<div style="float:left">
					<?php echo CHtml::label('Fecha Desde', 'fromDate'); ?>
			        <?php 
			        	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
					    'name' => 'fromDate',
					    'language' => 'es',
					    'value'=> $fromDate,
					    'htmlOptions' => array(
					        'size' => '10',
					        'maxlength' => '10',
					        'width' => '100px',
					    ),
					));?>
		        </div>
		        <div style="float:left">
			        <?php echo CHtml::label('Fecha Hasta', 'toDate'); ?>
			        <?php 
			        	$this->widget('zii.widgets.jui.CJuiDatePicker', array(
					    'name' => 'toDate',
					    'language' => 'es',
					    'value'=> $toDate,
					    'htmlOptions' => array(
					        'size' => '10',
					        'maxlength' => '10',
					        'width' => '100px',
					    ),
					));?>
		        </div>
		        <div style="clear:both"></div>
		    </div>

			<div id="metaFilter" style="display:none;margin-left:40px">
				<?php echo CHtml::label('Valor', 'metaVal'); ?>
		        <?php echo CHtml::textField('metaVal',$metaVal,array('id'=>'metaVal','title'=>'Ingrese la ruta del IDC'));?>
		    </div>

		    <div id="nameFilter" style="display:none;margin-left:40px">
				<?php echo CHtml::label('Nombre del IDC', 'name'); ?>
	        	<?php echo CHtml::textField('name',$name,array('id'=>'idIDC','title'=>'Ingrese el nombre del IDC'));?>
		    </div>

		    <div style="width:86%;margin-left:auto;margin-right:auto;">
				<button type="submit" name="Search" style="float:right">
					<?php echo CHtml::image('../images/xmag.png','search');?>
					Buscar
				</button>
			</div>

			<?php $columns = array(
							array(
						        'name'=>'',             
						        'value'=>'$data->IDC',
						        'class'=>'CCheckBoxColumn',
						        'selectableRows'=>100,
					        ),
					        array(            
	            				'name'=>'Tipo Documento',
	            				'value'=>'$data->docType',
	        				),
							array(            
	            				'name'=>'IDC',
	            				'value'=>'$data->IDC',
	        				),
							array(            
	            				'name'=>'Fecha de Creación',
	            				'value'=>'date("d/m/y", $data->Creation_date->sec)',
	        				),
						);

				foreach ($metaColumns as $column) {
					array_push($columns, $column);
				}

			?>

			<div id="gridIDCs" style="width: 86%; margin-left: auto; margin-right: auto;clear:both;overflow-x:auto">
	 			<?php $this->widget('zii.widgets.grid.CGridView', 
	 				array('id'=>'idcs-grid',
						'dataProvider'=> new CArrayDataProvider($idcs, array(
											        'pagination' => array(
											            'pageSize' => 100
											        )
											    )),
						//'filter'=>$model,
						'emptyText'=>'No hay ningún registro',
						'nullDisplay'=>'N/A',
						'summaryText'=>'Mostrando {start}-{end}  de {count} resultados',
						'pager'=>array('nextPageLabel'=>'Siguiente',
										'prevPageLabel'=>'Anterior',
										'header'=>'Ir a página'),
						'columns'=>$columns
		         	)
				); ?>

				<button type="button" name="Delete" onClick="deleteIDCs()" style="float:right">
					Borrar
				</button>

				<img id="imgLoadingDelete" src="../images/loading.gif" alt="cargando" style="display:none">
				<div style="clear:both;margin-bottom:10px;"></div>
				<div id="deleteResult">
			    </div>
			</div>	
		</div>
	</fieldset>
<?php $this->endWidget(); ?>
