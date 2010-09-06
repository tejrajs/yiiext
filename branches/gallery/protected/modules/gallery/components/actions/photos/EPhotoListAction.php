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
	public $view='list';
	public $viewData=array(
		'columnsCount'=>5,
	);

	public function run()
	{
		$album=EGalleryModule::album()->findByPk(EGalleryModule::getQueryId());
		$controller=$this->getController();

		if($album===null)
			throw new CHttpException(404,Yii::t('yiiext','Album not found!'));

		$dataProvider=new CActiveDataProvider(EGalleryModule::getPhotoClass(),array(
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
}
