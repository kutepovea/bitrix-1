<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/prolog.php");

if(!$USER->IsAdmin())
	$APPLICATION->AuthForm();
	
$APPLICATION->SetTitle("Импорт интернет тарифов");
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/prolog_admin_after.php");

Class Internet {
	public $sumResult;
	function prepareImport($file, $arPlan, $city) {
		$file = $_SERVER["DOCUMENT_ROOT"].$file;
		//echo $file.'<br />';
		if (file_exists($file)) {
			$data = file($file);
			foreach($data as $key=>$arItem) {
				@list($$_POST["FIELDS_ORDER"][0], $$_POST["FIELDS_ORDER"][1], $$_POST["FIELDS_ORDER"][2]) = explode(";", trim($arItem));
				$arImport[$key]["street"] =  str_replace("\"", "", $street);
				$arImport[$key]["house"] = $house;
				$arImport[$key]["building"] = $building;
			}
			
			$checkByXML = CIBlockElement::GetList(Array(), array("IBLOCK_ID"=>5, 'PROPERTY_LINKED_ISP'=>$arPlan), false, false, array("ID", "XML_ID"));
			while($ob = $checkByXML->GetNextElement()) {
				$arFields = $ob->GetFields();
				$arAllXML[] = $arFields["XML_ID"]; 
			}
			/*if (getenv('REMOTE_ADDR') == '79.142.82.62') {
				echo 'arAllXML: <pre>'; print_r($arAllXML); echo '</pre><br />';
				//echo 'arPlan: <pre>'; print_r($arPlan); echo '</pre><br />';
				//echo 'arImport: <pre>'; print_r($arImport); echo '</pre><br />';
				//exit();
			}*/

			$citys = $this->getCityList();

			$el = new CIBlockElement;

			foreach($arPlan as $arPlanItem) {
				foreach($arImport as $arImportItem) {				
					if(prev($arImport) && $_POST["FIRST_STRING"] == "Y") continue;
				
					$PROP = array();
					$PROP[14] = $arImportItem["house"];
					$PROP[15] = $arImportItem["building"];
					$PROP[16] = $arPlanItem;
					$PROP[24] = $city;

					$NAME = ((mb_detect_encoding($arImportItem["street"], mb_detect_order(), true) == "UTF-8") ? $arImportItem["street"] : iconv("cp1251","UTF-8",$arImportItem["street"]));
					$XML_ID = md5($city.'_'.$NAME."_".$arImportItem["house"]."_".$arImportItem["building"]."_".$arPlanItem);

					/*if (getenv('REMOTE_ADDR') == '79.142.82.62') {
						echo $XML_ID.'<br />';
						exit();
					}*/

					if(in_array($XML_ID, $arAllXML)) {
						$this->sumResult .= '<div class="adm-info-message-wrap adm-info-message-red"><div class="adm-info-message" style="margin: 0 0 10px"><div class="adm-info-message-title">'.$NAME.' '.$arImportItem["house"].' '.$arImportItem["building"].' уже в базе</div><div class="adm-info-message-icon"></div></div></div>';
						continue;
					}
					
					//$getJsonName = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.urlencode('Россия '.$citys[$city].' '.$NAME).'&sensor=false&language=ru');
					//echo 'http://maps.google.com/maps/api/geocode/json?address='.urlencode('Россия '.$citys[$city]['NAME'].' '.$NAME).'&sensor=false&language=ru<br />';
					//$jsonNAME = json_decode($getJsonName, true);
					/*if ($jsonNAME['status'] == 'OK') {*/
						//echo '<pre>'; print_r($jsonNAME); echo '</pre>';
						//$NAME = $jsonNAME['results'][0]['address_components'][0]['short_name'];
						//echo $XML_ID.' '.$NAME.'<br />';

						$arLoadProductArray = Array(
							"XML_ID" => $XML_ID,
							"IBLOCK_SECTION_ID" => false,
							"IBLOCK_ID" => 5,
							"PROPERTY_VALUES"=> $PROP,
							"NAME" => $NAME,
							"ACTIVE" => "Y"
						);
						
						//echo "<pre>"; print_r($arLoadProductArray); echo "</pre>";
						
						if($PRODUCT_ID = $el->Add($arLoadProductArray, false, false)) {
							$this->sumResult .= '<div class="adm-info-message-wrap adm-info-message-green"><div class="adm-info-message" style="margin: 0 0 10px"><div class="adm-info-message-title">'.$NAME.' '.$arImportItem["house"].' '.$arImportItem["building"].'  добавлен в базу</div><div class="adm-info-message-icon"></div></div></div>';
						}
					/*} else if ($jsonNAME['status'] == 'OVER_QUERY_LIMIT') {
						$this->sumResult .= '<div class="adm-info-message-wrap adm-info-message-red"><div class="adm-info-message" style="margin: 0 0 10px"><div class="adm-info-message-title">служба Google получила слишком много запросов от вашего приложения в разрешенный период времени</div><div class="adm-info-message-icon"></div></div></div>';
						return false;
					}*/
				}
			}
		} else {
			$data = false;
			$this->sumResult = '<div class="adm-info-message-wrap adm-info-message-red"><div class="adm-info-message" style="margin: 0 0 10px"><div class="adm-info-message-title">Ошибка обработки файла</div><div class="adm-info-message-icon"></div></div></div>';
		}
	}
	function getPlanList() {
		$arSelect = Array("ID", "NAME", "PROPERTY_LINKED_ISP");
		$arFilter = Array("IBLOCK_ID"=>4, "ACTIVE"=>"Y");
		$res = CIBlockElement::GetList(array('NAME'=>'ASC'), $arFilter, false, false, $arSelect);
		while($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			$arFields["LINKED_ISP"] = CIBlockElement::GetByID($arFields["PROPERTY_LINKED_ISP_VALUE"])->Fetch();
			$arResult[] = $arFields;
		}
		return $arResult;
	}
	function getCityList($ids=false) {
		$arSelect = array("ID", "NAME");
		$arFilter = array("IBLOCK_ID"=>7, "ACTIVE"=>"Y");
		if ($ids !== false && is_array($ids)) {
			$arFilter['ID'] = $ids;
		}
		$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		while($ob = $res->GetNextElement()) {
			$arFields = $ob->GetFields();
			$arResult[$arFields['ID']] = $arFields;
		}
		return $arResult;
	}
	function getFieldList() {
		$arFields = array("street" => "Улица", "house" => "Дом", "building" => "Корпус");
		return $arFields;
	}
}

