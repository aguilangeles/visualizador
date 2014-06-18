<div id="login-dark-banner">
			<?php echo CHtml::image('../images/documents.png','',array('style'=>'height:50px;float:left'))?>
			<h2>Imágenes por IDC</h2>
	</div>
	<div id="login-dark-banner-wrap"></div>
	<div class="container">
		<div id="content" style="padding: 0 20px 0 20px;width: 1140px;">
			
			<div id="newMeta" style="loat:left;width: 100%">
                            <?php echo $this->renderPartial('_form'); ?>
                        </div>
                </div>
        </div>
<script type="text/javascript">
function consultar(){

        var str = $("#idcName").val();
        $.ajax({url: "<?php echo Yii::app()->request->hostinfo?>/reports/idcSearch",
		context: document.body,
		type: "POST",
		dataType:"text",
                data:"idc="+str,
		success: function(data){
                    $("#report").empty();
                    $("#report").append(data);
                }
    })
}
</script>