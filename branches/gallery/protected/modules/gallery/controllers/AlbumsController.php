<?php
/**
 * AlbumsController class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package yiiext.modules.gallery
 */
class AlbumsController extends Controller
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
				'class'=>$this->getModule()->getId().'.components.actions.albums.EAlbumListAction',
			),
			'create'=>array(
				'class'=>$this->getModule()->getId().'.components.actions.albums.EAlbumEditAction',
			),
			'edit'=>array(
				'class'=>$this->getModule()->getId().'.components.actions.albums.EAlbumEditAction',
			),
			'delete'=>array(
				'class'=>$this->getModule()->getId().'.components.actions.albums.EAlbumDeleteAction',
			),
			'upload'=>array(
				'class'=>$this->getModule()->getId().'.components.actions.albums.EAlbumUploadAction',
			),
			'uploadify'=>array(
				'class'=>$this->getModule()->getId().'.components.actions.EUploadAction',
				'path'=>$this->getModule()->path,
				'onBeforeSave'=>array('AlbumsController','beforeSave'),
			),
		);
	}
	/**
	 * @param integer $id
	 */
	public function actionView($id)
	{
		Yii::app()->runController('gallery/photos');
	}

	static public function beforeSave($event)
	{
		$sender=$event->sender;
		call_user_func(array('AlbumsController','renamePhoto'),$event);
		$sender->getEventHandlers('onAfterSave')->add(array('AlbumsController','resizePhoto'));
		$sender->getEventHandlers('onAfterSave')->add(array('AlbumsController','saveThumb'));
		$sender->getEventHandlers('onAfterSave')->add(array('AlbumsController','saveToDb'));
	}
	static public function renamePhoto($event)
	{
		$sender=$event->sender;
		$file=$sender->file;
		$sender->filename=time().'-'.md5($file->getName()).'.'.strtolower($file->getExtensionName());
	}
	static public function resizePhoto($event)
	{
		$sender=$event->sender;
		$module=$sender->getController()->getModule();
		$albumId=intval(Yii::app()->getRequest()->getParam('id',0));

		$image=new EGdImageDriver;
		$image->load($module->path.'/'.$albumId.'/'.$sender->filename);
		$image->quality(90);
		$image->resize($module->photoWidth,$module->photoHeight,EImageDriver::AUTO,false);
		$image->save($module->path.'/'.$albumId.'/'.$sender->filename);
	}
	static public function saveThumb($event)
	{
		// Create thumbnail
		$sender=$event->sender;
		$module=$sender->getController()->getModule();
		$albumId=intval(Yii::app()->getRequest()->getParam('id',0));

		$image=new EGdImageDriver;
		$image->load($module->path.'/'.$albumId.'/'.$sender->filename);
		$image->quality(90);
		$image->resize($module->thumbnailWidth,$module->thumbnailHeight,EImageDriver::AUTO,false);
		$image->save($module->path.'/'.$albumId.'/'.'thumb-'.$sender->filename);
	}
	static public function saveToDb($event)
	{
		$sender=$event->sender;
		$albumId=intval(Yii::app()->getRequest()->getParam('id',0));
		$album=CActiveRecord::model('EAlbumModel')->onlyId()->findByPk($albumId);
		if($album!==null)
		{
			$photo=new EPhotoModel;
			$photo->path=$sender->filename;
			$photo->albumId=$album->id;
			$photo->save();
		}
	}
}
