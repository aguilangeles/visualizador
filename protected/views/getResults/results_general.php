<?php
$resultSet = $group["data"]["retval"];
$cantidad = $group["data"]["keys"];
$results = Image::writeImageData($resultSet);
$conditions = $group['keys'];
//$docs = explode(',', $_POST['docs']);
$index = $namesdoc;

//}
//solo quien sepa hacer esto de una manera mas prolija puede juzgarme.
//$total= count($docs);
/////////////////////////////////////Dios me perdone.
?>
<table id="box-table-<?php echo $index ?>"class="tablesorter">
	<?php
	$i = 0;
	$cols = 0;
	$beginIndex = (($currentPage * Idc::PAGE_SIZE) - Idc::PAGE_SIZE);
	$endIndex = ($pages == $currentPage) ? $beginIndex + ($cantidad - $beginIndex) : $beginIndex + Idc::PAGE_SIZE;

	for ($x = $beginIndex; $x < $endIndex; $x++) {
		$document = DocTypes::model()->find('doc_type_desc = :doc', array(':doc' => $resultSet[$x]["docType"]));
		if ($i == 0) {
//			$index++;
			?>
			<thead>
				<tr>
		<?php
		if (Yii::app()->user->isAdmin) {
			echo '<th scope="col" class="{sorter: false}">Visible</th>';
		}
		echo '<th scope="col" class="header">Imagenes</th>';
		foreach ($fields as $field) {
			echo '<th scope="col" class="header">' . $field->label . '</th>';
			$cols++;
		}
		?>
				</tr>
			</thead>
			<tbody>
		<?php
	}if ($resultSet[$x] != null) {

		$setConditions = $conditions;
		$condition = new Condition('docType', '==', $resultSet[$x]["docType"]);
		array_push($setConditions, $condition);

		foreach ($fields as $field) {
			$fieldc = $field->prefix . $field->name;
			$value = $resultSet[$x][$field->prefix . $field->name];
			$acondition = new Condition($fieldc, '==', $value);
			array_push($setConditions, $acondition);
		}
		$jsonEcriteria = json_encode($setConditions);
		$jsonfields = json_encode($fields);
		echo '<div id="fields_' . key($results[$x]) . '" style="display:none">' . $jsonfields . '</div>';
		echo '<div id="query_' . key($results[$x]) . '" style="display:none">' . $jsonEcriteria . '</div>';
		?>
				<tr>
				<?php
				if (Yii::app()->user->isAdmin) {
					$ver = array_keys($resultSet[$x]["images"]);
					echo '<td>' . CHtml::checkBox('check', $resultSet[$x]["images"][$ver[3]], array('id' => 'check_set_' . key($results[$x]),
					  'onClick' => 'js:toogleCaratVisibility("' . key($results[$x]) . '")')) . '</td>';
				}
				echo '<td>' . CHtml::Link($resultSet[$x]["index"], '#', array('style' => 'text-decoration:none;',
				  'onClick' => 'js:getImageInfo("' . key($results[$x]) . '","' . $x . '");return false;')) . '</td>';
				foreach ($document->Carats as $carat) {
					echo '<td>' . CHtml::Link($resultSet[$x]["CMETA_" . $carat->carat_meta_desc], '#', array('style' => 'text-decoration:none;',
					  'onClick' => 'js:getImageInfo("' . key($results[$x]) . '","' . $x . '");return false;')) . '</td>';
				}
				?>
				</tr>
				<tr class="tablesorter-childRow">
					<td height="100px" id="row<?php echo key($results[$x]) ?>" style="display: none;padding:0;" colspan="<?php echo (Yii::app()->user->isAdmin) ? $cols + 3 : $cols + 2 ?>">

					</td>
				</tr>
		<?php
		$i++;
	} else {
		break;
	}
}
?>
	</tbody>
	<tfoot class="table-footer">
		<tr>

		</tr>
	</tfoot>
</table>
<script type="text/javascript" >
	$(document).ready(function() {
		$('#box-table-<?php echo $index ?>childRow td').hide();
		$("#box-table-<?php echo $index ?>").tablesorter({
			widthFixed: false,
			sortReset: true,
			sortRestart: true
		}
		).tablesorterPager({
			container: '.pager'
			, ajaxUrl: null
			, customAjaxUrl: function(table, url) {
				return url;
			}
			, ajaxProcessing: function(ajax) {
				if (ajax && ajax.hasOwnProperty('data')) {
					// return [ "data", "total_rows" ]; 
					return [ajax.data, ajax.total_rows];
				}

			}
			, output: ' {page}/{totalPages}'
			, updateArrows: false
			, page: 0
			, size: 10
			, fixedHeight: false
			, savePages: false
			, storageKey: 'tablesorter-pager'
			, removeRows: false
			, positionFixed: false

		});
	});
</script>
<!--<div id="pager" class="pager" align="center">
	<form>
		<img src="img/first.png" class="first"/>
		<img src="img/prev.png" class="prev"/>
		<span class="pagedisplay"></span>   
		<img src="img/next.png" class="next"/>
		<img src="img/last.png" class="last"/>
	</form>
</div>-->
