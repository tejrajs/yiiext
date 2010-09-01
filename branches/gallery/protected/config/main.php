<?php
return array(
	'basePath'=>dirname(__FILE__).'/..',
	'name'=>'Gallery Demo',
	'language'=>'ru',
	'preload'=>array('log'),
	'onBeginRequest'=>function($event){
		$route=Yii::app()->getRequest()->getPathInfo();
		$module=substr($route,0,strpos($route,'/'));
		if(Yii::app()->hasModule($module))
		{
			$module=Yii::app()->getModule($module);
			if(isset($module->urlRules))
				Yii::app()->getUrlManager()->addRules($module->urlRules);
		}

		return TRUE;
	},
	'import'=>array(
		'application.models.*',
		'application.components.*',
		//'ext.slavcodev.XWebDebug.*',
	),
	'components'=>array(
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName' => false,
			'urlSuffix' => '.html',
		),
		'db'=>require_once('db.php'),
		'errorHandler'=>array(
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				/*array(
					'class'=>'XWebDebugRouter',
					'config'=>'alignRight, opaque, runInDebug, fixedPos, yamlStyle',//, collapsed
					'levels'=>'error, warning, trace, profile, info',
					'allowedIPs'=>array('127.0.0.1',),
				),
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),
	'params'=>array(
		'adminEmail'=>'webmaster@example.com',
	),
	'modules'=>array(
		'gallery'=>array(
			'class'=>'application.modules.gallery.EGalleryModule',
			'path'=>'/files/gallery',
			'thumbnailWidth'=>180,
			'thumbnailHeight'=>180,
			'imagesPerPage'=>20,
		),
	),
);
