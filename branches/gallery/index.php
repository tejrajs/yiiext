<?php
// change the following paths if necessary
$yii=dirname(__FILE__).'/../../trunk/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

function d($var, $depth = 1)
{
	CVarDumper::dump($var, $depth, true);
	echo "<br/>";
}
function e()
{
	exit;
}

require_once($yii);
Yii::createWebApplication($config)->run();
