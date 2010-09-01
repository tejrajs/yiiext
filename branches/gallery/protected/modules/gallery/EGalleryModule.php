<?php
/**
 * EGalleryModule class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package slavcodev.gallery
 */

Yii::setPathOfAlias('EGallery',dirname(__FILE__));

/**
 * EGalleryModule
 *
 * @property $path
 * @property $baseUrl
 */
class EGalleryModule extends CWebModule
{
	public $mimeTypes;
	public $thumbnailWidth=100;
	public $thumbnailHeight=100;
	public $photoWidth=800;
	public $photoHeight=800;
	public $imagesPerPage=20;
	public $albumsPerPage=20;
	public $order;

	public $defaultController='albums';

	/*public $urlRules=array(
		'gallery/photo/<id:\d+>'=>'gallery/default/photoview',

		'gallery/album/<id:\d+>'=>'gallery/default/albumview',
		'gallery/album/edit/<id:\d+>'=>'gallery/default/albumedit',
		'gallery/album/delete/<id:\d+>'=>'gallery/default/albumdelete',
		'gallery/album/add'=>'gallery/default/albumadd',
		'gallery/albums'=>'gallery/default/albums',
		'gallery/upload'=>'gallery/default/upload',
	);*/

	protected $_path;
	protected $_assetsUrl;

	public function init()
	{
		$this->setImport(array(
			$this->getId().'.models.*',
			$this->getId().'.components.*',
			$this->getId().'.components.drivers.*',
		));

		if($this->_path===NULL)
			throw new CException(Yii::t('yiiext', 'Required var "{class}.{property}" not set.',
				array('{class}' => get_class($this), '{property}' => 'storePath')));
		
		// Publish module assets.
		$this->_assetsUrl=Yii::app()->getAssetManager()->publish(dirname(__FILE__).'/assets',FALSE,-1,YII_DEBUG);
	}

	public function getAssetsUrl()
	{
		return $this->_assetsUrl;
	}

	public function getPath()
	{
		return is_dir($this->_path) ? $this->_path : Yii::getPathOfAlias('webroot').$this->_path;
	}
	
	public function setPath($path)
	{
		$this->_path=$path;
	}

	public function getBaseUrl()
	{
		return Yii::app()->baseUrl.$this->_path;
	}

	public function beforeControllerAction($controller,$action)
	{
		return parent::beforeControllerAction($controller,$action) ? true : false;
	}
}
