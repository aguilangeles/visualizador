<?php /* $this->widget('application.extensions.ETooltip.ETooltip', array("selector"=>"#password-form :input:not(:button)",
            "tooltip"=>array(
            "opacity"=>1,
            "position"=>"top center",
            "cancelDefault"=>TRUE,
        ), ));*/?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'password-form',
	'enableAjaxValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,)
)); ?>
	<fieldset class="form" style="width: auto;padding: 20px 0 20px 0">
		<legend>Para cambiar la contraseña, por favor llene los campos</legend>
		<?php echo $form->error($model,'newPassword'); ?>
		<?php echo $form->error($model,'newPassword2'); ?>

	<p>
		<?php echo $form->labelEx($model,'newPassword'); ?>
		<?php echo $form->passwordField($model,'newPassword',array('size'=>60,'maxlength'=>255, 'title'=>'Escriba la nueva contraseña')); ?>
		
	</p>	
	<p>		
		<?php echo $form->labelEx($model,'newPassword2'); ?>
		<?php echo $form->passwordField($model,'newPassword2',array('size'=>60,'maxlength'=>255, 'title'=>'Repita la nueva contraseña'));?>
	</p>
	<p>
		<button type="submit" name="Submit">
		<?php echo CHtml::image('../images/key.png','Actualizar',array('style'=>'height:16px;'));?>
		<? echo 'Actualizar';?>
		</button>
	</p>
	</fieldset>
<?php $this->endWidget(); ?>
<?php
    if (count($result)>0)
    {
        if ($result[0]['saved']==TRUE)
        {
           echo '<div class="okMessage"><img src="../images/ok.png" style="height:25px;margin-bottom:-6px;"> '.$result[0]['message'].'</div>';
        }
        else
        {
           echo '<div class="errorMessage"><img src="../images/error.png" style="height:25px;margin-bottom:-6px;"> '.$result[0]['message'].'</div>';
        }
    }
?>