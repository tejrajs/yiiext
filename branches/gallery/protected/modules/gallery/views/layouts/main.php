<?php
Yii::app()->getClientScript()->registerCssFile($this->getModule()->assetsUrl.'/style.css');
$this->beginContent();

echo CHtml::openTag('div',array('class'=>'yg-content'));
{
	echo CHtml::openTag('div',array('class'=>'yg-header'));
	{
		echo CHtml::openTag('div',array('class'=>'clearfix'));

		if($this->actions)
		{
			Yii::import($this->getModule()->getId().'.components.widgets.EButtonWidget');

			echo CHtml::openTag('div',array('class'=>'yg-right'));
			foreach($this->actions as $button)
				$this->widget('EButtonWidget',array(
					'htmlOptions'=>$button['htmlOptions'],
					'link'=>$button['link'],
					'caption'=>$button['caption'],
				));
			echo CHtml::closeTag('div');
		}

		echo CHtml::openTag('div');
			echo CHtml::tag('h2',array('class'=>'yg-header-title'),$this->title);
			echo CHtml::openTag('h4');
				echo $comment;
			echo CHtml::closeTag('h4');
		echo CHtml::closeTag('div');

		echo CHtml::closeTag('div');
	}
	echo CHtml::closeTag('div');

	echo $content;
}
echo CHtml::closeTag('div');

$this->endContent();