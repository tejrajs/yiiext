<?php
/**
 * EUploadAction class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */

/**
 * EUploadAction upload a image.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package slavcodev.modules.gallery
 */
class EUploadAction extends CAction
{
	protected $_file;

	public $path;
	public $filename;
	public $modelClass='EPhotoForm';

	public function run()
	{

		$albumId=intval(Yii::app()->getRequest()->getParam('id',0));
		if(!is_dir($this->path.'/'.$albumId) && !mkdir($this->path.'/'.$albumId))
			throw new CHttpException(404,Yii::t('yiiext','Path "{path}" does not exists.',array('{path}'=>$this->path.$dir)));

		$modelClass=$this->modelClass;
		if(isset($_FILES[$modelClass]))
		{
			$model=new $modelClass;
			$model->file=CUploadedFile::getInstance($model,'file');

			if($model->validate()===FALSE)
				self::showError(CHtml::errorSummary($model));

			$this->_file=$model->file;
			if($this->filename===NULL)
				$this->filename=$this->_file->getName();

			$this->beforeSave();
			
			if(!$model->file->saveAs($this->path.'/'.$albumId.'/'.$this->filename))
				throw new CHttpException(404,Yii::t('yiiext','Cannot save file "{file}".',array('{file}'=>$this->path.'/'.$albumId.'/'.$this->filename)));

			$this->afterSave();

			echo Yii::t('yiiext',"File success saved.");
		}
		else
			throw new CHttpException(404,Yii::t('yiiext',"File not sent."));
	}
	public function getFile()
	{
		return $this->_file;
	}
	public function onBeforeSave($event)
	{
		$this->raiseEvent('onBeforeSave',$event);
	}
	public function onAfterSave($event)
	{
		$this->raiseEvent('onAfterSave',$event);
	}
	protected function beforeSave()
	{
		if($this->hasEventHandler('onBeforeSave'))
			$this->onBeforeSave(new CEvent($this));
	}
	protected function afterSave()
	{
		if($this->hasEventHandler('onAfterSave'))
			$this->onAfterSave(new CEvent($this));
	}
}
