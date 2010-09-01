<?php
Yii::import($this->getModule()->getId().'.components.widgets.EGalleryGrid');

$this->title=Yii::t('yiiext','Albums');
$this->actions=array(
	array('caption'=>Yii::t('yiiext','Create album'),'link'=>$this->createUrl('create')),
);

$this->widget('EGalleryGrid',array(
	'dataProvider'=>$dataProvider,
	'thumbAction'=>'view',
));
