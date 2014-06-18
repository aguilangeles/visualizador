<?php $this->widget('application.extensions.ETooltip.ETooltip', array("selector"=>"#report-form :input:not(:button)",
            "tooltip"=>array(
            "opacity"=>1,
            "position"=>"top center",
            "cancelDefault"=>TRUE,
        ), ));?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'report-form',
	'enableAjaxValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,)
)); ?>

	<fieldset class="form" style="width: auto;padding: 20px 0 20px 0">
		<legend>Complete con el nombre de IDC</legend>

	<p>
		<?php echo CHtml::label('Nombre de IDC', 'idcName2'); ?>
                <?php echo CHtml::textField('idcName2','',array('id'=>'idcName','title'=>'ingrese el Nombre de IDC a consultar'));?>
	</p>
	<p>
		<button type="button" name="Submit" onClick="consultar()">
		<?php echo CHtml::image('../images/xmag.png','consultar');?>
		Consultar
		</button>
	</p>
</fieldset>
<?php $this->endWidget(); ?>
<div id="report"></div>
