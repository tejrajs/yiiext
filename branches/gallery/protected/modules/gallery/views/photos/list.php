<?php
Yii::import($this->getModule()->getId().'.components.widgets.EGalleryGrid');

$this->title=Yii::t('yiiext',$album->name);
$this->actions=array(
	array('caption'=>Yii::t('yiiext',$album->photosCount ? 'Add more photos' : 'Add photos'),'link'=>$this->createUrl('albums/upload',array('id'=>$album->id))),
	array('caption'=>Yii::t('yiiext','Edit info'),'link'=>$this->createUrl('albums/edit',array('id'=>$album->id))),
	array('caption'=>Yii::t('yiiext','Delete album'),'link'=>$this->createUrl('albums/delete',array('id'=>$album->id))),
);
$this->description=Yii::t('yiiext','By').'&nbsp;';
$this->breadcrumbs['Veaceslav Medvedev']=array('users/profile','id'=>1);
$this->breadcrumbs['View Photos']=array('albums/list');

if($album->photosCount)
{
	$this->widget('EGalleryGrid',array(
		'dataProvider'=>$dataProvider,
		'thumbAction'=>'view',
	));
	echo CHtml::openTag('div',array('class'=>'yg-album-metadata'));
	if($album->description)
	{
		echo CHtml::tag('div',array('class'=>'yg-metadata-value'),$album->description);
	}
	if($album->location)
	{
		echo CHtml::tag('div',array('class'=>'yg-metadata-label'),Yii::t('yiiext','Location'));
		echo CHtml::tag('div',array('class'=>'yg-metadata-value'),$album->location);
	}
	if($album->modifyTime)
	{
		echo CHtml::tag('div',array('class'=>'yg-metadata-label'),Yii::t('yiiext','Last update'));
		echo CHtml::tag('div',array('class'=>'yg-metadata-value'),$album->modifyTime);
	}
	echo CHtml::closeTag('div');

}
else
{
	echo CHtml::tag('div',array(),Yii::t('yiiext','There are no photos in this album. {upload}.',array(
		'{upload}'=>CHtml::link(Yii::t('yiiext','Add some'),array('albums/upload','id'=>$album->id)),
	)));
}
