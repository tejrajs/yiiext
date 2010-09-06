<?php
Yii::import($this->getModule()->getId().'.components.widgets.EGalleryGrid');

$this->title=Yii::t('yiiext','Albums');
$this->actions=array(
	array('caption'=>Yii::t('yiiext','Create album'),'link'=>$this->createUrl('create')),
);
$this->description=Yii::t('yiiext','By').'&nbsp;';
$this->breadcrumbs['Veaceslav Medvedev']=array('users/profile','id'=>1);

$this->widget('EGalleryGrid',array(
	'dataProvider'=>$dataProvider,
	'thumbAction'=>'view',
));
