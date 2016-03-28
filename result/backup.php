<!DOCTYPE html>
<html>
<head>
        <title></title>
</head>
<body>
<h2>Database Backuped</h2>
<a href="admin.php">Back -></a>
<?php
	$plus2hour = time() + (2 * 60 * 60);
	$file="../backup/db/confusion-".date('Y-m-d-h-i',$plus2hour).".db";
	copy('../confusion.db', $file);
?>
</body>
</html>

