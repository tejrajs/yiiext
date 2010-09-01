<?php
/**
 * EGdImageDriver class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */

/**
 * EGdImageDriver.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package slavcodev.gallery
 */
class EGdImageDriver extends EImageDriver
{
	/**
	 * Image resource.
	 */
	protected $_r;
	/**
	 * A transparent PNG as a string
	 */
	protected static $_p;

	/**
	 * @throws CException
	 * @param boolean $outputToFile
	 * @return boolean|void
	 */
	public function render($outputToFile=false)
	{
		$status=parent::render($outputToFile);

		$type=self::$allowedTypes[$this->_d['type']];
		$params=array($this->getImage(),$outputToFile);
		if($this->_d['type']==IMAGETYPE_JPEG || $this->_d['type']==IMAGETYPE_PNG)
			$params[]=$this->getQuality();
		$status=$status && call_user_func_array('image'.$type,$params);

		if($status===false)
			throw new CException(Yii::t('yiiext', 'Cannot '.($outputToFile===false ? 'save' : 'output').' "{format}" image.',
				array('{format}'=>$type)));

		return $status;
	}
	/**
	 * @return EGdImageProvider
	 */
	protected function copyImage()
	{
		$canvas=$this->createCanvas();
		unset($this->_d['canvasWidth']);
		unset($this->_d['canvasHeight']);
		unset($this->_d['backgroundColor']);

		imagecopyresampled(
			$canvas,
			$this->getImage(),
			$this->getLeft(),
			$this->getTop(),
			$this->getSrcX(),
			$this->getSrcY(),
			$this->getWidth(),
			$this->getHeight(),
			$this->getOriginalWidth(),
			$this->getOriginalHeight()
		);
		imagedestroy($this->_r);
		$this->_r=$canvas;
		
		$this->_d['width']=$this->_d['originalWidth']=imagesx($this->_r);
		$this->_d['height']=$this->_d['originalHeight']=imagesy($this->_r);
		$this->_d['left']=$this->_d['top']=$this->_d['srcX']=$this->_d['srcY']=0;
		
		return $this;
	}
	/**
	 * @return integer
	 */
	protected function getBackgroundColor($image=null)
	{
		if(!is_resource($image))
			$image=$this->getImage();

		$backgroundColor=parent::getBackgroundColor();



		if($this->_d['type']==IMAGETYPE_PNG)
		{
			if(!is_array($backgroundColor))
			{
				if(self::$_p===null)
					self::$_p=imagecreatefromstring(base64_decode(
						'iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29'.
						'mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAADqSURBVHjaYvz//z/DYAYAAcTEMMgBQAANegcCBN'.
						'CgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQ'.
						'AANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoH'.
						'AgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAAA16BwIE0KB'.
						'3IEAADXoHAgTQoHcgQAANegcCBNCgdyBAgAEAMpcDTTQWJVEAAAAASUVORK5CYII='
					));

				$backgroundColor=imagecolorsforindex(self::$_p,imagecolorat(self::$_p,0,0));
				$backgroundColor[0]=$backgroundColor['red'];
				$backgroundColor[1]=$backgroundColor['green'];
				$backgroundColor[2]=$backgroundColor['blue'];
				$backgroundColor[3]=$backgroundColor['alpha'];
			}
			imagealphablending($image,false);
			$backgroundColor=imagecolorallocatealpha($image,$backgroundColor[0],$backgroundColor[1],$backgroundColor[2],$backgroundColor[3]);
		}
		else
		{
			if(!is_array($backgroundColor))
				$backgroundColor=array(0,0,0);
			$backgroundColor=imagecolorallocate($image,0,0,0);
			//$backgroundColor=imagecolorallocate($image,$backgroundColor[0],$backgroundColor[1],$backgroundColor[2]);
		}

		return (int)$backgroundColor;
	}
	/**
	 * @param integer $width
	 * @param integer $height
	 * @param array|null $backgroundColor
	 * @return resource
	 */
	protected function createCanvas()
	{
		$width=$this->getCanvasWidth();
		$height=$this->getCanvasHeight();
		$c=imagecreatetruecolor($width,$height);
		$backgroundColor=$this->getBackgroundColor($c);

		switch($this->_d['type'])
		{
			case IMAGETYPE_GIF:
				imagecolortransparent($c,$backgroundColor);
				imagetruecolortopalette($c,false,256);
				break;
			case IMAGETYPE_PNG:
				imagefill($c,0,0,$backgroundColor);
				imagesavealpha($c,true);
				break;
			default:
				imagecolorat($c,0,0);
				imagefill($c,0,0,$backgroundColor);
		}

		return $c;
	}
	/**
	 * @throws CException
	 * @param string $path
	 * @return EImageProvider
	 */
	public function load($path)
	{
		parent::load($path);
		$type=self::$allowedTypes[$this->_d['type']];
		$function='imagecreatefrom'.$type;
		if(($this->_r=$function($this->getPath()))===false)
			throw new CException(Yii::t('yiiext', 'Invalid image "{format}" format.'),array('{format}'=>$type));
	}
	/**
	 * @throws CException
	 * @return resource
	 */
	public function getImage()
	{
		if($this->_r===null)
			$this->load($this->getPath());

		return $this->_r;
	}
	/**
	 * @param resource $image
	 * @return void
	 */
	public function setImage($image)
	{
		if(is_resource($image))
			$this->_r=$image;
	}
	/**
	 * @param integer|string $width
	 * @param integer|string $height
	 * @param integer $masterDimension
	 * @return EGdImageProvider
	 */
	protected function resize($width,$height,$masterDimension=self::AUTO,$enlarge=true)
	{
		parent::resize($width,$height,$masterDimension,$enlarge);
		return $this->copyImage();
	}
	/**
	 * @param integer|string $width
	 * @param integer|string $height
	 * @param integer $align
	 * @param array|null $backgroundColor
	 * @return EGdImageProvider
	 */
	protected function resizeCanvas($width,$height,$align=self::CENTER,$backgroundColor=null)
	{
		parent::resizeCanvas($width,$height,$align,$backgroundColor);
		return $this->copyImage();
	}
	/**
	 * @param integer $x
	 * @param integer $y
	 * @return EGdImageProvider
	 */
	protected function shift($x,$y)
	{
		parent::shift($x,$y);
		return $this->copyImage();
	}
	/**
	 * @param integer $direction
	 * @return EGdImageProvider
	 */
	protected function flip($direction)
	{
		parent::flip($direction);
		
		if($direction===self::HORIZONTAL)
		{
			$this->_d['srcX']=$this->getWidth()-1;
			$this->_d['originalWidth']=-$this->getWidth();
		}
		else if($direction===self::VERTICAL)
		{
			$this->_d['srcY']=$this->getHeight()-1;
			$this->_d['originalHeight']=-$this->getHeight();
		}
		
		return $this->copyImage();
	}
	/**
	 * @param integer $angle
	 * @param integer $align
	 * @return EGdImageProvider
	 */
	protected function rotate($angle,$align=self::CENTER)
	{
		// TODO: implement $align
		return $this->rotateCanvas($angle);
	}
	/**
	 * @param integer $angle
	 * @param integer $align
	 * @return EGdImageProvider
	 */
	protected function rotateCanvas($angle)
	{
		parent::rotateCanvas($angle);
		$this->_r=imagerotate($this->getImage(),(int)$angle,$this->getBackgroundColor());
		$this->_d['canvasWidth']=$this->_d['width']=$this->_d['originalWidth']=imagesx($this->getImage());
		$this->_d['canvasHeight']=$this->_d['height']=$this->_d['originalHeight']=imagesy($this->getImage());

		return $this->copyImage();
	}
	/**
	 * @param EGdImageProvider $image
	 * @param integer $align
	 * @return EGdImageProvider
	 */
	protected function draw($image,$align=self::CENTER)
	{
		parent::draw($image);
		list($left,$top)=$this->alignToCords($align,$image->getWidth(),$image->getHeight());

		return $this->drawXY($image,$left,$top);
	}
	/**
	 * @param EGdImageProvider $image
	 * @param integer $x
	 * @param integer $y
	 * @return EGdImageProvider
	 */
	protected function drawXY($image,$x=0,$y=0)
	{
		// TODO: transparent image don't drawn.
		parent::drawXY($image,$x,$y);
		imagecopy($this->getImage(),$image->getImage(),(int)$x,(int)$y,0,0,$image->getWidth(),$image->getHeight());

		return $this;
	}
}
