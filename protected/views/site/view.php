<?php
if (isset ($f))
{
	header("Content-type:".$f);
	echo $im;
	$im->destroy();
}
?>
