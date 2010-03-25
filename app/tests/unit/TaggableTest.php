<?php
/**
 * TaggableTest.php
 */
class TaggableTest extends CDbTestCase {
    public $fixtures=array(
        'posts'=>'Post',
    );

    function setUp($table='Tag'){
        parent::setUp();
        Yii::app()->db->createCommand("truncate {$table}")->query();
        Yii::app()->db->createCommand("truncate Post{$table}")->query();
    }

    private function assertTagsAreEqual($tags1, $tags2){
        $diff = array_merge(array_diff($tags1, $tags2), array_diff($tags2, $tags1));
        if(!empty($diff)){
            echo '1='.print_r($tags1, true);
            echo '2='.print_r($tags2, true);
        }
        $this->assertEquals(array(), $diff);
    }

    private function prepareTags(){
        $this->setUp();

        $post = Post::model()->findByPk(1);
        $post->setTags("yii, mysql, php")->save();

        $post = Post::model()->findByPk(2);
        $post->setTags("yii, php")->save();
    }

    function testGetTags(){
        $this->prepareTags();

        $post = Post::model()->findByPk(1);

        $this->assertTagsAreEqual($post->getTags(), array('yii', 'php', 'mysql'));
    }

    function testSetTags(){
        $this->setUp();

        $post = Post::model()->findByPk(1);
        $post->setTags("php,yii , cool tag  ")->save();
        
        $post = Post::model()->findByPk(1);
        $this->assertTagsAreEqual(array("php", "yii", "cool tag"), $post->getTags());

        $post = Post::model()->findByPk(1);
        $post->setTags("php")->save();

        $post = Post::model()->findByPk(1);
        $this->assertTagsAreEqual(array("php"), $post->getTags());

        $post = Post::model()->findByPk(1);
        $post->setTags(array("php", "yii"))->save();
        
        $post = Post::model()->findByPk(1);
        $this->assertTagsAreEqual(array("php", "yii"), $post->getTags());
    }

    function testAddTags(){
        $this->prepareTags();

        $post = Post::model()->findByPk(1);        
        $post->addTags("  yii, cool tag")->save();

        $post = Post::model()->findByPk(1);
        $this->assertTagsAreEqual(array("php", "yii", "cool tag", "mysql"), $post->getTags());
    }

    function testRemoveTags(){
        $this->prepareTags();
        
        $post = Post::model()->findByPk(1);
        $post->removeTags("yii")->save();

        $post = Post::model()->findByPk(1);
        $this->assertTagsAreEqual(array("php", "mysql"), $post->getTags());
    }

    function testRemoveAllTags(){
        $this->prepareTags();

        $post = Post::model()->findByPk(1);
        $post->removeAllTags()->save();

        $post = Post::model()->findByPk(1);
        $this->assertTagsAreEqual(array(), $post->getTags());
    }

    function testGetAllTagsWithModelsCount(){
        $this->prepareTags();       

        $tagsWithModelsCount = Post::model()->getAllTagsWithModelsCount();

        $this->assertTrue(in_array(array(
            'name' => 'yii',
            'count' => 2
        ), $tagsWithModelsCount));

        $this->assertTrue(in_array(array(
            'name' => 'mysql',
            'count' => 1
        ), $tagsWithModelsCount));

        $this->assertTrue(in_array(array(
            'name' => 'php',
            'count' => 2
        ), $tagsWithModelsCount));
    }    

    function testGetAllTags(){
        $this->prepareTags();

        $tags = Post::model()->getAllTags();
        $this->assertTagsAreEqual($tags, array('php', 'yii', 'mysql'));
    }

    function testAfterDelete(){
        $this->prepareTags();
        $post = Post::model()->findByPk(1);
        $post->delete();

        $count = Yii::app()->db->createCommand("select count(*) from PostTag where postId = 1")->queryScalar();
        $this->assertEquals(0, $count);
    }

    function testToString(){
        $this->prepareTags();

        $post = Post::model()->findByPk(1);
        $this->assertEquals("yii, mysql, php", $post->tags->toString());
    }

    function testHasTag(){
        $this->prepareTags();

        $post = Post::model()->findByPk(1);
        
        $this->assertTrue($post->hasTag("yii"));
        $this->assertFalse($post->hasTags("yii, cakephp"));
    }

