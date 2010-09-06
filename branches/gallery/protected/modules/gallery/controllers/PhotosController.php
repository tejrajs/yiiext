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
	public $description='';
	public $prevLink='';
	public $nextLink='';
	public $breadcrumbs=array();
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
		if($id=EGalleryModule::getQueryId())
		{
			if($model=EPhotoModel::model()->findByPk($id))
			{
				$nextModel=$model->getNextPhoto();
				if($nextModel===null)
					$nextModel=$model->album->getFirstPhoto();
				$prevModel=$model->getPrevPhoto();
				if($prevModel===null)
					$prevModel=$model->album->getLastPhoto();

				return $this->render('view',array(
					'model'=>$model,
					'nextModel'=>$nextModel,
					'prevModel'=>$prevModel,
				));
			}
		}

		throw new CHttpException(404,Yii::t('yiiext','Photo not found!'));
	}
}
