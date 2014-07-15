<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
  'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
  'name' => 'UTN-Visualizador v 3.0',
  // preloading 'log' component
  'preload' => array('log'),
  // autoloading model and component classes
  'language' => 'es',
  'import' => array(
    'application.models.*',
    'application.components.*',
    'ext.YiiMongoDbSuite.*',
    'application.extensions.EZip.EZip',
    'application.extensions.yiidebugtb.*',
    'application.extensions.FPDF.FPDF',
    'application.extensions.OZ.OZ.Deepzoom.*',
  ),
  'modules' => array(
    // uncomment the following to enable the Gii tool

    'gii' => array(
      'class' => 'system.gii.GiiModule',
      'password' => 'gii',
      // If removed, Gii defaults to localhost only. Edit carefully to taste.
      'ipFilters' => array('127.0.0.1', '::1'),
      'generatorPaths' => array(
        'ext.YiiMongoDbSuite.gii.*'
      ),
    ),
  ),
  // application components
  'components' => array(
    'user' => array(
      // enable cookie-based authentication
      'allowAutoLogin' => true,
      'autoRenewCookie' => true,
    ),
    'authManager' => array(
      'class' => 'CDbAuthManager',
      'connectionID' => 'db',
      'itemTable' => 'AuthItem', // Tabla que contiene los elementos de autorizacion
      'itemChildTable' => 'AuthItemChild', // Tabla que contiene los elementos padre-hijo
      'assignmentTable' => 'AuthAssignment', // Tabla que contiene la asignacion usuario-autorizacion
    ),
    'mongodb' => array(
      'class' => 'EMongoDB',
      'connectionString' => 'mongodb://127.0.0.1',
      // 'connectionString' => 'mongodb://192.168.0.81',
      //de nico'dbName' => 'DBVisualizador',
      'dbName' => 'DBVisualizador',
      'fsyncFlag' => true,
      'safeFlag' => true,
      'useCursor' => true,
    ),
    // uncomment the following to enable URLs in path-format
    'urlManager' => array(
      'urlFormat' => 'path',
      'showScriptName' => false,
      'caseSensitive' => false,
      'rules' => array(
        '<controller:\w+>/<id:\d+>' => '<controller>/view',
        '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
      ),
    ),
    'db' => array(
      'connectionString' => 'mysql:host=localhost;dbname=visualizador',
      'emulatePrepare' => true,
      'username' => 'root',
      'password' => 'root',
      'charset' => 'utf8',
    ),
    'errorHandler' => array(
      // use 'site/error' action to display errors
      'errorAction' => 'site/error',
    ),
    'log' => array(
      'class' => 'CLogRouter',
      'routes' => array(
        /*// Save log messages on file
        array(
          'class' => 'CFileLogRoute',
          'levels' => 'error, warning, trace, info',
        ),
        // Show log messages on web pages
        array(
          'class' => 'CWebLogRoute',
          //'categories' => 'system.db.CDbCommand',
          'levels' => 'error, warning, trace, info',
          //'showInFireBug' => true,
          'filter' => array(
            'class' => 'CLogFilter',
            'logUser' => true,
            'prefixSession' => true,
            'prefixUser' => true,
            'logVars' => array('_SERVER', '_POST'),
          ),
        ),*/
        array(// configuration for the toolbar
          'class' => 'XWebDebugRouter',
          'config' => 'alignLeft, opaque, runInDebug, fixedPos, collapsed, yamlStyle',
          'levels' => 'error, profile',
//'levels' => 'error, warning, trace, profile, info',
        ),
      ),
    ),
  ),
  // application-level parameters that can be accessed
  // using Yii::app()->params['paramName']
  'params' => array(
    // this is used in contact page
    'adminEmail' => 'webmaster@example.com',
  ),
);
