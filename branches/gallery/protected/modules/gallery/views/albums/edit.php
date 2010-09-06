<?php
$this->title=Yii::t('yiiext',$model->getIsNewRecord() ? 'Create album' : 'Edit album - {album}',array('{album}'=>$model->name));
$this->breadcrumbs['Back to Album']=array('albums/view','id'=>$model->id);

echo CHtml::errorSummary($model);

echo CHtml::openTag('div',array('class'=>'yg-album-form'));
echo CHtml::beginForm('','post',array('name'=>'upload','id'=>'upload','enctype'=>'multipart/form-data'));

echo CHtml::openTag('table',array('class'=>'yg-form','border'=>0,'cellspacing'=>0));
echo CHtml::openTag('tbody');

echo CHtml::openTag('tr');
echo CHtml::openTag('td',array('class'=>'label'));
echo CHtml::label($model->getAttributeLabel('name').":",'name');
echo CHtml::closeTag('td');
echo CHtml::openTag('td');
echo CHtml::activeTextField($model,'name',array('id'=>'name','class'=>'inputtext','maxlength'=>75,'autocomplete'=>'off','required'=>'true'));
echo CHtml::closeTag('td');
echo CHtml::closeTag('tr');

echo CHtml::openTag('tr');
echo CHtml::openTag('td',array('class'=>'label'));
echo CHtml::label($model->getAttributeLabel('location').":",'location');
echo CHtml::closeTag('td');
echo CHtml::openTag('td');
echo CHtml::activeTextField($model,'location',array('id'=>'location','class'=>'inputtext'));
echo CHtml::closeTag('td');
echo CHtml::closeTag('tr');

echo CHtml::openTag('tr');
echo CHtml::openTag('td',array('class'=>'label'));
echo CHtml::label($model->getAttributeLabel('description').":",'description');
echo CHtml::closeTag('td');
echo CHtml::openTag('td');
echo CHtml::activeTextArea($model,'description',array('id'=>'desc','class'=>'textarea','rows'=>5));
echo CHtml::closeTag('td');
echo CHtml::closeTag('tr');

echo CHtml::closeTag('tbody');
echo CHtml::closeTag('table');

echo CHtml::openTag('div',array('class'=>'formbuttons'));

Yii::import($this->getModule()->getId().'.components.widgets.EButtonWidget');
$this->widget('EButtonWidget',array(
		'link'=>$model->getIsNewRecord() ? $this->createUrl('list') : $this->createUrl('view',array('id'=>$model->id)),
		'caption'=>Yii::t('yiiext','Cancel'),
		'htmlOptions'=>array('class'=>'yg-button-large'),
	));
echo CHtml::openTag('label',array('class'=>'yg-button yg-button-confirm yg-button-large'));
echo CHtml::submitButton(Yii::t('yiiext','Save'),array('id'=>'submit','name'=>'submit','class'=>'yg-inputsubmit'));
echo CHtml::closeTag('label');
echo CHtml::closeTag('div');

echo CHtml::endForm();
echo CHtml::closeTag('div');
