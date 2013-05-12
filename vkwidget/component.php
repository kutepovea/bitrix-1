<?
/**
* Кастомный импорт
*/

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

CModule::IncludeModule('iblock');
$APPLICATION->AddHeadScript('//vk.com/js/api/openapi.js');

if(substr($arParams["LINK"], 0, 6) == "public") {
	$arResult["LINK"] = substr($arParams["LINK"], 6);
} else {
	$getGroupXML = file_get_contents("https://api.vk.com/method/groups.getById?gids=".$arParams["LINK"]);
	$arGroupXML = json_decode($getGroupXML);
	$arResult["LINK"] = $arGroupXML->response[0]->gid;
}

$this->IncludeComponentTemplate($componentPage);
?>