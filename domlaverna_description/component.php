<pre>
<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
//if($USER->isAdmin()):
//if ($this->StartResultCache(3600)) {

	$arFilter = Array('IBLOCK_ID'=>21);
	$db_list = CIBlockSection::GetList(Array(), $arFilter, false, Array("ID", "IBLOCK_SECTION_ID", "UF_DESC_TEMPLATE"));
	$arres = array();
	while($ob = $db_list->Fetch()) {
		$arres[$ob["ID"]] = $ob;
	}
	
	function checktemplate($arres, $id, $i = 0, $j = 5) {
		//echo $id;
		$i++;
		if($i > $j) return false;
		foreach($arres as $aritem) {
			//print_r($aritem);
			if(($id == (int)$aritem["ID"] && !empty($aritem["UF_DESC_TEMPLATE"]))) { 
				return $aritem["UF_DESC_TEMPLATE"];
			} else {

			}
		}
		if(is_null($arres[$id]["IBLOCK_SECTION_ID"])) { 
			return $arres[$id]["UF_DESC_TEMPLATE"];
		} else {
			return checktemplate($arres, (int)$arres[$id]["IBLOCK_SECTION_ID"], $i, $j);
		}
	}
		
	$descResult = checktemplate($arres, $arParams["SECTION_ID"]);
	
	/*$arFilter = Array('IBLOCK_ID'=>$arParams["IBLOCK_ID"], 'ID'=>$arParams["SECTION_ID"]);
	$getParentSection = CIBlockSection::GetList(Array(), $arFilter, false, Array("ID", "UF_DESC_TEMPLATE"));
	$parentSection = $getParentSection->Fetch();

	$descResult = $parentSection["UF_DESC_TEMPLATE"];*/

	$pattern = '#[A-Z\_]+#'; 
	preg_match_all($pattern, $descResult, $matches); 
	
	$arProperty = array();
	
	foreach($matches[0] as $item) {
		if($item == "NAME") continue;
		$arProperty[] = "PROPERTY_".$item;
	}
	
	$arFilter = Array('IBLOCK_ID'=>$arParams["IBLOCK_ID"], 'ID' => $arParams["ELEMENT_ID"]);
	$getElement = CIBlockElement::GetList(Array(), $arFilter, false, array_merge(array("ID", "NAME"), $arProperty));
	$arElement = $getElement->GetNextElement();
	
	$arElementFields = $arElement->GetFields();
	$arElementProps = $arElement->GetProperties();
	
	if(empty($arElementProps["ITEM_DESCRIPTION"]["VALUE"])) {
		
		foreach($arElementProps as $key=>$arItem) {
			if($arItem["PROPERTY_TYPE"] == "E") {
				$value = CIBlockElement::GetByID($arItem["VALUE"]);
				$value = $value->Fetch();
				$arItem["VALUE"] = $value["NAME"];
			}
			$descResult = str_replace("#".$key."#", strip_tags($arItem["VALUE"]), $descResult);
		}

		$descResult = str_replace("#NAME#", strip_tags($arElementFields["NAME"]), $descResult);
		
		//echo '<pre>'; print_r($descResult); echo '</pre>';
		
		$APPLICATION->SetPageProperty("description", $descResult);
	
	}
//}
//endif;
?>
</pre>