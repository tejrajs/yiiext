<?php
/**
 * EGalleryThumb class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */

/**
 * 
 */
class EGalleryThumb extends CWidget
{
	public $model;
	public $action;
	/**
	 * Initializes the view.
	 */
	public function init()
	{
		if($this->model===NULL)
			throw new CException(Yii::t('yiiext','The "model" property cannot be empty.'));

		if($this->action===NULL)
			throw new CException(Yii::t('yiiext','The "action" property cannot be empty.'));
	}
	/**
	 * Renders the view.
	 * This is the main entry of the whole view rendering.
	 */
	public function run()
	{
		$this->registerClientScript();
		$isAlbum=$this->model instanceof EAlbumModel;

		$htmlOptions=array(
			'class'=>'yg-thumb yg-thumb-huge'.($isAlbum ? ' yg-album-thumb' : ''),
			'title'=>'',
			'href'=>Yii::app()->getController()->createUrl($this->action,array('id'=>$this->model['id'])),
		);
		echo CHtml::openTag('a',$htmlOptions);

		if($isAlbum)
			echo CHtml::openTag('span',array('class'=>'yg-thumb-wrap'));

		$module=$this->getController()->getModule();
		$image=$this->model[$isAlbum ? 'cover' : 'path'];
		if($image===null)
			$image=$module->getAssetsUrl().'/unknown.png';
		else
			$image=$module->getBaseUrl().'/'.$this->model[$isAlbum ? 'id' : 'albumId'].'/thumb-'.$image;
		
		echo CHtml::openTag('i',array('style'=>'background-image:url(\''.$image.'\');'));
		echo CHtml::closeTag('i');

		if($isAlbum)
			echo CHtml::closeTag('span');

		echo CHtml::closeTag('a');

		if($isAlbum)
		{
			echo CHtml::openTag('div',array('class'=>'yg-album-details'));
			echo CHtml::openTag('a',array(
				'href'=>Yii::app()->getController()->createUrl($this->action,array('id'=>$this->model['id'])),
			));
			echo CHtml::tag('strong',array(),$this->model['name']);
			echo CHtml::closeTag('a');
			echo CHtml::tag('div',array('class'=>'yg-album-details-count'),Yii::t('yiiext','{count} photos',array('{count}'=>$this->model['photosCount'],)));
			echo CHtml::closeTag('div');
		}
	}
	/**
	 * Registers necessary client scripts.
	 */
	public function registerClientScript()
	{
		// TODO: Виджет может вызываться без модуля, исправить путь!
		Yii::app()->getClientScript()->registerCssFile(Yii::app()->getController()->getModule()->assetsUrl.'/style.css');
	}
}
