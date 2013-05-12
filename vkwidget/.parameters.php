<? if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = array(
	'PARAMETERS' => array(
    	'LINK' => array(
            'NAME' => 'Идентификатор страницы',
            'TYPE' => 'STRING',
            'MULTIPLE' => 'N',
            'PARENT' => 'BASE',
            'DEFAULT' => 'public20003922',
       	),
		"TYPE" => array(
			"NAME" => 'Тип',
			"TYPE" => "LIST",
			"ADDITIONAL_VALUES" => "N",
			"VALUES" => array('0'=>'Участники', '1'=>'Только название', '2'=>'Новости'),
			"REFRESH" => "Y",
			"PARENT" => "BASE",
		),
    	'WIDTH' => array(
            'NAME' => 'Ширина',
            'TYPE' => 'STRING',
            'MULTIPLE' => 'N',
            'PARENT' => 'VISUAL',
            'DEFAULT' => '220',
       	),
    	'HEIGHT' => array(
            'NAME' => 'Высота',
            'TYPE' => 'STRING',
            'MULTIPLE' => 'N',
            'PARENT' => 'VISUAL',
            'DEFAULT' => '400',
       	),
    	'COLOR_BACKGROUND' => array(
            'NAME' => 'Цвет фона',
            'TYPE' => 'COLORPICKER',
            'MULTIPLE' => 'N',
            'PARENT' => 'VISUAL',
            'DEFAULT' => '#FFFFFF',
       	),
    	'COLOR_TEXT' => array(
            'NAME' => 'Цвет текста',
            'TYPE' => 'COLORPICKER',
            'MULTIPLE' => 'N',
            'PARENT' => 'VISUAL',
            'DEFAULT' => '#2B587A',
       	),
    	'COLOR_BUTTON' => array(
            'NAME' => 'Цвет кнопок',
            'TYPE' => 'COLORPICKER',
            'MULTIPLE' => 'N',
            'PARENT' => 'VISUAL',
            'DEFAULT' => '#5B7FA6',
       	),
       	"CACHE_TIME" => array(),
	),
);
if ($arCurrentValues['TYPE'] == 2) 
{ 
    $arComponentParameters['PARAMETERS']['WIDE'] = array(
    	'NAME' => 'Расширенный режим',
      	'TYPE' => 'CHECKBOX',
     	'MULTIPLE' => 'N',
      	'PARENT' => 'VISUAL',
  	);
} 
?>