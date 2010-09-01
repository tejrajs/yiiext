<?php
/**
 * EGdWatermark class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */

/**
 * EGdWatermark.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package slavcodev.gallery
 */
class EGdWatermark extends CBehavior
{
	/**
	 * @param EGdImageProvider $image
	 * @param integer $align
	 * @return EGdImageProvider
	 */
	protected function watermark($image,$align=self::CENTER)
	{
		$owner=$this->getOwner();
		list($left,$top)=$owner->alignToCords($align,$owner->getWidth(),$owner->getHeight());
		return $this->watermarkXY($image,$left,$top);
	}
	/**
	 * @param EGdImageProvider $image
	 * @param integer $x
	 * @param integer $y
	 * @return EGdImageProvider
	 */
	protected function watermarkXY($image,$x=0,$y=0)
	{
		$owner=$this->getOwner();
		$image=$this->copyRecursive($owner->getImage(),$image->getImage());
		imagedestroy($owner->getImage());
		$owner->setImage($image);
		return $owner;
	}
	
	protected function copyRecursive($res1,$res2,$alphaLevel=100) {
		$alphaLevel	/= 100;// convert 0-100 (%) alpha to decimal

		// calculate our images dimensions
		$res1_w	= imagesx( $res1 );
		$res1_h	= imagesy( $res1 );
		$res2_w	= imagesx( $res2 );
		$res2_h	= imagesy( $res2 );

		// determine center position coordinates
		$res1_min_x	= floor( ( $res1_w / 2 ) - ( $res2_w / 2 ) );
		$res1_max_x	= ceil( ( $res1_w / 2 ) + ( $res2_w / 2 ) );
		$res1_min_y	= floor( ( $res1_h / 2 ) - ( $res2_h / 2 ) );
		$res1_max_y	= ceil( ( $res1_h / 2 ) + ( $res2_h / 2 ) );

		// create new image to hold merged changes
		$return_img	= imagecreatetruecolor( $res1_w, $res1_h );

		// walk through main image
		for( $y = 0; $y < $res1_h; $y++ ) {
			for( $x = 0; $x < $res1_w; $x++ ) {
				$return_color	= NULL;

				// determine the correct pixel location within our watermark
				$watermark_x	= $x - $res1_min_x;
				$watermark_y	= $y - $res1_min_y;

				// fetch color information for both of our images
				$main_rgb = imagecolorsforindex( $res1, imagecolorat( $res1, $x, $y ) );

				// if our watermark has a non-transparent value at this pixel intersection
				// and we're still within the bounds of the watermark image
				if (	$watermark_x >= 0 && $watermark_x < $res2_w &&
							$watermark_y >= 0 && $watermark_y < $res2_h ) {
					$watermark_rbg = imagecolorsforindex( $res2, imagecolorat( $res2, $watermark_x, $watermark_y ) );

					// using image alpha, and user specified alpha, calculate average
					$watermark_alpha	= round( ( ( 127 - $watermark_rbg['alpha'] ) / 127 ), 2 );
					$watermark_alpha	= $watermark_alpha * $alphaLevel;

					// calculate the color 'average' between the two - taking into account the specified alpha level
					$avg_red		= $this->_get_ave_color( $main_rgb['red'],		$watermark_rbg['red'],		$watermark_alpha );
					$avg_green	= $this->_get_ave_color( $main_rgb['green'],	$watermark_rbg['green'],	$watermark_alpha );
					$avg_blue		= $this->_get_ave_color( $main_rgb['blue'],	$watermark_rbg['blue'],		$watermark_alpha );

					// calculate a color index value using the average RGB values we've determined
					$return_color	= $this->_get_image_color( $return_img, $avg_red, $avg_green, $avg_blue );

				// if we're not dealing with an average color here, then let's just copy over the main color
				} else {
					$return_color	= imagecolorat( $res1, $x, $y );

				}

				// draw the appropriate color onto the return image
				imagesetpixel( $return_img, $x, $y, $return_color );

			}
		}

		return $return_img;
	}

	// average two colors given an alpha
	protected function _get_ave_color( $color_a, $color_b, $alpha_level ) {
		return round( ( ( $color_a * ( 1 - $alpha_level ) ) + ( $color_b	* $alpha_level ) ) );
	}

	// return closest pallette-color match for RGB values
	protected function _get_image_color($im, $r, $g, $b) {
		$c=imagecolorexact($im, $r, $g, $b);
		if ($c!=-1) return $c;
		$c=imagecolorallocate($im, $r, $g, $b);
		if ($c!=-1) return $c;
		return imagecolorclosest($im, $r, $g, $b);
	}
}
