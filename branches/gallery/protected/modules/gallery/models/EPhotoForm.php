<?php
/**
 * EPhotoForm class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package slavcodev.gallery
 */
class EPhotoForm extends CFormModel
{
	public $file;

	public function rules()
	{
		return array(
			array(
				'file','file',
				//'maxSize'=>1024*1024*1024,
				'types'=>'jpg,png,gif',
			),
		);
	}
}
