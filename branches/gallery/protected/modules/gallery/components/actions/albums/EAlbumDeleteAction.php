<?php
/**
 * EAlbumDeleteAction class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */

/**
 * EAlbumDeleteAction album editing action.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package yiiext.modules.gallery
 */
class EAlbumDeleteAction extends CAction
{
	public $modelClass='EAlbumModel';
	public $view='delete';
	public $viewData=array();

	public function run()
	{
		$modelClass=$this->modelClass;
		$model=$this->loadModel();
		$controller=$this->getController();
		
		if($model===null)
			$this->redirect(array('list'));

		if(Yii::app()->getRequest()->getParam('confirm',false))
		{
			$model->delete();
			$controller->redirect(array('list'));
		}

		$this->viewData['model']=$model;
		$controller->render($this->view,$this->viewData);
	}
	protected function loadModel()
	{
		$modelClass=$this->modelClass;
		$albumId=$this->getAlbumId();

		return CActiveRecord::model($modelClass)->with('photosCount')->findByPk($albumId,array('select'=>'id,name'));
	}
	protected function getAlbumId()
	{
		return intval($id=Yii::app()->getRequest()->getParam('id',0));
	}
}
