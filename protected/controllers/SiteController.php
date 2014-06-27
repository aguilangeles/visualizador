<?php

include ('Funciones.php');
class SiteController extends Controller {

    /**
     * Constante que define un layout vacío.
     */
    const EMPTY_LAYOUT = 'empty_layout';

    /**
     * Directorio donde se crearan las imágenes para ser usadas por Open Zoom.
     */
    const OZ_DIRECTORY = 'images/temp/oz/';

    /**
     * Identificador en mongo, de una imagen Open Zoom.
     */
    const OZ_DOCSUBTYPE = 'OZ';

    /**
     * Ruta de la imagen a visualizar. Inicializada en null.
     * @var string
     * @author GDM
     */
    private $filePath = null;

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
          // captcha action renders the CAPTCHA image displayed on the contact page
          'captcha' => array(
            'class' => 'CCaptchaAction',
            'backColor' => 0xFFFFFF,
          ),
          // page action renders "static" pages stored under 'protected/views/site/pages'
          // They can be accessed via: index.php?r=site/page&view=FileName
          'page' => array(
            'class' => 'CViewAction',
          ),
        );
    }

    public function actionInfo() {
        $this->render('info');
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {

        if (Yii::app()->user->isGuest) {//se fija si esta autenticado
            $this->redirect('/site/login');
        } else {
            $user = Users::model()->findByPk((int) Yii::app()->user->id);
            $docLevel1 = array('0' => '[SELECCIONE UN TIPO DE DOC]');
            $docLevel2 = array('0' => '[SELECCIONE UN TIPO DE DOC]');
            $docLevel3 = array('0' => '[SELECCIONE UN TIPO DE DOC]');
            $docLevel4 = array('0' => '[SELECCIONE UN TIPO DE DOC]');
            $rotulos = array('0' => '[SELECCIONE UN RÓTULO]');
            foreach ($user->GroupsAsoc as $group) {
                foreach ($group->Group->DoctypesAsoc as $docType) {
                    $documento = $docType->DocumentType->doc_type_desc;
                    switch ($docType->DocumentType->doc_type_level) {
                        case 1: $docLevel1 = $docLevel1 + array($docType->DocumentType->doc_type_id => $docType->DocumentType->doc_type_label);
                            break;
                        case 2: $docLevel2 = $docLevel2 + array($docType->DocumentType->doc_type_id => $docType->DocumentType->doc_type_label);
                            break;
                        case 3: $docLevel3 = $docLevel3 + array($docType->DocumentType->doc_type_id => $docType->DocumentType->doc_type_label);
                            break;
                        case 4: $docLevel4 = $docLevel4 + array($docType->DocumentType->doc_type_id => $docType->DocumentType->doc_type_label);
                            break;
                    }
                }
            }
            $rots = Rotulos::model()->findAll();
            foreach ($rots as $rot) {
                if (Users::model()->getRotulosPermission($rot->DocsIds)) {
                    foreach ($rot->DocsIds as $docId) {
                        $rotulos = $rotulos + array($rot->rotulo_id => $rot->rotulo_desc);
                    }
                }
            }
//            $doc = $user->getGroups();
            $model = new Idc();

            $this->render('index', array('model' => $model,
              'docLevel1' => $docLevel1,
              'docLevel2' => $docLevel2,
              'docLevel3' => $docLevel3,
              'docLevel4' => $docLevel4,
              'rotulos' => $rotulos,
            ));
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        $error = '';
        if ($error == Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionLogin() {
        $model = new LoginForm;
        $this->layout = 'login';
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }


    /**
     * Cast an object to another class, keeping the properties, but changing the methods
     *
     * @param string $class  Class name
     * @param object $object
     * @return object
     */
    function casttoclass($class, $object) {
        return unserialize(preg_replace('/^O:\d+:"[^"]++"/', 'O:' . strlen($class) . ':"' . $class . '"', serialize($object)));
    }
    protected function orderResults($group, $qty = 1) {
        $result = array();
        $r = $group['retval'][0]['images'];
        $p = $group['retval'][0]['info'];
        $i = 0;
        foreach ($p as $index) {
            $start = (($index - 1) * $qty);
            $end = $start + $qty;
            for ($j = $start; $j < $end; $j++) {
                $result = $result + array($j => $r[$i]);
                $i++;
            }
        }
        ksort($result);
        $group['retval'][0]['images'] = $result;
        return $group;
    }
}
