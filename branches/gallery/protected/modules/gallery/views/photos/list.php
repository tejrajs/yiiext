<?php
Yii::import($this->getModule()->getId().'.components.widgets.EGalleryGrid');

$this->title=Yii::t('yiiext',$album->name);
$this->actions=array(
	array('caption'=>Yii::t('yiiext',$album->photosCount ? 'Add more photos' : 'Add photos'),'link'=>$this->createUrl('albums/upload',array('id'=>$album->id))),
	array('caption'=>Yii::t('yiiext','Edit info'),'link'=>$this->createUrl('albums/edit',array('id'=>$album->id))),
	array('caption'=>Yii::t('yiiext','Delete album'),'link'=>$this->createUrl('albums/delete',array('id'=>$album->id))),
);

if($album->photosCount)
	$this->widget('EGalleryGrid',array(
		'dataProvider'=>$dataProvider,
		'thumbAction'=>'view',
	));
else
{
	echo CHtml::tag('div',array(),Yii::t('yiiext','There are no photos in this album. {upload}.',array(
		'{upload}'=>CHtml::link(Yii::t('yiiext','Add some'),array('albums/upload','id'=>$album->id)),
	)));
}