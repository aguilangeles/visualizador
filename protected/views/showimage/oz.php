<?php
$widthSize = $widthSize -100;
$script = 'var viewer = null;function init() {viewer = new Seadragon.Viewer("container");viewer.openDzi("'.$destination.'");}init();';
?>
<?php
    Yii::app()->clientScript->registerScript('seadragon',$script,CClientScript::POS_HEAD);
?>
        <style type="text/css">
            #container
            {
                margin-top: 100px;
                width: <?php echo $widthSize.'px'?>;
                height: 600px;
                background-color: white;
                border: 1px solid white;
                color: red;   /* for error messages, etc. */
            }
        </style>

        
        <div id="container"></div>

