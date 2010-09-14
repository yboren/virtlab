<?php
	ob_start();
	echo "cool";
	ob_end_clean();
	header("Location:index.php");
?>