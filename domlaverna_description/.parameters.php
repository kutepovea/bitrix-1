<? if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = array(
	'PARAMETERS' => array(
		'IBLOCK_ID' => array(
			'NAME' => 'ID инфоблока',
			'TYPE' => 'STRING',
			'MULTIPLE' => 'N',
			'PARENT' => 'BASE',
		),
		'ELEMENT_ID' => array(
			'NAME' => 'ID элемента',
			'TYPE' => 'STRING',
			'MULTIPLE' => 'N',
			'PARENT' => 'BASE',
		),
		'SECTION_ID' => array(
			'NAME' => 'ID раздела',
			'TYPE' => 'STRING',
			'MULTIPLE' => 'N',
			'PARENT' => 'BASE',
		),
		'TEMPLATE' => array(
			'NAME' => 'Шаблон',
			'TYPE' => 'STRING',
			'MULTIPLE' => 'N',
			'PARENT' => 'BASE',
		),
		'VAR' => array(
			'NAME' => 'Переменные',
			'TYPE' => 'STRING',
			'MULTIPLE' => 'Y',
			'PARENT' => 'BASE',
		),
		'CACHE_TIME'  =>  array('DEFAULT'=>3600),
	),
);
?>