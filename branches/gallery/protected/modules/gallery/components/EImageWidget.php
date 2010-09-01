<?php
/**
 * EImageWidget class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */

/**
 * EImageWidget render a image.
 *
 * The image element rendered by EImageWidget will display a image generated
 * by an action of class {@link EImageAction} belonging to the current controller.
 * By default, the action ID should be 'image', which can be changed by setting {@link imageAction}.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package slavcodev.gallery
 */
class EImageWidget extends CWidget
{
	/**
	 * @var string the ID of the action that should provide image. Defaults to 'image',
	 * meaning the 'image' action of the current controller. This property may also
	 * be in the format of 'ControllerID/ActionID'. Underneath, this property is used
	 * by {@link CController::createUrl} to create the URL that would serve the image.
	 * The action has to be of {@link EImageAction}.
	 */
	public $imageAction='image';
	/**
	 * @var array HTML attributes to be applied to the rendered image element.
	 */
	public $imageOptions=array();
	/**
	 *  @var string the path to the rendered image.
	 */
	public $path;
	/**
	 * @var string the master dimension, can be 'none','auto','width','height'
	 */
	public $master;

	/**
	 * Renders the widget.
	 */
	public function run()
	{
		echo EImageAction::image($this->path,$this->imageOptions,$this->master,$this->imageAction);
	}

}
