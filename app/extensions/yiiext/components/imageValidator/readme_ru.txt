Image Validator
=============
��������� ��� ��������� ��������, ������, ������, mime ���. �������� ����������� ��
CFileValidate �� ����� �������� ��� ����������� ����� ����������, �������� ������ �����,
����������.

���������
---------------------
� `protected/config/main.php` ��������:
~~~
[php]
'import'=>array(
    'ext.yiiext.components.imageValidator.*'
),
~~~

�������������
---------------------
public function rules()
{
	return array(
		array('inputname', 'EImageValidator', 'on'=>'a'),
	);
}