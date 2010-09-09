<?php
$headers = $_SERVER;
while(list($header, $value) = each($headers)){
    echo "<b>$header:</b>$value<br/>\n";
}
?>
