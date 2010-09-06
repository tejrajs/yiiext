<?php
$this->title=Yii::t('yiiext','Upload Photos - {album}',array('{album}'=>$album->name));
$this->breadcrumbs['Back to Album']=array('albums/view','id'=>$album->id);

$this->widget('ext.yiiext.widgets.uploadify.EUploadifyWidget',array(
	'model'=>$model,
	'modelAttribute'=>'file',
	'settings'=>array(
		'folder'=>'/'.$album->id,
		'fileExt'=>'*.jpg;*.png;*.gif',
		/*'onError'=>'function(event,queueID,fileObj,errorObj){
			//$.each(event,function(i,v){alert(i+": "+v);});
			//alert(queueID);
			//$.each(fileObj,function(i,v){alert(i+": "+v);});
			$.each(errorObj,function(i,v){alert(i+": "+v);});
		}',*/
		//'onComplete'=>'function(event,queueID,fileObj,response,data){alert(response);}',
		//'onAllComplete'=>'function(event,data){alert('All complete.');}',
		'script'=>$this->createUrl('albums/uploadify',array('id'=>$album->id)),
		'auto'=>false,
		'multi'=>true,
		'buttonText'=>'Select Photos',
	),
));

echo '<br/>';
echo CHtml::tag('div',array(),Yii::t('yiiext','You can upload {extension} files under {size}.',array(
	'{extension}'=>'JPG, GIF or PNG',
	'{size}'=>'5 MB',
)));

echo CHtml::tag('div',array(),Yii::t('yiiext','You can select multiple photos in the dialog by holding the "ctrl" key down while clicking on the files.'));
echo '<br/>';

Yii::app()->getClientScript()->registerCoreScript('jquery');
Yii::app()->getClientScript()->registerScript('yg-upload-photos-submit','$("#submit").click(function(){$("#uploadifyFileId").uploadifyUpload();});',CClientScript::POS_END);
echo CHtml::openTag('label',array('class'=>'yg-button yg-button-confirm yg-button-large'));
echo CHtml::button(Yii::t('yiiext','Upload Photos'),array(
	'id'=>'submit',
	'name'=>'submit',
	'class'=>'yg-inputsubmit',
));
echo CHtml::closeTag('label');

//http://sphotos.ak.fbcdn.net/hphotos-ak-ash2/hs072.ash2/36955_130767520294849_100000848041857_149860_5850126_n.jpg
//http://photos-h.ak.fbcdn.net/hphotos-ak-ash2/hs072.ash2/36955_130767520294849_100000848041857_149860_5850126_s.jpg
