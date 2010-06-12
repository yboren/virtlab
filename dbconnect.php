<?
    include("setting.php");
    $conn = mysql_connect($dbhost, $dbuser, $dbpass);
    if(!$conn) die("mysql error:".mysql_error());
    mysql_select_db($dbname, $conn);
?>
