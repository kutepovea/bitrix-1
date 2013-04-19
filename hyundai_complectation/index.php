<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<!DOCTYPE html>
<html>
<head>
	<title>Комплектации</title>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
	<script>
		$(document).ready( function() {
			/*$('.compare-add-model span').click(function() {
				$('.compare-add-type').stop(true, true).slideUp();
				$(this).parent().next('.compare-add-type').slideDown();
			});*/
			var compareWidth = $('.compare-option').outerWidth() + $('.compare-value').length * $('.compare-value').outerWidth();
			if(compareWidth > $(window).width()) {
				$('.compare').css({ 'width': + compareWidth + 'px' });//.draggable({ cursor: 'move',  opacity: 0.7, axis: "x" });
			}
			$('.compare-add-item-open').click(function() {
				$('.compare-add-item-type').removeClass('active');
				$(this).prev('.compare-add-item-type').addClass('active');
			});
			$('.compare-add-item-close').click(function() {
				$(this).parent('.compare-add-item-type').removeClass('active');
			});
		});
	</script>
	<style>
		* { margin: 0; padding: 0; }
		body { background: #ededeb; font-size: 12px; font-family: Arial; color: #000; }
		
		.clear { clear: both; height: 0; }
		
		.viewport { width: 100%; height: 100%; }
		
		.dc-logo { margin: 40px 40px 0 40px; z-index: 999; }
		.dc-logo img { vertical-align: top; border: none; }
		
		.compare { position: relative; background: #fff; margin: 0 40px 40px; padding: 20px; box-shadow: 0 0 10px #ccc; box-shadow: 0 0 10px rgba(0,0,0,0.2); }
		
		.compare-add { clear: both; margin-bottom: 10px; }
		.compare-add-title { font-size: 14px; font-weight: bold; color: #999; margin-bottom: 10px; }
		.compare-add-item { float: left; width: 180px; position: relative; margin-bottom: 20px; min-height: 100px; }
		.compare-add-item-model { font-size: 18px; margin-bottom: 5px; }
		.compare-add-item-model span {  border-bottom: 1px dotted #777; cursor: pointer; }
		.compare-add-item-type { height: 54px; overflow: hidden; margin-bottom: 5px; width: 180px; }
		.compare-add-item-type.active { position: absolute; height: auto; background: #fff; z-index: 1; box-shadow: 0px 4px 7px #ccc; }
		.compare-add-item-type-item { background: url(/images/blue_dot.png) no-repeat center left; padding-left: 15px; margin-bottom: 3px; }
		.compare-add-item-type-item a { text-decoration: none; color: #000; }
		.compare-add-item-type-item a:hover { color: #df2925; }
		.compare-add-item-type-item.active { font-weight: bold; color: #df2925; }
		.compare-add-item-open { font-size: 11px; color: #999; cursor: pointer; }
		.compare-add-item-close { font-size: 11px; color: #999; cursor: pointer; text-align: right; padding: 2px 4px; }
		
		.compare-option { float: left; max-width: 600px; }
		.compare-option-title { height: 100px; line-height: 50px; text-align: center; border-bottom: 1px solid #eee; }
		.compare-option-item { padding: 0 10px; overflow: hidden; white-space: nowrap; height: 28px; line-height: 28px; border-bottom: 1px solid #eee; }
		
		.compare-value { float: left; width: 120px; text-align: center; }
		.compare-value-title { height: 60px; border-bottom: 1px solid #eee; }
		.compare-value-title-model { font-size: 13px; font-weight: bold; }
		.compare-value-title-type { font-size: 11px; color: #999; margin-bottom: 7px; }
		.compare-value-title-delete a { opacity: 0.3; }
		.compare-value-title-delete a:hover { opacity: 1; }
		.compare-value-item { height: 28px; line-height: 28px; font-size: 11px; border-bottom: 1px solid #eee; color: #999; }
		.compare-value-item img { vertical-align: middle; }
		.compare-value-item-price { height: 40px; line-height: 40px; font-size: 14px; background: #eee; color: #333; font-weight: bold; }
	</style>
</head>
<body>

<?

Class Compl {
	public $dbTableCar;
	public $dbTableOption;
	function __construct() {
		$this->dbTableCar = "bf_compl_car";
		$this->dbTableOption = "bf_compl_link";
		if(!empty($_GET["id"])) {
			$arCarId = explode(",", $_GET["id"]);
			foreach($arCarId as $id) {
				if(!is_numeric($id)) header("Location: /complectation/");
			}
		}
	}
	function checkGet($carId) {
		if(!empty($carId)) {
			return true;
		} else {
			return false;
		}
	}
	function getTypeTree() {
		global $DB;
		$arActiveID = explode(",", $_GET["id"]);
		$res = $DB->Query("SELECT * FROM `".$this->dbTableCar."`", false, __LINE__);
		while($item = $res->Fetch()) {
			$arResult[] = $item;
			$arModel[] = $item["model"];
		}
		$arModel = array_unique($arModel);
		foreach($arModel as $model) {
			foreach($arResult as $arItem) {
				if($arItem["model"] == $model) {
					if(array_search($arItem["id"], $arActiveID)) {
						$arTypeTree[$model]["active"] = $arItem["type"]; 
					} else {
						$arTypeTree[$model][$arItem["id"]] = $arItem["type"]; 
					}
				}
			}
		}
		return $arTypeTree;		
	}
	function getCurCar($carId) {
		global $DB;
		$arCarId = explode(",", $carId);
		$res = $DB->Query("SELECT * FROM `".$this->dbTableCar."` WHERE `id` IN ('".implode('\',\'', $arCarId)."')", false, __LINE__);
		while($item = $res->Fetch()) {
			$arCarResult[] = $item;
		}
		return $arCarResult;
	}
	function getCurOption($carId) {
		global $DB;
		$res = $DB->Query("SELECT * FROM `".$this->dbTableOption."` WHERE `car` IN (".$carId.") AND `option` <> 'Цена' GROUP BY `option` ASC ORDER BY `option` ASC", false, __LINE__);
		while($item = $res->Fetch()) {
			$arOptionResult[$item["option"]] = $item["value"];
		}
		return $arOptionResult;
	}
	function getCurValue($optionId) {
		global $DB;
		$res = $DB->Query("SELECT * FROM `".$this->dbTableOption."` WHERE `car` = '".$optionId."' AND `option` <> 'Цена' ORDER BY `option` ASC", false, __LINE__);
		while($item = $res->Fetch()) {
			$arValueResult[$item["option"]] = $item["value"];
		}
		foreach($this->getCurOption($_GET["id"]) as $key=>$option) {
			$arResult[$key] = $arValueResult[$key];
		}
		return $arResult;
	}
	function getPrice($optionId) {
		global $DB;
		$res = $DB->Query("SELECT * FROM `".$this->dbTableOption."` WHERE `car` = '".$optionId."' AND `option` = 'Цена'", false, __LINE__);
		$arPriceResult = $res->Fetch();
		if(!empty($arPriceResult["value"])) {
			return $arPriceResult["value"]."руб.";
		} else {
			return "";
		}
	}
	function getAddLink($id) {
		if(!empty($_GET["id"])) {
			return $_GET["id"].",".$id;
		} else {
			return $id;
		}
	}
	function getDeleteLink($id) {
		$arCarId = explode(",", $_GET["id"]);
		foreach($arCarId as $carId) {
			if($carId == $id) continue;
			$arDeleteLink[] = $carId;
		}
		$deleteLink = implode(",", $arDeleteLink);
		return $deleteLink;
	}
}

$compl = new compl();

?>

<div class="viewport">

	<div class="dc-logo"><a href="http://www.hyundai-altufievo.ru" target="_blank"><img src="/images/dc_name.png" alt="РОЛЬФ Алтуфьево" title="РОЛЬФ Алтуфьево" /></a></div>

	<div class="compare">
		<div class="compare-add">
			<div class="compare-add-title">Добавьте необходимое количество комплектаций для сравнения и ознакомьтесь с результатами под списком комплектаций для выбора:</div>
			<?foreach($compl->getTypeTree() as $model=>$arType) {?>
				<div class="compare-add-item">
					<div class="compare-add-item-model"><span><?=$model;?></span></div>
					<div class="compare-add-item-type">
					<?foreach($arType as $id=>$type) {?>
						<?if($id == "active") {?>
							<div class="compare-add-item-type-item active"><?=$type;?></div>
						<?} else {?>
							<div class="compare-add-item-type-item"><a href="/complectation/?id=<?=$compl->getAddLink($id);?>"><?=$type;?></a></div>
						<?}?>
					<?}?>
					<?if(count($arType) > 3):?><div class="compare-add-item-close">закрыть</div><?endif;?>
					</div>
					<?if(count($arType) > 3):?><div class="compare-add-item-open"><span>показать все &raquo;</span></div><?endif;?>
				</div>
			<?}?>
			<div class="clear"></div>
		</div>
		<?if($compl->checkGet($_GET["id"])) {?>
			<div class="compare-option">
			<div class="compare-option-title"></div>	
			<?foreach($compl->getCurOption($_GET['id']) as $option=>$value) {?>
				<div class="compare-option-item"><?=$option?></div>
			<?}?>
			</div>
			<?foreach($compl->getCurCar($_GET['id']) as $arComplItem) {?>
				<?$arCurValue = $compl->getCurValue($arComplItem["id"]);?>
				<div class="compare-value">
					<div class="compare-value-title">
						<div class="compare-value-title-model"><?=$arComplItem["model"];?></div>
						<div class="compare-value-title-type"><?=$arComplItem["type"];?></div>
						<div class="compare-value-title-delete"><a href="/complectation/?id=<?=$compl->getDeleteLink($arComplItem["id"]);?>"><img src="/images/delete.png" alt="Удалить" title="Удалить" /></a></div>
					</div>
					<div class="compare-value-item-price">
						<?=$compl->getPrice($arComplItem["id"]);?>
					</div>
					<?foreach($compl->getCurValue($arComplItem["id"]) as $option=>$value) {?>
						<?if(!empty($value)) {?>
							<?if($value == "Y") {?>
								<div class="compare-value-item"><img src="/images/green_dot.png" alt="" /></div>
							<?} else {?>
								<div class="compare-value-item"><?=$value;?></div>
							<?}?>
						<?} else {?>
							<div class="compare-value-item">-</div>
						<?}?>
					<?}?>
					<div class="compare-value-item-price">
						<?=$compl->getPrice($arComplItem["id"]);?>
					</div>
				</div>
			<?}?>
		<?}?>	
		<div class="clear"></div>
	</div>
</div>
	
</body>
</html>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>