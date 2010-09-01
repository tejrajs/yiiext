<?php
/**
 * EImageDriver class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */

/**
 * EImageDriver
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package slavcodev.gallery
 */
abstract class EImageDriver extends CApplicationComponent implements IImageDriver
{
	const NONE=0x1;
	const AUTO=0x2;
	const WIDTH=0x3;
	const HEIGHT=0x4;

	const VERTICAL=0x1;
	const HORIZONTAL=0x2;

	const CENTER=0x00001;
	const TOP=0x00010;
	const RIGHT=0x00100;
	const BOTTOM=0x01000;
	const LEFT=0x10000;

	public static $allowedTypes=array(
		IMAGETYPE_GIF=>'gif',
		IMAGETYPE_JPEG=>'jpeg',
		IMAGETYPE_PNG=>'png',
	);
	public static $allowedMimes=array(
		IMAGETYPE_GIF=>'image/gif',
		IMAGETYPE_JPEG=>'image/jpeg',
		IMAGETYPE_PNG=>'image/png',
	);

	/**
	 * Image data storage.
	 */
	protected $_d=array();
	/**
	 * Process queue.
	 */
	protected $_q;

	/**
	 * @throws CException
	 * @return void
	 */
	public function __construct()
	{
		// Check the function exactly once
		static $check;
		if($check===NULL && ($check=function_exists('getimagesize'))===FALSE)
			throw new CException(Yii::t('yiiext', 'Function "{function}" missing.',array('{function}'=>'getimagesize')));

		// Create queue.
		$this->_q=new CQueue;
	}
	/**
	 * @param string $name
	 * @param array $arguments
	 */
	public function __call($name,$arguments)
	{
		if(method_exists($this,$name))
		{
			$this->_q->enqueue(array($name,$arguments));
			return $this;
		}
		else
			parent::__call($name,$arguments);
	}
	/**
	 * @throws CException
	 * @param string $path
	 */
	public function load($path)
	{
		if(!is_file($path))
			throw new CException(Yii::t('yiiext', 'Image file not found.'));

		$size=@getimagesize($path);

		if (!is_array($size) || count($size)<3)
			throw new CException(Yii::t('yiiext', 'Image file unreadable.'));

		if(!isset(self::$allowedTypes[$size[2]]))
			throw new CException(Yii::t('yiiext', 'Unsupported image format.'));

		$this->_q->clear();
		$this->_d=array();
		$this->setPath($path);
		$this->_d['originalWidth']=$size[0];
		$this->_d['originalHeight']=$size[1];
		$this->_d['width']=$size[0];
		$this->_d['height']=$size[1];
		$this->_d['type']=$size[2];
		$this->_d['mime']=$size['mime'];

		return $this;
	}
	/**
	 * @param string $path
	 * @return void
	 */
	public function save($path='',$filePermissions=0644)
	{
		$this->setPath($path);
		$status=$this->render($this->getPath());

		if($status===true && $filePermissions!==FALSE)
			chmod($path,$filePermissions);

		return $status;
	}
	/**
	 * @param string|boolean $outputToFile
	 * @return void
	 */
	public function render($outputToFile=FALSE)
	{
		if(is_string($outputToFile))
		{
			$this->setPath($outputToFile);
			$outputToFile=$this->getPath();
		}
		else
			$outputToFile=FALSE;

		$status=$this->process();

		if($outputToFile===FALSE)
		{
			header('Content-Disposition: inline; filename="'.pathinfo($this->getPath(),PATHINFO_BASENAME).'"');
			header('Content-Transfer-Encoding: binary');
			header('Content-type: '.$this->_d['mime']);
		}

		return $status;
	}
	/**
	 * @return boolean
	 */
	public function process()
	{
		while($this->_q->getCount()>0)
		{
			list($function,$arguments)=$this->_q->dequeue();
			call_user_func_array(array($this,$function),$arguments);
		}

		return TRUE;
	}
	/**
	 * @return string
	 */
	protected function getPath()
	{
		if(!is_string($this->_d['path']))
			throw new CException(Yii::t('yiiext', 'Image not loaded.'));
		
		return $this->_d['path'];
	}
	/**
	 * @param string $path
	 * @return void
	 */
	protected function setPath($path)
	{
		$path=(string)$path;
		if(!empty($path))
		{
			$this->_d['path']=$path;

			if($this->_d['originalPath']===null)
				$this->_d['originalPath']=$this->_d['path'];
		}
	}
	/**
	 * @return integer
	 */
	protected function getCanvasWidth()
	{
		if(is_int($this->_d['canvasWidth']))
			return $this->_d['canvasWidth'];

		return $this->_d['width'];
	}
	/**
	 * @return integer
	 */
	protected function getCanvasHeight()
	{
		if(is_int($this->_d['canvasHeight']))
			return $this->_d['canvasHeight'];

		return $this->_d['height'];
	}
	/**
	 * @return array|null
	 */
	protected function getBackgroundColor()
	{
		if(isset($this->_d['backgroundColor']))
			return $this->_d['backgroundColor'];

		return null;
	}
	/**
	 * @return integer
	 */
	protected function getWidth()
	{
		return $this->_d['width'];
	}
	/**
	 * @return integer
	 */
	protected function getHeight()
	{
		return $this->_d['height'];
	}
	/**
	 * @return integer
	 */
	protected function getOriginalWidth()
	{
		return $this->_d['originalWidth'];
	}
	/**
	 * @return integer
	 */
	protected function getOriginalHeight()
	{
		return $this->_d['originalHeight'];
	}
	/**
	 * @return integer
	 */
	protected function getLeft()
	{
		return is_int($this->_d['left']) ? $this->_d['left'] : 0;
	}
	/**
	 * @return integer
	 */
	protected function getTop()
	{
		return is_int($this->_d['top']) ? $this->_d['top'] : 0;
	}
	/**
	 * @return integer
	 */
	protected function getQuality()
	{
		// Set default quality.
		if(empty($this->_d['quality']))
			$this->_d['quality']=75;

		// Transform percents to compression level for PNG images. 
		if($this->_d['type']==IMAGETYPE_PNG)
			$quality=10-ceil($this->_d['quality']/10);
		else
			$quality=$this->_d['quality'];

		return $quality;
	}
	/**
	 * @param integer $value
	 * @return void
	 */
	protected function setLeft($value)
	{
		$this->_d['left']=$this->getLeft()+(int)$value;
	}
	/**
	 * @param integer $value
	 * @return void
	 */
	protected function setTop($value)
	{
		$this->_d['top']=$this->getTop()+(int)$value;
	}
	/**
	 * @return integer
	 */
	protected function getSrcX()
	{
		return is_int($this->_d['srcX']) ? $this->_d['srcX'] : 0;
	}
	/**
	 * @return integer
	 */
	protected function getSrcY()
	{
		return is_int($this->_d['srcY']) ? $this->_d['srcY'] : 0;
	}
	/**
	 * @static
	 * @param mixed $value
	 * @return integer|boolean
	 */
	protected static function percents($value)
	{
		if(is_string($value) && !ctype_digit($value))
		{
			preg_match('/^([0-9]+) ?%$/D',$value,$matches);
			return $matches[1];
		}
		return FALSE;
	}
	/**
	 * @param integer $align
	 * @return array
	 */
	public function alignToCords($align,$width=NULL,$height=NULL)
	{
		if(!is_int($width))
			$width=$this->_d['width'];
		if(!is_int($height))
			$height=$this->_d['height'];

		return array(
			$align & self::LEFT ? 0 : ($align & self::RIGHT ? $this->getCanvasWidth()-$width : floor(($this->getCanvasWidth()-$width)/2)),
			$align & self::TOP ? 0 : ($align & self::BOTTOM ? $this->getCanvasHeight()-$height : floor(($this->getCanvasHeight()-$height)/2)),
		);
	}
	/**
	 * @param integer $width
	 * @param boolean $saveProportion
	 * @param boolean $enlarge
	 */
	protected function width($width,$saveProportion=true,$enlarge=true)
	{
		$percents=self::percents($width);
		$width=$percents!==false ? round($this->_d['width']*$percents/100) : intval($width);

		if($width!=$this->_d['width'] && ($enlarge!==false || $width<$this->_d['width']))
		{
			if($saveProportion!==false)
				$this->_d['height']=round($this->_d['height']*$width/$this->_d['width']);

			$this->_d['width']=$width;
		}

		return $this;
	}
	/**
	 * @param integer $height
	 * @param boolean $saveProportion
	 * @param boolean $enlarge
	 */
	protected function height($height,$saveProportion=true,$enlarge=true)
	{
		$percents=self::percents($height);
		$height=$percents!==false ? round($this->_d['height']*$percents/100) : intval($height);

		if($height!=$this->_d['height'] && ($enlarge!==false || $height<$this->_d['height']))
		{
			if($saveProportion!==false)
				$this->_d['width']=round($this->_d['width']*$height/$this->_d['height']);

			$this->_d['height']=$height;
		}

		return $this;
	}
	/**
	 * @param mixed $width
	 * @param mixed $height
	 * @param integer $masterDimension
	 * @param boolean $enlarge
	 */
	protected function resize($width,$height,$masterDimension=self::AUTO,$enlarge=true)
	{
		if($this->_d['height']<$this->_d['width'])
		{
			if($masterDimension!=self::WIDTH)
				$this->height($height,$masterDimension==self::AUTO || $masterDimension==self::HEIGHT,$enlarge);

			if($masterDimension!=self::HEIGHT)
				$this->width($width,$masterDimension==self::AUTO || $masterDimension==self::WIDTH,$enlarge);
		}
		else
		{
			if($masterDimension!=self::HEIGHT)
				$this->width($width,$masterDimension==self::AUTO || $masterDimension==self::WIDTH,$enlarge);

			if($masterDimension!=self::WIDTH)
				$this->height($height,$masterDimension==self::AUTO || $masterDimension==self::HEIGHT,$enlarge);
		}

		return $this;
	}
	/**
	 * @param array|null $backgroundColor
	 */
	protected function fillBackground($backgroundColor=null)
	{
		$this->_d['backgroundColor']=$backgroundColor;
		return $this;
	}
	/**
	 * @param integer $width
	 * @param integer $height
	 * @param integer $align
	 * @param array|null $backgroundColor
	 */
	protected function resizeCanvas($width,$height,$align=self::CENTER,$backgroundColor=null)
	{
		if(($percents=self::percents($width))!==false)
			$width=round($this->getCanvasWidth()*$percents/100);

		if(($percents=self::percents($height))!==false)
			$height=round($this->getCanvasHeight()*$percents/100);

		if(($width=(int)$width)!=$this->getCanvasWidth() && $width)
			$this->_d['canvasWidth']=$width;
		
		if(($height=(int)$height)!=$this->getCanvasHeight() && $height)
			$this->_d['canvasHeight']=$height;

		$this->fillBackground($backgroundColor);
		
		list($left,$top)=$this->alignToCords($align);
		$this->setLeft($left);
		$this->setTop($top);

		return $this;
	}
	/**
	 * @param integer $x
	 * @param integer $y
	 */
	protected function shift($x,$y)
	{
		$this->setLeft($x);
		$this->setTop($y);
		return $this;
	}
	/**
	 * @param integer $left
	 * @param integer $top
	 * @param integer $right
	 * @param integer $bottom
	 */
	protected function crop($left,$top,$right,$bottom)
	{
		$width=($right=(int)$right)-($left=(int)$left);
		$height=($bottom=(int)$bottom)-($top=(int)$top);

		if($width>0 && $height>0)
		{
			$this->setLeft(-$left);
			$this->setTop(-$top);
			$this->resizeCanvas($width,$height,self::TOP|self::LEFT);
		}
		
		return $this;
	}
	/**
	 * @param integer $direction
	 */
	abstract protected function flip($direction);
	/**
	 * @param integer $angle
	 * @param integer $align
	 */
	abstract protected function rotate($angle,$align=self::CENTER);
	/**
	 * @param integer $angle
	 * @param integer $align
	 */
	abstract protected function rotateCanvas($angle);
	/**
	 * @param EGdImageProvider $image
	 * @param integer $align
	 */
	abstract protected function draw($image,$align=self::CENTER);
	/**
	 * @param EGdImageProvider $image
	 * @param integer $x
	 * @param integer $y
	 */
	abstract protected function drawXY($image,$x=0,$y=0);
	/**
	 * @param mixed extension or mime-type or integer image type example IMAGETYPE_PNG
	 */
	protected function convert($format)
	{
		if(is_string($format))
		{
			$format=str_replace('jpg','jpeg',$format);
			$type=array_search($format,self::$allowedTypes);
			if($type===FALSE)
				$type=array_search($format,self::$allowedMimes);
		}
		else
			$type=(int)$format;

		if(isset(self::$allowedTypes[$type]))
		{
			$this->_d['type']=$type;
			$this->_d['mime']=self::$allowedMimes[$type];
		}

		return $this;
	}
	/**
	 * @param mixed $value
	 */
	protected function quality($value)
	{
		$percents=self::percents($value);
		if($percents)
			$value=$percents;
		
		$this->_d['quality']=$value%100;

		return $this;
	}
}
//pathinfo(,PATHINFO_DIRNAME|PATHINFO_BASENAME|PATHINFO_FILENAME|PATHINFO_EXTENSION);
