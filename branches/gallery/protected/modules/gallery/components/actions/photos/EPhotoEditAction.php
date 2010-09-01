<?php
/**
 * EPhotoEditAction class file.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @link http://code.google.com/p/yiiext/
 * @license http://www.opensource.org/licenses/mit-license.php
 */
/**
 * EPhotoEditAction upload a image.
 *
 * @author Veaceslav Medvedev <slavcopost@gmail.com>
 * @version 0.1
 * @package slavcodev.modules.gallery
 */
class EPhotoEditAction extends CAction
{
	public $view='photoedit';
	public $viewData=array();
	public $modelClass='EPhotoModel';

	public function run()
	{
		$modelClass=$this->modelClass;
		$model=new $modelClass;
		
		if(isset($_POST[$modelClass]))
		{
			$model->attributes=$_POST[$modelClass];
			if($model->save())
				$this->getController()->redirect(array('index','id'=>$model->id));
		}

		$this->viewData['model']=$model;
		$this->getController()->render($this->view,$this->viewData);
	}
}
