<?php
/**
 * EButtonWidget class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */

/**
 * EButtonWidget render a button.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package yiiext.modules.gallery
 */
class EButtonWidget extends CWidget
{
	public $htmlOptions=array();
	public $link='#';
	public $caption;

	/**
	 * Renders the widget.
	 */
	public function run()
	{
		if(!isset($this->htmlOptions['class']) || strpos('yg-button',$this->htmlOptions['class'])===false)
			$this->htmlOptions['class']='yg-button '.strval($this->htmlOptions['class']);

		if(!isset($this->htmlOptions['href']))
			$this->htmlOptions['href']=$this->link;

		echo CHtml::openTag('a',$this->htmlOptions);
		echo CHtml::tag('span',array('class'=>'yg-button-text'),$this->caption);
		echo CHtml::closeTag('a');
	}

}
