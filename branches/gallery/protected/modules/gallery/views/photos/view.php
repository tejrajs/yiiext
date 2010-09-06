<?php
$this->title=Yii::t('yiiext','My Photos - {album}',array('{album}'=>$model->album->name));
$this->description=Yii::t('yiiext','Photo {number} of {count}',array('{number}'=>$model->photoNumber,'{count}'=>$model->album->photosCount)).'&nbsp;';
$this->breadcrumbs['Back to Album']=array('albums/view','id'=>$model->album->id);
$this->breadcrumbs['My Photos']=array('albums/list');
if($model->album->photosCount>1)
{
	$this->prevLink=$this->createUrl('view',array('id'=>$prevModel->id));
	$this->nextLink=$this->createUrl('view',array('id'=>$nextModel->id));
}

echo CHtml::openTag('div',array('class'=>'yg-photo-container'));
echo CHtml::openTag('a',array('href'=>$this->nextLink));
echo CHtml::image($this->getModule()->getBaseUrl().'/'.$model->albumId.'/'.$model->path,$model->description);
echo CHtml::closeTag('a');
echo CHtml::closeTag('div');
echo CHtml::openTag('div',array('class'=>'yg-photo-metadata'));
if($model->description)
{
	echo CHtml::tag('div',array('class'=>'yg-metadata-value'),$model->description);
}
echo CHtml::closeTag('div');
