<?php
$resultSet = $group['data']["retval"];
$cantidad = $group['data']["keys"];
$conditions = $group['keys'];
$results = Image::writeImageData($resultSet);
foreach ($results as $result) {
	foreach ($result as $key => $value) {
		echo $value;
	}
}
?>

<table id="box-table-a" class="tablesorter">
	<?php
	$i = 0;
	$cols = 0;
	$beginIndex = (($currentPage * Idc::PAGE_SIZE) - Idc::PAGE_SIZE);
	$endIndex = ($pages == $currentPage) ? $beginIndex + ($cantidad - $beginIndex) : $beginIndex + Idc::PAGE_SIZE;

	for ($x = $beginIndex; $x < $endIndex; $x++) {
		if ($i == 0) {
			?>
			<thead>
				<tr>
					<?php
		if (Yii::app()->user->isAdmin) {
			echo '<th scope="col" class="{sorter: false}" style="width:10%">Visible</th>';
		}
		echo '<th scope="col"class="{sorter: false}">Acciones</th>';
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
			}
			if ($resultSet[$x] != null) {
				$setConditions = $conditions;

				if (isset($resultSet[$x]['c1'])) {
					$condition = new Condition('c1', '==', $resultSet[$x]['c1']);
					array_push($setConditions, $condition);
				}

				if (isset($resultSet[$x]['c2'])) {
					$condition = new Condition('c2', '==', $resultSet[$x]['c2']);
					array_push($setConditions, $condition);
				}

				if (isset($resultSet[$x]['c3'])) {
					$condition = new Condition('c3', '==', $resultSet[$x]['c3']);
					array_push($setConditions, $condition);
				}

				if (isset($resultSet[$x]['c4'])) {
					$condition = new Condition('c4', '==', $resultSet[$x]['c4']);
					array_push($setConditions, $condition);
				}

				foreach ($fields as $field) {
					$fieldc = $field->prefix . $field->name;
					$value = $resultSet[$x][$field->prefix . $field->name];
					$condition = new Condition($fieldc, '==', $value);
					array_push($setConditions, $condition);
				}
				$jsonEcriteria = json_encode($setConditions);
				$jsonfields = json_encode($fields);
				echo '<div id="fields_' . key($results[$x]) . '" style="display:none">' . $jsonfields . '</div>';
				echo '<div id="query_' . key($results[$x]) . '" style="display:none">' . $jsonEcriteria . '</div>';
				?>

				<tr>
					<?php
					$editCarat = '';
					if (Yii::app()->user->isAdmin) {
						$ver = array_keys($resultSet[$x]["images"]);
						echo '<td>' . CHtml::checkBox('check', $resultSet[$x]["images"][$ver[3]], array('id' => 'check_set_' . key($results[$x]),
						  'onClick' => 'js:toogleCaratVisibility("' . key($results[$x]) . '")')) . '</td>';
						$editCarat = ($groupBy == 'carat') ?
						    CHtml::Link(CHtml::image('/images/edit_icon.png', 'Editar Carátula'), '#', array('style' => 'text-decoration:none;margin-right:10px',
						      'onClick' => 'js:openCartaForm("' . key($results[$x]) . '");return false;')) : '';
					}

					echo '<td>' . CHtml::Link(CHtml::image('/images/zip_icon.png', 'Exportar a ZIP'), '#', array('style' => 'text-decoration:none;',
					  'onClick' => 'js:exportZIP("' . key($results[$x]) . '");return false;'))
					. CHtml::Link(CHtml::image('/images/pdf_icon.png', 'Exportar a PDF'), '#', array('style' => 'text-decoration:none;',
					  'onClick' => 'js:exportPDF("' . key($results[$x]) . '");return false;'))
					. $editCarat . '</td>';

					echo '<td>' . CHtml::Link($resultSet[$x]["index"], '#', array('style' => 'text-decoration:none;',
					  'onClick' => 'js:getImageInfo("' . key($results[$x]) . '","' . $x . '");return false;')) . '</td>';

					foreach ($fields as $field) {
						echo '<td>' . CHtml::Link($resultSet[$x][$field->prefix . $field->name], '#', array('style' => 'text-decoration:none;', 'id' => $field->prefix . $field->name . '_' . key($results[$x]),
						  'onClick' => 'js:getImageInfo("' . key($results[$x]) . '","' . $x . '");return false;')) . '</td>';
					}
					?>
				</tr>

				<tr  class="tablesorter-childRow">
							<td height="100px" id="row<?php echo key($results[$x]) ?>" style="display: none;padding:0;" colspan="
							    <?php echo (Yii::app()->user->isAdmin) ? $cols + 3 : $cols + 2 ?>">
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
	<tfoot>
	</tfoot>
</table>

<?php include 'pager.php'?>

<div id="carat_form" class="hidden"></div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#carat_form").dialog({
			autoOpen: false,
			modal: true,
			resizable: false,
			title: "Modificar Carátula",
			width: 500
		});
	});
	function openCartaForm(id) {
		$("#carat_form").dialog("option", "buttons", [
			{text: "Actualizar", click: function() {
					modCarat(id);
				}},
			{text: "Cancelar", click: function() {
					$(this).dialog("close");
				}}
		]);
		getCaratData(id);
	}

	function getCaratData(id) {
		var query = $("#query_" + id).html();
		$.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/Caratmeta/getCaratData",
			context: document.body,
			type: "POST",
			data: "conditions=" + query,
			dataType: "json",
			success: function(data) {
				$("#carat_form").html(data.html);
				$("#carat_form").dialog("open");
			}
		});
	}

	function modCarat(id) {
		var inputs = $('#new_carat_form :input');

		var query = $("#query_" + id).html();
		var newData = {};
		$.each(inputs, function( ) {
			$(newData).attr(this.id, $(this).val());

		});
		$.ajax({url: "<?php echo Yii::app()->request->hostinfo ?>/Caratmeta/ModCaratData",
			context: document.body,
			type: "POST",
			data: {conditions: query, new_data: newData},
			dataType: "json",
			success: function(data) {
				if (data.success) {
					$("#query_" + id).html(data.query);
					updateGridValues(id, newData, "carat_form");
				}
				else {
					alert(data.message);
					$("#carat_form").dialog("close");
				}
			}
		});
	}

	function updateGridValues(id, newData, formName) {
		$.each(newData, function(index, value) {
			var _id = index + "_" + id;
			$("#" + _id).html(value);
		});
		$("#" + formName).dialog("close");
	}
</script>