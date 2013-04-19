<?function agentCheckStreetsYandex() {
	CModule::IncludeModule("iblock");

	$getCities = CIBlockElement::GetList(array(), array("IBLOCK_ID"=>7), false, false, array("ID", "NAME"));
	$getStreets = CIBlockElement::GetList(array(), array("IBLOCK_ID"=>5, "PROPERTY_CHECKED"=>false), false, array("nPageSize"=>100), array("ID", "NAME", "PROPERTY_LINKED_CITY", "PROPERTY_CHECKED"));

	while($arCity = $getCities->Fetch()) {
		$arCitiesResult[$arCity["ID"]] = $arCity["NAME"];
	}

	while($arStreet = $getStreets->Fetch()) {
		$getXMLStreet = file_get_contents('http://geocode-maps.yandex.ru/1.x/?format=xml&geocode='.(string)$arCitiesResult[$arStreet["PROPERTY_LINKED_CITY_VALUE"]].' '.$arStreet["NAME"]);
		preg_match_all( '#<ThoroughfareName xml:lang="ru">(.+?)</ThoroughfareName>#is', $getXMLStreet, $matches );
		$arUpdate = array(
			"NAME"=>strip_tags((string)$matches[0][0])
		);
		$el = new CIBlockElement;
		$el->Update($arStreet["ID"], $arUpdate);
		CIBlockElement::SetPropertyValues($arStreet["ID"], 5, 1, 35);

		//echo (string)$matches[0][0]." ".$arStreet["ID"]."<br /";
	}

	return "agentCheckStreetsYandex();";
}
?>