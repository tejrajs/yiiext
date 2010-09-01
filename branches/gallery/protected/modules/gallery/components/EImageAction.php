<?php
/**
 * EImageAction class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */

Yii::import('EGallery.components.drivers.EImageDriver');

/**
 * EImageAction render a image.
 *
 * Using image involves the following steps:
 * <ol>
 * <li>Override {@link CController::actions()} and register an action of class EImageAction with ID 'image'.</li>
 * <li>In the controller view, insert a {@link EImageWidget} widget.</li>
 * </ol>
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package slavcodev.gallery
 */

class EImageAction extends CAction
{
	/**
	 * @var EImageResource the instance of image resource component.
	 */
	protected $_image=null;

	/**
	 * @static
	 * @param array $imageOptions
	 * @param string|null $master
	 * @return array
	 */
	public static function prepareImageOptions($imageOptions,$master=null)
	{
		if($master=='width')
			unset($imageOptions['height']);
		if($master=='height')
			unset($imageOptions['width']);

		return $imageOptions;
	}
	/**
	 * @static
	 * @param string $path
	 * @param array $imageOptions
	 * @param string|null $master
	 * @return array
	 */
	public static function prepareThumbUrlOptions($path,$imageOptions,$master=null)
	{
		$thumbUrlOptions=array();

		if($imageOptions['width'])
			$thumbUrlOptions['width']=$imageOptions['width'];

		if($imageOptions['height'])
			$thumbUrlOptions['height']=$imageOptions['height'];

		if($master)
			$thumbUrlOptions['master']=$master;
		else if($imageOptions['width'] && $imageOptions['height'])
			$thumbUrlOptions['master']='none';

		$thumbUrlOptions['path']=urlencode($path);

		return $thumbUrlOptions;
	}
	/**
	 * @static
	 * @param string $path
	 * @return string
	 */
	public static function filename($path)
	{
		$filename=pathinfo($path,PATHINFO_DIRNAME ).'/'.pathinfo($path,PATHINFO_FILENAME);
		$extension=pathinfo($path,PATHINFO_EXTENSION);
		return urlencode(base64_encode($filename).'.'.$extension);
	}
	/**
	 * @static
	 * @param string $path
	 * @param array $imageOptions
	 * @param string|null $master
	 * @param string $action
	 * @return string
	 */
	public static function path($path,$imageOptions=array(),$master=null,$action='image')
	{
		$imageOptions=self::prepareImageOptions($imageOptions);
		$thumbUrlOptions=self::prepareThumbUrlOptions($path,$imageOptions,$master,$action);
		return Yii::app()->getController()->createUrl($action,$thumbUrlOptions);
	}
	/**
	 * @static
	 * @param string $path
	 * @param array $imageOptions
	 * @param string|null $master
	 * @param string $action
	 * @return string
	 */
	public static function image($path,$imageOptions=array(),$master=null,$action='image')
	{
		$imageOptions=self::prepareImageOptions($imageOptions);
		$src=self::path($path,$imageOptions,$master,$action);

		if($master=='auto')
			unset($imageOptions['width'],$imageOptions['height']);

		return CHtml::image(
			$src,
			$imageOptions['alt'] ? $imageOptions['alt'] : '',
			$imageOptions
		);
	}
	/**
	 * @param mixed the configuration for image resource component. It can be either a string or an array.
	 */
	public function setImage($value)
	{
		if(is_string($value) && Yii::app()->hasComponent($value))
			$this->_image=Yii::app()->getComponent($value);
		else
			$this->_image=Yii::createComponent($value);
	}
	/**
	 * @return EImageResource the instance of image resource component.
	 */
	public function getImage()
	{
		if(!($this->_image instanceof IImageDriver))
			// If not image resource component, use default EGdImageDriver.
			$this->setImage(array(
				'class'=>'EGdImageDriver',
			));

		return $this->_image;
	}
	/**
	 * @return string the requested image path
	 * @see pathParam
	 */
	public function getPath()
	{
		return $path=urldecode(Yii::app()->getRequest()->getQuery('path',''));
		/*$filename=pathinfo($path,PATHINFO_FILENAME);
		$extension=pathinfo($path,PATHINFO_EXTENSION);
		return Yii::getPathOfAlias('webroot').base64_decode($filename).'.'.$extension;*/
	}
	/**
	 * @return string the requested image width
	 * @see pathWidth
	 */
	public function getWidth()
	{
		return (int)Yii::app()->getRequest()->getQuery('width');
	}
	/**
	 * @return string the requested image height
	 * @see pathHeight
	 */
	public function getHeight()
	{
		return (int)Yii::app()->getRequest()->getQuery('height');
	}
	/**
	 * @return boolean whether image proportion need saved, defaults to true.
	 * @see proportionParam
	 */
	public function getMaster()
	{
		switch(Yii::app()->getRequest()->getQuery('master','auto'))
		{
			case 'none':
				return EImageDriver::NONE;
			case 'width':
				return EImageDriver::WIDTH;
			case 'height':
				return EImageDriver::HEIGHT;
			default:
				return EImageDriver::AUTO;
		}
	}
	/**
	 * Runs the action.
	 */
	public function run()
	{
		$this->renderImage();
		Yii::app()->end();
	}
	/**
	 * Resize and render the image.
	 * @see EImageResource
	 */
	protected function renderImage()
	{
		$width=$this->getWidth();
		$height=$this->getHeight();
		$master=$this->getMaster();
		if($width<1 && $height>0)
			$master=EImageDriver::HEIGHT;
		if($height<1 && $width>0)
			$master=EImageDriver::WIDTH;

		$path=$this->getController()->getModule()->path.$this->getPath();
		$image=$this->getImage();
		$image->load($path);
		if($width>0 || $height>0)
		{
			$image->resize($width,$height,$master);
			/*if($master!=EImageDriver::NONE)
				$image->resizeCanvas($width,$height);*/
		}
		$image->render();
	}
}
