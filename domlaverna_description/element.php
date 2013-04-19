<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
	$_REQUEST['SECTION_ID'] = $arResult["VARIABLES"]["SECTION_ID"];
?>
	<div id="main">

		<div class="headline"><?$APPLICATION->ShowTitle();?></div>

		<div id="locator">
			<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "lv", array(
	"START_FROM" => "0",
	"PATH" => "",
	"SITE_ID" => "s1"
	),
	false
);?>
		</div>

		<hr/>

		<div id="content">
			<div id="left-column">
<?$APPLICATION->IncludeComponent("bitrix:main.include", ".default", array(
	"AREA_FILE_SHOW" => "file",
	"PATH" => "/catalog/sect_inc.php",
	"EDIT_TEMPLATE" => ""
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "Y"
	)
);?>
			</div>

			<div id="right-column">

<?$ElementID=$APPLICATION->IncludeComponent(
	"bitrix:catalog.element",
	"",
	Array(
 		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
 		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
 		"PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
		"META_KEYWORDS" => $arParams["DETAIL_META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["DETAIL_META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["DETAIL_BROWSER_TITLE"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
 		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
 		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
 		"SET_TITLE" => "N",
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
		"LINK_IBLOCK_TYPE" => $arParams["LINK_IBLOCK_TYPE"],
		"LINK_IBLOCK_ID" => $arParams["LINK_IBLOCK_ID"],
		"LINK_PROPERTY_SID" => $arParams["LINK_PROPERTY_SID"],
		"LINK_ELEMENTS_URL" => $arParams["LINK_ELEMENTS_URL"],

		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],

 		"ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
 		"ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
 		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
 		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		"ADD_SECTIONS_CHAIN" => "N",
	),
	$component
);?>
			</div>

		</div>

	</div>

<?
$obNavigation = CIBlockSection::GetNavChain($arParams["IBLOCK_ID"], $arResult["VARIABLES"]["SECTION_ID"]);
$code = '';
$name = '';
while ($arNavigation = $obNavigation->GetNext()) {
	if($arNavigation['DEPTH_LEVEL'] == 1) {
		$code = $arNavigation['CODE'];
	}
	else {
		$APPLICATION->AddChainItem($arNavigation['NAME'], str_replace('#ROOT_CODE#', $code, $arNavigation['SECTION_PAGE_URL']));
		$name = $arNavigation['NAME'];
	}
}

$obElement = CIBlockElement::GetByID($arResult["VARIABLES"]["ELEMENT_ID"]);
if($arElement = $obElement->GetNext()) {
	$APPLICATION->SetTitle($arElement['NAME'].' - '.$name);
}

?>

<?
	$obResult = CIBlockSection::GetByID($arResult["VARIABLES"]["SECTION_ID"]);
	if($arSection = $obResult->GetNext())
		$APPLICATION->SetTitle($arSection['NAME']);


	$obResult = CIBlockElement::GetByID($arResult["VARIABLES"]["ELEMENT_ID"]);
	if($arElement = $obResult->GetNext())
		$APPLICATION->AddChainItem($arElement['NAME']);
?>

<?/*$APPLICATION->IncludeComponent("traffic:description", ".default", array(
	"IBLOCK_ID" => $arParams["IBLOCK_ID"],
	"ELEMENT_ID" => $arElement["ID"],
	"SECTION_ID" => $arElement["IBLOCK_SECTION_ID"],
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "3600"
	),
	false
);*/?>