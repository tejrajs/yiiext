v0.1

��� ������������� ����� ������� ����� assets � ����� � ��������, ����������� ���� ����� css images � js �� ������ � elfinder.

����� ����� ������ ����� ������������ ���:

<?php

class SiteController extends Controller
{
    public function actions()
    {
        return array(
// ���������� ��������� �� ����-���������
            'fileManager'=>array(
                'class'=>'ext.elfinder.ElFinderAction',
            ),
        );
    }
// ...
}
� � �������������:
<?php
$this->widget('application.my.form.widgets.elfinder.ElFinderWidget',array(
	'lang'=>'ru',
    'url'=>CHtml::normalizeUrl(array('site/fileManager')),
    'editorCallback'=>'js:function(url) {
		var funcNum = window.location.search.replace(/^.*CKEditorFuncNum=(\d+).*$/, "$1");
//      var langCode = window.location.search.replace(/^.*langCode=([a-z]{2}).*$/, "$1");

        window.opener.CKEDITOR.tools.callFunction(funcNum, url);
        window.close();
    }',
//            'htmlOptions'=>array('style'=>'height:500px'),
));

��������, ��� ������ �� ������� ������������� � ����������� ECKEditor