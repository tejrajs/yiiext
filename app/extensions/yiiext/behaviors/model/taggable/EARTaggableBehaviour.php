<?php
/**
 * TaggableBehaviour
 *
 * Allows to use AR objects as tags.
 *
 * @version 1.1.0
 * @author 5е-1
 * @link http://code.google.com/p/yiiext/
 */
class EARTaggableBehaviour extends ETaggableBehaviour {
    /**
     * Tag model name
     */
    public $tagModel = 'Tag';

	/**
	 * Creates tag model
	 *
	 * @param string $title tag title
	 * @return CActiveRecord
	 */
    protected function createTag($title) {
        $class = $this->tagModel;
        $tag = new $class();
        $tag->{$this->tagTableName} = $title;
        $tag->save();
    }
}
