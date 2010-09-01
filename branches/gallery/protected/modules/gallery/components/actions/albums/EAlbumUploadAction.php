<?php
/**
 * EAlbumUploadAction class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */

/**
 * EAlbumUploadAction album editing action.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package yiiext.modules.gallery
 */
class EAlbumUploadAction extends CAction
{
	public $view='upload';
	public $viewData=array();

	public $albumClass='EAlbumModel';
	public $formClass='EPhotoForm';

	public function run()
	{
		$albumId=intval(Yii::app()->getRequest()->getParam('id',0));
		$album=CActiveRecord::model($this->albumClass)->with('photosCount')->onlyName()->findByPk($albumId);
		if($album)
		{
			$model=new $this->formClass;
			$controller=$this->getController();

			$this->viewData['album']=$album;
			$this->viewData['model']=$model;
			$controller->render($this->view,$this->viewData);
		}
		else
			throw new CException('Album not found!');
	}
}
