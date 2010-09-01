<?php
/**
 * EPhotoListAction class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */

/**
 * EPhotoListAction album editing action.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package yiiext.modules.gallery
 */
class EPhotoListAction extends CAction
{
	public $albumClass='EAlbumModel';
	public $modelClass='EPhotoModel';
	public $view='list';
	public $viewData=array(
		'columnsCount'=>5,
	);

	public function run()
	{
		$modelClass=$this->modelClass;
		$albumClass=$this->albumClass;
		$album=$this->loadAlbum();
		$controller=$this->getController();

		if($album===null)
			throw new CHttpException(404,'Album not found!');

		$dataProvider=new CActiveDataProvider($modelClass,array(
			'criteria'=>array(
				'condition'=>'`albumId`='.$album->id,
			),
			'pagination'=>array(
				'pageSize'=>$controller->getModule()->imagesPerPage,
			),
		));

		$this->viewData['album']=$album;
		$this->viewData['dataProvider']=$dataProvider;
		$controller->render($this->view,$this->viewData);
	}
	protected function loadAlbum()
	{
		$albumClass=$this->albumClass;
		$albumId=$this->getAlbumId();

		return CActiveRecord::model($albumClass)->with('photosCount')->findByPk($albumId,array('select'=>'id,name'));
	}
	protected function getAlbumId()
	{
		return intval($id=Yii::app()->getRequest()->getParam('id',0));
	}
}
