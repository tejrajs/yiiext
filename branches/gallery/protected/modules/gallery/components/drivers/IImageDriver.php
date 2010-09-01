<?php
/**
 * IImageDriver interface file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */

/**
 * IImageDriver
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package slavcodev.gallery
 */
interface IImageDriver
{
	public function load($path);
	public function save($path='',$filePermissions=0644);
	public function render($outputToFile=false);
	public function process();
	public function getImage();
}
