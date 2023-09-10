<?php
use Bitrix\Main\Localization\Loc;
?>
<div class="jsFormContainer">
	<form id="formFeedback">
		<div class="alert alert-danger jsErrorMessage" role="alert" style="display: none;"></div>
		<div class="alert alert-success jsFormSuccess" role="alert" style="display: none;"><?=Loc::getMessage('SUCCESS_MESSAGE')?></div>
	    <?php foreach ($arResult['FIELDS'] as $fieldName => $field) { ?>
		    <?php
			$name = $field['IS_REQUIRED'] ? '*' : '';
		    $name .= $field['NAME'];
			$name .= ':';
		    ?>
			<div class="form-group mb-3">
				<label for="<?=$fieldName?>"><?=$name?>
					<input type="text" class="form-control" name="<?=$fieldName?>">
				</label>
			</div>
	    <?php } ?>
		<input type="hidden" name="HIGHLOADBLOCK_ID" value="<?=$arParams['HL_BLOCK_ID']?>">

		<button type="submit" class="btn btn-secondary"><?=Loc::getMessage('BUTTON_SUBMIT')?></button>
	</form>
</div>
