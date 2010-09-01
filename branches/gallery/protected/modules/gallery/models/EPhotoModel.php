<?php
/**
 * EPhotoModel class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package slavcodev.gallery
 */
/**
 * @property $id integer Image ID
 * @property $path string Image path
 * @property $description string Image description
 * @property $albumId integer Album ID
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
	public function relations()
	{
		return array(
			'album'=>array(self::BELONGS_TO, 'EAlbumModel', 'albumId'),
		);
	}
	protected function afterSave()
	{
		$this->checkIsCover();
		return parent::afterSave();
	}
	protected function checkIsCover()
	{
		$album=CActiveRecord::model('EAlbumModel')->findByPk($this->albumId);
		if($album->cover===null)
		{
			$album->cover=$this->path;
			return $album->save(true,array('cover'));
		}
		return false;
	}
}
