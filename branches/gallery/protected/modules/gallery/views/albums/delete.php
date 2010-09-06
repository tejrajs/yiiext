<?php
$this->title=Yii::t('yiiext','Edit album - {album}',array('{album}'=>$model->name));
$this->breadcrumbs['Back to Album']=array('albums/view','id'=>$model->id);

echo CHtml::openTag('div',array('class'=>'yg-album-form'));
echo CHtml::beginForm('','post',array('name'=>'delete','id'=>'delete','enctype'=>'multipart/form-data'));
echo CHtml::hiddenField('confirm',TRUE);

echo CHtml::tag('h2',array(),Yii::t('yiiext','Delete Photo Album?'));
echo '<hr/>';

if($model['photosCount']>0)
	echo CHtml::tag('div',array(),Yii::t('yiiext','Are you sure you want to delete {album}',array('{album}'=>$model['name'])));
else
	echo CHtml::tag('div',array(),Yii::t('yiiext','{album} is empty. Delete it?',array('{album}'=>$model['name'])));

echo '<br/>';
echo CHtml::openTag('div',array('class'=>'yg-actions'));

echo CHtml::openTag('label',array('class'=>'yg-button yg-button-confirm yg-button-large'));
echo CHtml::submitButton(Yii::t('yiiext','Delete'),array('id'=>'submit','name'=>'submit','class'=>'yg-inputsubmit'));
echo CHtml::closeTag('label');

Yii::import($this->getModule()->getId().'.components.widgets.EButtonWidget');
$this->widget('EButtonWidget',array(
	'link'=>$this->createUrl('view',array('id'=>$model['id'])),
	'caption'=>Yii::t('yiiext','Cancel'),
	'htmlOptions'=>array('class'=>'yg-button-large'),
));

echo CHtml::endForm();
echo CHtml::closeTag('div');

