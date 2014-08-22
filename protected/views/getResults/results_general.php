<?php
$resultSet = $group["data"]["retval"];
$cantidad = $group["data"]["keys"];
$results = Image::writeImageData($resultSet);
$conditions = $group['keys'];
//foreach ($results as $result) {
//	foreach ($result as $key => $value) {
//		echo $value;
//	}
//}
?>
<table id="box-table-a" class="tablesorter">
	<?php
	$i = 0;
	$cols = 0;
	$beginIndex = (($currentPage * Idc::PAGE_SIZE) - Idc::PAGE_SIZE);
	$endIndex = ($pages == $currentPage) ? $beginIndex + ($cantidad - $beginIndex) : $beginIndex + Idc::PAGE_SIZE;
	for ($x = $beginIndex; $x < $endIndex; $x++) {
		$document = DocTypes::model()->find('doc_type_desc = :doc', array(':doc' => $resultSet[$x]["docType"]));
		if ($i == 0) {
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
				<tr>
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
<!--	<tfoot>
		<tr>
			<td class="table-footer" colspan=1" style="border-right:0;">
				< ?php echo ($currentPage == 1)?'':CHtml::link('Anterior','#',array('onClick'=>'SearchGralDocs('.($currentPage-1).','.$document->doc_type_id.')')).' ';?>
			</td>
			<td class="table-footer" style="border-right:0;border-left:0;text-align: center;" colspan="< ?php echo (Yii::app()->user->isAdmin) ? $cols : $cols - 1; ?>">
				< ?php
				$pager = ceil($pages/10);
//				$index = 10;
				if ($currentPage >= 10/$pager)
				{
					$lastPage = ($currentPage + 5>$pages)?$pages:$currentPage + 5;
					$x = $lastPage -9;
				}
				else
				{
					$lastPage = ($pages < 10)?$pages:10;
					$x=1;
				}
//				$p = ($pages<$index)?$pages:10;

				for($x;$x<($lastPage+1);$x++)
				{
					if($currentPage == $x)
					{
						echo '<b>'.$x.'</b> ';
					}
					else
					{
						echo CHtml::link($x,'#',array('onClick'=>'SearchGralDocs('.$x.','.$document->doc_type_id.')')).' ';
					}
				}
				?>
			</td>
			<td class="table-footer" colspan=1" style="border-left:0;">
				< ?php echo ($currentPage==$pages)?'':CHtml::link('Siguiente','#',array('onClick'=>'SearchGralDocs('.($currentPage+1).','.$document->doc_type_id.')')).' ';?>
			</td>
		</tr>
	</tfoot>-->
</table>
<div id="pager" class="pager" align="center">
	<form>
		<img src="img/first.png" class="first"/>
		<img src="img/prev.png" class="prev"/>
		<span class="pagedisplay"></span>   
		<img src="img/next.png" class="next"/>
		<img src="img/last.png" class="last"/>
	</form>
</div>
