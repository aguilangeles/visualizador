<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <?php
        //Yii::app()->clientScript->registerCoreScript('jquery');
        Yii::app()->clientScript->registerScriptFile('/js/jquery.js');
        Yii::app()->clientScript->registerScriptFile('/js/jquery.rotate.min.js');
        //Yii::app()->clientScript->registerScriptFile('/js/rotar.js');
        Yii::app()->clientScript->registerScriptFile('/js/seadragon/seadragon-min.js');
        include('paths.php');
        ?>

        <!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
        <![endif]-->

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
        <link rel="shortcut icon" href="/images/UTN3.ico">

            <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>

        <div class="container" id="page" style="background: #EFF4FA">

            <div id="header">
                <div id="logo"><?php echo CHtml::image('../images/logoB.png'); ?></div>
                <div id="menu">
                    <?php
                    $this->widget('application.extensions.menu.SMenu', array(
                      "stylesheet" => "menu_visu.css",
                      "menuID" => "myMenu",
                      "delay" => 3,
                      "menu" => array(
                        array("url" => array("route" => "/site/index",),
                          "label" => "Búsqueda",
                          "visible" => !Yii::app()->user->isGuest),
                        array("url" => array("route" => "/users/changepassword",),
                          "label" => "Cambiar contraseña",
                          "visible" => (!Yii::app()->user->isGuest && !Yii::app()->user->isAdmin)),
                        array("url" => array("route" => "#",),
                          "label" => "Administración",
                          "visible" => Yii::app()->user->isAdmin,
                          //usuarios
                          array("url" => array("#",),
                            "label" => "Usuarios",
                            "visible" => !Yii::app()->user->isGuest,
                            array("url" => array("route" => "/users/index",),
                              "label" => "Usuarios",
                              "visible" => !Yii::app()->user->isGuest),
                            array("url" => array("route" => "/groups/index",),
                              "label" => "Grupos",
                              "visible" => !Yii::app()->user->isGuest),),
                          //documentos
                          array("url" => array("#",),
                            "label" => "Documentos",
                            "visible" => !Yii::app()->user->isGuest,
                            array("url" => array("route" => "/doctypes/index",),
                              "label" => "Documentos",
                              "visible" => !Yii::app()->user->isGuest),
                            array("url" => array("route" => "/caratmeta/index",),
                              "label" => "Meta Data Caratula",
                              "visible" => !Yii::app()->user->isGuest),
                            array("url" => array("route" => "/ocrmeta/index",),
                              "label" => "Meta Data OCR",
                              "visible" => !Yii::app()->user->isGuest),
                            array("url" => array("route" => "/Rotulos/index",),
                              "label" => "Rótulos",
                              "visible" => !Yii::app()->user->isGuest),
                            array("url" => array("route" => "/documents/index",),
                              "label" => "Gestionar",
                              "visible" => !Yii::app()->user->isGuest),
                          ),
                          array("url" => array("#",),
                            "label" => "Reportes",
                            "visible" => !Yii::app()->user->isGuest,
                            array("url" => array("route" => "/Reports/index",),
                              "label" => "Imágenes por IDC",
                              "visible" => !Yii::app()->user->isGuest),
                          ),
                          array("url" => array("route" => "/users/changepassword",),
                            "label" => "Cambiar contraseña",
                            "visible" => (!Yii::app()->user->isGuest)),
                        ),
                        array("url" => array("route" => "/site/login",),
                          "label" => "Login",
                          "visible" => Yii::app()->user->isGuest),
                        array("url" => array("route" => "/site/logout",),
                          "label" => "Salir (" . Yii::app()->user->name . ")",
                          "visible" => !Yii::app()->user->isGuest),
                    )));
                    ?>
                </div>
            </div>

<?php echo $content; ?>

            <div id="footer">
      <!--		Copyright &copy; <?php //echo date('Y');  ?> by My Company.<br/>-->
                <!--		All Rights Reserved.<br/>-->
<?php //echo Yii::powered();  ?>
            </div><!-- footer -->

        </div><!-- page -->

    </body>
</html>