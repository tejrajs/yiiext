<?php
/**
 * PhotosController class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package yiiext.modules.gallery
 */
class PhotosController extends Controller
{
	public $layout='main';
	public $defaultAction='list';

	public $title='';
	public $actions=array();

	/**
	 * @return array
	 */
	public function actions()
	{
		return array(
			'list'=>array(
				'class'=>$this->getModule()->getId().'.components.actions.photos.EPhotoListAction',
			),
		);
	}
	public function actionUpload()
	{
		$model=new EPhotoModel;
		$this->render('add',array('model'=>$model,'album'=>'test'));
	}
	public function actionView()
	{
		if(intval($id=Yii::app()->getRequest()->getParam('id',NULL))>0)
		{
			if($photo=EPhotoModel::model()->findByPk($id))
			{
				$this->render('view',array('model'=>$photo,));
				return;
			}
		}

		throw new CHttpException(404,'Photo cannot found!');
	}
}
