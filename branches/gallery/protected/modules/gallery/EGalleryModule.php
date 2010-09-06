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

		if($this->_path===null)
			throw new CException(Yii::t('yiiext','Required var "{class}.{property}" not set.',
				array('{class}'=>get_class($this),'{property}'=>'storePath')));
		
		// Publish module assets.
		$this->_assetsUrl=Yii::app()->getAssetManager()->publish(dirname(__FILE__).'/assets',false,-1,YII_DEBUG);
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
	public static function getQueryId()
	{
		return intval($id=Yii::app()->getRequest()->getParam('id',0));
	}
	public static function getAlbumClass()
	{
		return 'EAlbumModel';
	}
	public static function getPhotoClass()
	{
		return 'EPhotoModel';
	}
	public static function album($isNewRecord=false)
	{
		$albumClass=self::getAlbumClass();
		return $isNewRecord ? new $albumClass : CActiveRecord::model($albumClass);
	}
	public static function photo($isNewRecord=false)
	{
		$photoClass=self::getPhotoClass();
		return $isNewRecord ? new $photoClass : CActiveRecord::model($photoClass);
	}
	/**
	 * @static
	 * @throws CHttpException
	 * @param string $error
	 * @param integer $code
	 * @return void
	 */
	static protected function showError($error,$code=500)
	{
		throw new CHttpException($code,$error);
	}
}
