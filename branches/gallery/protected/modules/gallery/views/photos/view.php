<?php

$this->title=Yii::t('yiiext','My Photos - {album}',array('{album}'=>$model->album->name));
//'comment'=>'Фотография 1 из '.$model->album->photosCount

echo CHtml::openTag('div',array('class'=>'yg-photo-container'));
echo CHtml::image($this->getModule()->getBaseUrl().'/'.$model->albumId.'/'.$model->path,$model->description);
echo CHtml::closeTag('div');
