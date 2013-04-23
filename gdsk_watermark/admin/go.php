<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/prolog.php");

if(!$USER->IsAdmin())
	$APPLICATION->AuthForm();
	
$APPLICATION->SetTitle("Водяной знак");
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/prolog_admin_after.php");

if(!empty($_POST["url"])) {
	$ar_cururl = explode("/", $_POST["url"]);
	$filename = end($ar_cururl);
	$subdir = $ar_cururl[4]."/".$ar_cururl[5];
	
	if($_POST["color"] == "black") {
		$watermarkFile = $_SERVER['DOCUMENT_ROOT']."/bitrix/images/watermark_black.png";
	} else {
		$watermarkFile = $_SERVER['DOCUMENT_ROOT']."/bitrix/images/watermark_white.png";
	}
	$arFilter_watermark = Array(
		Array(
			'name' => 'watermark',
			'position' => $_POST["position"],
			'size' => 'real',
			'type' => 'image',
			'alpha_level' => $_POST["alpha"],
			'file' => $watermarkFile
		)
	);

	$res = CFile::GetList(array("FILE_SIZE" => "desc"), array("FILE_NAME" => $filename, "SUBDIR" => $subdir));
	if($res_arr = $res->GetNext()) {
		$oldImage = $_SERVER["DOCUMENT_ROOT"]."upload/".$res_arr["SUBDIR"]."/".$res_arr["FILE_NAME"];
		$result = CFile::ResizeImageGet($res_arr["ID"], $arSize = false, $resizeType = BX_RESIZE_IMAGE_PROPORTIONAL, $bInitSizes = false, $arFilter_watermark);
		$newImage = $_SERVER["DOCUMENT_ROOT"].$result["src"];
		if(unlink($oldImage)) {
			rename($newImage, $oldImage);
			$updatedImage = "/upload/".$res_arr["SUBDIR"]."/".$res_arr["FILE_NAME"];
		} else {
			$error = true;
		}
	} else {
		$oldImage = explode("/", $_POST["url"]);
		unset($oldImage[0], $oldImage[1], $oldImage[2]);
		$oldImage = $_SERVER["DOCUMENT_ROOT"].implode("/", $oldImage);
		$arFile = CFile::MakeFileArray($oldImage);
		if($newFileID = CFile::SaveFile($arFile, "tmp")) {
			if($result = CFile::ResizeImageGet($newFileID, $arSize = false, $resizeType = BX_RESIZE_IMAGE_PROPORTIONAL, $bInitSizes = false, $arFilter_watermark)) {
				$newImage = $_SERVER["DOCUMENT_ROOT"].$result["src"];
				if(unlink($oldImage)) {
					rename($newImage, $oldImage);
					CFile::Delete($newFileID);
					$updatedImage = $_POST["url"];
				} else {
					$error = true;
				}
			} else {
				$error = true;
			}
		} else {
			$error = true;
		}
	}
}
?>
<?if($error == true):?>
<div class="adm-info-message-wrap adm-info-message-red">
	<div class="adm-info-message">
		<div class="adm-info-message-title">Ошибка</div>
			Изображение не найдено.
		<div class="adm-info-message-icon"></div>
	</div>
</div>
<?else:?>
<div class="adm-info-message-wrap">
	<div class="adm-info-message"><b>Внимание:</b> старая версия изображения будет удалена.</div>
</div>
<?endif;?>
<table class="adm-detail-content-table edit-table">
	<form action="" method="POST">
	<tr>
		<td class="adm-detail-content-cell-l">
			URL изображения:
		</td>
		<td class="adm-detail-content-cell-r">
			<input type="text" name="url" size="40" style="margin:0;" placeholder="Пример: http://www.gdsk.ru/test.jpg"/>
		</td>
	</tr>
	<tr>
		<td class="adm-detail-content-cell-l">
			Цвет знака:
		</td>
		<td class="adm-detail-content-cell-r">
			<select name="color">
				<option value="white">Светлый</option>
				<option value="black">Темный</option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="adm-detail-content-cell-l">
			Прозрачность:
		</td>
		<td class="adm-detail-content-cell-r">
			<select name="alpha">
				<option value="100">100%</option>
				<option value="80">80%</option>
				<option value="60" selected="selected">60%</option>
				<option value="40">40%</option>
				<option value="20">20%</option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="adm-detail-content-cell-l">
			Расположение:
		</td>
		<td class="adm-detail-content-cell-r">
			<input type="radio" name="position" value="tl" /><input type="radio" name="position" value="tc" /><input type="radio" name="position" value="tr" /><br />
			<input type="radio" name="position" value="ml" /><input type="radio" name="position" value="mc" /><input type="radio" name="position" value="mr" /><br />
			<input type="radio" name="position" value="bl" /><input type="radio" name="position" value="bc" /><input type="radio" name="position" value="br" checked="checked"/>
		</td>
	</tr>
	<tr>
		<td class="adm-detail-content-cell-l">
		</td>
		<td class="adm-detail-content-cell-r">
			<input type="submit" class="adm-btn-save" />
		</td>
	</tr>
	</form>
	<?if(isset($updatedImage)):?>
	<tr>
		<td class="adm-detail-content-cell-l">
			Результат:
		</td>
		<td class="adm-detail-content-cell-r">
			<div style="padding: 10px 0;"><input type="text" value="<?=$updatedImage;?>" size="40" onclick="select(this);"/><input type="button" value="Открыть" onclick="window.open('<?=$updatedImage;?>','_blank'); return false;"/></div>
		</td>
	</tr>
	<?endif;?>
</table>
<?
require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin.php");
?>
