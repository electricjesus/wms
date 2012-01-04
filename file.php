<?php
header("Content-Type:application/vnd.ms-excel");
header('Content-Disposition: attachment; filename="' . $name .'"');
readfile("files/$name");
?>