$internet = new Internet();

if(isset($_POST["URL_DATA_FILE"])) {
	$internet->prepareImport($_POST["URL_DATA_FILE"], $_POST["PLAN"], $_POST["CITY"]);
}
?>

<table width="100%">
	<tr>
	<td width="50%" valign="top">
		<form action="" method="POST">

		<div style="margin: 20px 0;">
			<input type="text" name="URL_DATA_FILE" value="" size="30" id="internet_import_csv" />
			<input type="button" value="Открыть" onclick="BtnClick()" />
		</div>
		
		<div style="margin-bottom: 20px;">
			<?for($i = 1; $i <= $countField = count($internet->getFieldList()); $i++):?>
				<select name="FIELDS_ORDER[]">
					<?foreach($internet->getFieldList() as $key=>$option):?>
						<option value="<?=$key;?>"><?=$option;?></option>
					<?endforeach;?>
				</select>
			<?endfor;?>
		</div>

		<div style="margin-bottom: 20px;">
		<select name="CITY">
			<?foreach($internet->getCityList() as $arItem):?>
				<option value="<?=$arItem["ID"];?>"><?=$arItem["NAME"];?></option>
			<?endforeach;?>
		</select>
		</div>
		
		<div style="margin-bottom: 20px;">
		<?foreach($internet->getPlanList() as $arItem):?>
			<div><label><input type="checkbox" name="PLAN[]" value="<?=$arItem["ID"]?>"/><b><?=$arItem["LINKED_ISP"]["NAME"];?></b> / <?=$arItem["NAME"];?></label></div>
		<?endforeach;?>
		</div>
		
		<div style="margin-bottom: 20px;">
			<label><input type="checkbox" name="FIRST_STRING" value="Y"/><i>Первая строка содержит имена полей</i></label>
		</div>	
		
		<div style="margin-bottom: 20px;">
			<input type="submit" class="adm-btn-save" />
		</div>		
				
		</form>
	</td>
	<td width="50%" valign="top">
		<?echo $internet->sumResult;?>
	</td>
	</tr>		
</table>
		
<script>
	var mess_SESS_EXPIRED = 'Ошибка файлового диалога: Сессия пользователя истекла';
	var mess_ACCESS_DENIED = 'Ошибка файлового диалога: У вас недостаточно прав для использования диалога выбора файла';
	window.BtnClick = function(bLoadJS, Params)
	{
		if (!Params)
			Params = {};

			var UserConfig =
			{
				site : 's1',
				path : '/upload',
				view : 'list',
				sort : 'type',
				sort_order : 'asc'
			};
			
		if (!window.BXFileDialog)
		{
			if (bLoadJS !== false)
				BX.loadScript("/bitrix/js/main/file_dialog.js?v=1361365765");
			return setTimeout(function(){window['BtnClick'](false, Params)}, 50);
		}

		var oConfig =
		{
			submitFuncName : 'BtnClickResult',
			select : 'F',
			operation: 'O',
			showUploadTab : true,
			showAddToMenuTab : false,
			site : 's1',
			path : '/upload',
			lang : 'ru',
			fileFilter : 'csv',
			allowAllFiles : true,
			saveConfig : true,
			sessid: phpVars["bitrix_sessid"],
			checkChildren: true,
			genThumb: true,
			zIndex: 2500				};

		if(window.oBXFileDialog && window.oBXFileDialog.UserConfig)
		{
			UserConfig = oBXFileDialog.UserConfig;
			oConfig.path = UserConfig.path;
			oConfig.site = UserConfig.site;
		}

		if (Params.path)
			oConfig.path = Params.path;
		if (Params.site)
			oConfig.site = Params.site;

		oBXFileDialog = new BXFileDialog();
		oBXFileDialog.Open(oConfig, UserConfig);
	};
	window.BtnClickResult = function(filename, path, site, title, menu)
	{
		path = jsUtils.trim(path);
		path = path.replace(/\\/ig,"/");
		path = path.replace(/\/\//ig,"/");
		if (path.substr(path.length-1) == "/")
			path = path.substr(0, path.length-1);
		var full = (path + '/' + filename).replace(/\/\//ig, '/');
		if (path == '')
			path = '/';

		var arBuckets = [];
		if(arBuckets[site])
		{
			full = arBuckets[site] + filename;
			path = arBuckets[site] + path;
		}

		document.getElementById('internet_import_csv').value = path+"/"+filename;		
		
		//if ('F' == 'D')
		//	name = full;
		//	document.dataload.URL_DATA_FILE.value = full;
		//	BX.fireEvent(document.dataload.URL_DATA_FILE, 'change');
	};
	if (window.jsUtils) {
		jsUtils.addEvent(window, 'load', function(){jsUtils.loadJSFile("/bitrix/js/main/file_dialog.js?v=1361365765");}, false);
	}
</script>

<?
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");
?>