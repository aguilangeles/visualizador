<?php
header("Content-type:".$f);
echo ($message == "")?$im:$message;

$im->destroy();
?>
