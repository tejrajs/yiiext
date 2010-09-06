<?php
/**
 * EAlbumModel class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package slavcodev.gallery
 */
/**
 * @property $id integer Album ID
 * @property $name string Album name
 * @property $location string Album location
 * @property $description string Album description
 * @property $cover string Album cover path
 * @property $createTime timestamp Album create time
 * @property $modifyTime timestamp Album modify time
 * 
 * @property $photos array Album photos
 * @property $photosCount integer Album photos count
 */
class EAlbumModel extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function tableName()
	{
		return 'albums';
	}
	public function rules()
	{
		return array(
			array('name,location,description','safe'),
			array('createTime','default','value'=>new CDbExpression('NOW()')),
			array('modifyTime','default','value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false),
			//array('cover','default','value'=>Yii::app()->getController()->getModule()->getAssetsUrl().'/unknown.png'),
		);
	}
	public function relations()
	{
		return array(
			'photos'=>array(self::HAS_MANY,'EPhotoModel','albumId'),
			'photosCount'=>array(self::STAT,'EPhotoModel','albumId'),
		);
	}
	public function scopes()
	{
		return array(
			'onlyId'=>array(
				'select'=>'id'
			),
			'onlyName'=>array(
				'select'=>'id,name'
			),
		);
	}
	protected function afterDelete()
	{
		// Delete all photos. 
		if($this->photosCount>0)
		{
			// From DB
			foreach($this->photos as $photo)
				$photo->delete();
			
			// TODO: From FS
		}

		return parent::afterDelete();
	}
	public function getFirstPhoto($select='`id`')
	{
		$criteria=new CDbCriteria(array(
			'select'=>$select,
			'condition'=>'`albumId`=:albumId',
			'params'=>array(
				':albumId'=>$this->id,
			),
		));

		return EGalleryModule::photo()->find($criteria);
	}
	public function getLastPhoto($select='`id`')
	{
		$criteria=new CDbCriteria(array(
			'select'=>$select,
			'condition'=>'`albumId`=:albumId',
			'params'=>array(
				':albumId'=>$this->id,
			),
		));

		return EGalleryModule::photo()->reverse()->find($criteria);
	}
}
