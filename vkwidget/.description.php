<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => 'Сообщества',
	"DESCRIPTION" => 'Компонент выводит виджет для сообществ вконтакте.',
	"ICON" => '/images/icon.gif',
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "utility",
	    "CHILD" => array(
	    	"ID" => "vkwidgets",
	        "NAME" => "Виджеты Вконтакте"
	    )
	),
);
?>
