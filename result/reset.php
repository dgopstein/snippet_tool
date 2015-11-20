<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<h2>Database Resetted</h2>
<a href="admin.php">Back -></a>
<?php
	$plus2hour = time() + (2 * 60 * 60);
        $file="../backup/db/tmp/confusion-".date('Y-m-d-h-i-s',$plus2hour).".db";
        copy('../confusion.db', $file);
	copy('../db/confusion.db', '../confusion.db');
?>
</body>
</html>
