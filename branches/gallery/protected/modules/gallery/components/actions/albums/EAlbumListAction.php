<?php
/**
 * EAlbumListAction class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */

/**
 * EAlbumListAction album editing action.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package yiiext.modules.gallery
 */
class EAlbumListAction extends CAction
{
	public $modelClass='EAlbumModel';
	public $view='list';
	public $viewData=array(
		'columnsCount'=>5,
	);

	public function run()
	{
		$modelClass=$this->modelClass;
		$controller=$this->getController();
		
		$dataProvider=new CActiveDataProvider($modelClass,array(
			//'criteria'=>array(),
			'pagination'=>array(
				'pageSize'=>$controller->getModule()->albumsPerPage,
			),
		));

		$this->viewData['dataProvider']=$dataProvider;
		$controller->render($this->view,$this->viewData);
	}
}
