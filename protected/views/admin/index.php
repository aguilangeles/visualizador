<?php
if (Yii::app()->user->isAdmin)
{
	$this->widget('zii.widgets.jui.CJuiTabs', array(
		'tabs'=>array(
			'Usuarios'=>array('ajax'=>$this->createUrl('/users/index')),
			//'Grupos'=>array('content'=>$this->renderPartial('//usuarios//index')),
			'Crear Usuarios'=>array('ajax'=>$this->createUrl('/users/create')),
			'Documentos'=>'Content for tab 1',
			'Cambiar Contraseña'=>array('ajax'=>$this->createUrl('/admin/changePassword')),
			// panel 3 contains the content rendered by a partial view
			'AjaxTab'=>array('ajax'=>$this->createUrl('/admin/users')),

		),
		// additional javascript options for the tabs plugin
		'options'=>array(
			'collapsible'=>true,
		),
	));
}
else
{
	$this->widget('zii.widgets.jui.CJuiTabs', array(
		'tabs'=>array(
			'Cambiar Contraseña'=>array('content'=>'Content for tab 2', 'id'=>'tab2','visible'=>false),
			// panel 3 contains the content rendered by a partial view
			//'AjaxTab'=>array('ajax'=>$ajaxUrl),
		),
		// additional javascript options for the tabs plugin
		'options'=>array(
			'collapsible'=>true,
		),
	));
}
?>
