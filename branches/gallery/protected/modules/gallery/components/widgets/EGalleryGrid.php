<?php
/**
 * EGalleryGrid class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */

Yii::import('zii.widgets.CBaseListView');

/**
 * 
 */
class EGalleryGrid extends CBaseListView
{
	public $columnsCount=5;
	public $thumbAction;
	public $template='{items}';

	/**
	 * Initializes the gallery view.
	 */
	public function init()
	{
		parent::init();

		if(!isset($this->htmlOptions['class']))
			$this->htmlOptions['class']='yg-grid';
	}
	/**
	 * Registers necessary client scripts.
	 */
	public function registerClientScript()
	{
		// TODO: Виджет может вызываться без модуля, исправить путь!
		Yii::app()->getClientScript()->registerCssFile(Yii::app()->getController()->getModule()->assetsUrl.'/style.css');
	}
	/**
	 * Renders the data items for the gallery view.
	 */
	public function renderItems()
	{
		echo CHtml::openTag('div',$this->htmlOptions);
		$data=$this->dataProvider->getData();
		$count=count($data);
		if($count>0)
		{
			Yii::import(Yii::app()->getController()->getModule()->getId().'.components.widgets.EGalleryThumb');
			echo CHtml::openTag('table');
			echo CHtml::openTag('tbody');

			for($i=0;$i<ceil($count/$this->columnsCount);$i++)
			{
				echo CHtml::openTag('tr');
				for($j=0;$j<$this->columnsCount;$j++)
				{
					echo CHtml::openTag('td');
					$id=$i*$this->columnsCount+$j;
					if($id<$count)
						$this->widget('EGalleryThumb',array(
							'model'=>$data[$id],
							'action'=>$this->thumbAction,
						));

					echo CHtml::closeTag('td');
				}
				echo CHtml::closeTag('tr');
			}

			echo CHtml::closeTag('tbody');
			echo CHtml::closeTag('table');
		}
		else
			$this->renderEmptyText();
		echo CHtml::closeTag('div');
	}
}
