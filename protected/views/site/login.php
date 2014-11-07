
<div id="login-top"></div>
<div id="login-form">
	<div id="login-content" style="background: #DEEAF5">
		<div id="login-header">
			<? echo CHtml::image('../images/logoB.png', 'UTN-Visualizador', array('style' => 'width:370px')); ?>
		</div>
		<div id="login-dark-banner">
			<h2>Iniciar Sesión</h2>
		</div>
		<div id="login-dark-banner-wrap"></div>
		<div>
			<?php
			$form = $this->beginWidget('CActiveForm', array(
			  'id' => 'login-form',
			  'enableClientValidation' => false,
			  'clientOptions' => array(
			    'validateOnSubmit' => false,
			  ),
			));
			?>
			<fieldset class="login-form">
				<?php echo $form->error($model, 'username'); ?>
				<?php echo $form->error($model, 'password'); ?>

				<?php echo $form->labelEx($model, 'username'); ?>
				<?php echo $form->textField($model, 'username'); ?>

				<?php echo $form->labelEx($model, 'password'); ?>
				<?php echo $form->passwordField($model, 'password'); ?>
				<!--SE DESESTIMO EL USO DEL REMEMBER ME-->
				<p>
					<button type="submit" name="Submit">
						<?php echo CHtml::image('../images/key.png', 'Ingresar'); ?>
						Ingresar
					</button>
				</p>
			</fieldset>
			<?php $this->endWidget(); ?>
		</div>
	</div>
</div>