    function testTaggedWith(){
        $this->prepareTags();

        $postCount = Post::model()->count();
        $this->assertEquals(3, $postCount);

        $posts = Post::model()->taggedWith('php, yii')->findAll();
        $this->assertEquals(2, count($posts));

        $posts = Post::model()->taggedWith(array('php', 'yii'))->findAll();
        $this->assertEquals(2, count($posts));
    }    

    function testTaggedWithCount(){
        $this->prepareTags();

        $postCount = Post::model()->taggedWith('php, yii')->count();
        $this->assertEquals(2, $postCount);
    }

    /**
     * @expectedException Exception
     */
    function testCreateTagsAutomaticallyOff(){        
        $this->prepareTags();

        $post = Post::model()->findByPk(1);
        $post->createTagsAutomatically = false;
        $post->addTags("non existing tag")->save();
    }

    function testTwoTagDimensions(){
        $this->setUp();

        $post = Post::model()->findByPk(1);
        $post->colors->addTags("blue");
        $post->addTags("test");        
        $post->save();
    }

    function testTagTableName(){
        $this->setUp('Food');

        $post = Post::model()->findByPk(1);
        $post->food->addTags('apple, meat');
        $post->save();
        $this->assertTagsAreEqual($post->food->getTags(), array('apple', 'meat'));
        
        $post = Post::model()->findByPk(1);
        $post->food->addTags("pear");
        $post->save();
        $this->assertTagsAreEqual($post->food->getTags(), array('apple', 'meat', 'pear'));
        
        $post = Post::model()->findByPk(1);
        $post->food->removeTags("meat");
        $post->save();
        $this->assertTagsAreEqual($post->food->getTags(), array('apple', 'pear'));
        
        $post = Post::model()->findByPk(1);
        $post->food->setTags("pear, meat");
        $post->save();
        $this->assertTagsAreEqual($post->food->getTags(), array('meat', 'pear'));
        
        $post = Post::model()->findByPk(1);
        $food = $post->food->getAllTags();
        $this->assertTagsAreEqual($food, array('apple', 'meat', 'pear'));
    }
    
    function testTagTableCount(){
        $this->setUp('Food');

        $post1 = Post::model()->findByPk(1);
        $post1->food->addTags("apple, meat");
        $post1->save();
        
        $post2 = Post::model()->findByPk(2);
        $post2->food->addTags("pear, meat");
        $post2->save();
        // 1-apple, meat; 2-meat, pear
        $tagsWithModelsCount = Post::model()->food->getAllTagsWithModelsCount();

        $this->assertTrue(in_array(array(
            'name' => 'apple',
            'count' => 1
        ), $tagsWithModelsCount));

        $this->assertTrue(in_array(array(
            'name' => 'meat',
            'count' => 2
        ), $tagsWithModelsCount));

        $this->assertTrue(in_array(array(
            'name' => 'pear',
            'count' => 1
        ), $tagsWithModelsCount));
        
        $post1 = Post::model()->findByPk(1);
        $post1->food->removeTags("meat");
        $post1->save();
        // 1-apple; 2-meat, pear
        $tagsWithModelsCount = Post::model()->food->getAllTagsWithModelsCount();

        $this->assertTrue(in_array(array(
            'name' => 'apple',
            'count' => 1
        ), $tagsWithModelsCount));

        $this->assertTrue(in_array(array(
            'name' => 'meat',
            'count' => 1
        ), $tagsWithModelsCount));

        $this->assertTrue(in_array(array(
            'name' => 'pear',
            'count' => 1
        ), $tagsWithModelsCount));
        
        $post2 = Post::model()->findByPk(2);
        $post2->food->setTags("pear, apple");
        $post2->save();
        // 1-apple; 2-apple, pear
        $tagsWithModelsCount = Post::model()->food->getAllTagsWithModelsCount();

        $this->assertTrue(in_array(array(
            'name' => 'apple',
            'count' => 2
        ), $tagsWithModelsCount));

        $this->assertTrue(in_array(array(
            'name' => 'meat',
            'count' => 0
        ), $tagsWithModelsCount));

        $this->assertTrue(in_array(array(
            'name' => 'pear',
            'count' => 1
        ), $tagsWithModelsCount));
    }
}
