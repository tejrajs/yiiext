<?php
/**
 * EAlbumEditAction class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */

/**
 * EAlbumEditAction album editing action.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package yiiext.modules.gallery
 */
class EAlbumEditAction extends CAction
{
	public $modelClass='EAlbumModel';
	public $view='edit';
	public $viewData=array();

	public function run()
	{
		$modelClass=$this->modelClass;
		$model=$this->loadModel();
		$controller=$this->getController();
		
		if(isset($_POST[$modelClass]))
		{
			$model->attributes=$_POST[$modelClass];
			// TODO: не сохранять при рефреше
			if($model->save())
				$controller->redirect(array('view','id'=>$model->id));
		}

		$this->viewData['model']=$model;
		$controller->render($this->view,$this->viewData);
	}
	protected function loadModel()
	{
		$modelClass=$this->modelClass;
		$albumId=$this->getAlbumId();
		
		if($albumId>0)
			$model=CActiveRecord::model($modelClass)->findByPk($albumId);
		else
			$model=new $modelClass;

		return $model;
	}
	protected function getAlbumId()
	{
		return intval($id=Yii::app()->getRequest()->getParam('id',0));
	}
}
