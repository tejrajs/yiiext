<?php
/**
 * EGdImageFilters class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */

/**
 * EGdImageFilters.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package slavcodev.gallery
 */
class EGdImageFilters extends CBehavior
{
	/**
	 * @throws CException
	 * @param integer $amount
	 * @return EGdImageProvider
	 */
	protected function sharpen($amount)
	{
		$amount=round(abs(-18+($amount*0.08)),2);
		$matrix=array(
			array(-1,-1,-1),
			array(-1,$amount,-1),
			array(-1,-1,-1),
		);

		imageconvolution($this->getOwner()->getImage(),$matrix,$amount-8,0);
		return $this->getOwner();
	}
	/**
	 * @return EGdImageProvider
	 */
	protected function gaussianBlur()
	{
		imagefilter($this->getOwner()->getImage(),IMG_FILTER_GAUSSIAN_BLUR);
		return $this->getOwner();
	}
	/**
	 * @param integer $level
	 * @return EGdImageProvider
	 */
	protected function smooth($level)
	{
		imagefilter($this->getOwner()->getImage(),IMG_FILTER_SMOOTH,(int)$level);
		return $this->getOwner();
	}
}
