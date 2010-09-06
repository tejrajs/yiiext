<?php
Yii::app()->getClientScript()->registerCssFile($this->getModule()->assetsUrl.'/style.css');
$this->beginContent();

echo CHtml::openTag('div',array('class'=>'yg-content'));
{
	echo CHtml::openTag('div',array('class'=>'yg-header'));
	{
		// Top header
		echo CHtml::openTag('div',array('class'=>'clearfix'));
		{
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
			// Title
			echo CHtml::openTag('div');
				echo CHtml::tag('h2',array('class'=>'yg-header-title'),$this->title);
			echo CHtml::closeTag('div');
		}
		echo CHtml::closeTag('div');
		// Bottom header
		echo CHtml::openTag('div',array('class'=>'clearfix'));
		{
			// Description and breadcrumbs
			echo CHtml::openTag('div',array('class'=>'yg-left'));
				echo CHtml::openTag('h4',array('class'=>'yg-header-description'));
					echo CHtml::tag('span',array('class'=>'yg-photo-count'),$this->description);
					$this->widget('zii.widgets.CBreadcrumbs', array(
						'tagName'=>'span',
						'separator'=>' · ',
						'homeLink'=>false,
						'links'=>$this->breadcrumbs,
					));
				echo CHtml::closeTag('h4');
			echo CHtml::closeTag('div');
			// Photo navigator
			if($this->prevLink || $this->nextLink)
			{
				$navigator=array();
				echo CHtml::openTag('div',array('class'=>'yg-right'));
					if($this->prevLink)
						$navigator[]=CHtml::link(Yii::t('yiiext','Previous'),$this->prevLink);
					if($this->nextLink)
						$navigator[]=CHtml::link(Yii::t('yiiext','Next'),$this->nextLink);
					echo implode('&nbsp;&nbsp;',$navigator);
				echo CHtml::closeTag('div');
			}
		}
		echo CHtml::closeTag('div');
	}
	echo CHtml::closeTag('div');

	echo $content;
}
echo CHtml::closeTag('div');

$this->endContent();
