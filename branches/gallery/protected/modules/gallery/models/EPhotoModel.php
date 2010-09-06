<?php
/**
 * EPhotoModel class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */
/**
 * EPhotoModel photo model.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package yiiext.modules.gallery
 */
/**
 * @property $id integer Image ID
 * @property $path string Image path
 * @property $description string Image description
 * @property $albumId integer Album ID
 * @property $albumOrder integer Album order
 * @property $createTime timestamp Photo upload time
 *
 * @property $album EAlbumModel Album model
 */
class EPhotoModel extends CActiveRecord
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
		return 'photos';
	}
	public function rules()
	{
		return array(
			array('description','safe'),
			array('albumOrder','default','value'=>0),
			array('createTime','default','value'=>new CDbExpression('NOW()')),
		);
	}
	public function relations()
	{
		return array(
			'album'=>array(self::BELONGS_TO,'EAlbumModel','albumId'),
		);
	}
	public function defaultScope()
	{
		return array(
			'order'=>'`albumOrder` DESC'
		);
	}
	public function scopes()
	{
		return array(
			'onlyId'=>array(
				'select'=>'id'
			),
			'onlyPath'=>array(
				'select'=>'id,path'
			),
			'reverse'=>array(
				'order'=>'`albumOrder` ASC'
			),
		);
	}
	protected function beforeSave()
	{
		if($this->getIsNewRecord())
		{
			// Set last order
			// $this->albumOrder=$this->album->photosCount+1;
			// TODO: Set previous photo
		}

		return parent::beforeSave();
	}
	protected function afterSave()
	{
		// If cover for album is not exist, set it.
		if($this->album->cover===null)
		{
			$this->album->cover=$this->path;
			$this->album->save(true,array('cover'));
		}

		return parent::afterSave();
	}
	public function getPhotoNumber()
	{
		$criteria=new CDbCriteria(array(
			'condition'=>'`albumId`=:albumId AND `albumOrder` > :currentAlbumOrder',
			'params'=>array(
				':albumId'=>$this->albumId,
				':currentAlbumOrder'=>$this->albumOrder,
			),
		));

		$command=$this->getCommandBuilder()->createCountCommand($this->getTableSchema(),$criteria);

		return $command->query()->readColumn(0)+1;
	}
	public function getNextPhoto($select='`id`')
	{
		$criteria=new CDbCriteria(array(
			'select'=>$select,
			'condition'=>'`albumId`=:albumId AND `albumOrder` < :currentAlbumOrder',
			'params'=>array(
				':albumId'=>$this->albumId,
				':currentAlbumOrder'=>$this->albumOrder,
			),
		));

		return self::model()->find($criteria);
	}
	public function getPrevPhoto($select='`id`')
	{
		$criteria=new CDbCriteria(array(
			'select'=>$select,
			'condition'=>'`albumId`=:albumId AND `albumOrder` > :currentAlbumOrder',
			'params'=>array(
				':albumId'=>$this->albumId,
				':currentAlbumOrder'=>$this->albumOrder,
			),
		));

		return self::model()->find($criteria);
	}
}
