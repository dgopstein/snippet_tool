<!DOCTYPE html>
<html>
<head>
<title>Survey</title>
<link rel = "stylesheet"
  type = "text/css"
  href = "home.css" /> 
</head>
<body>
<?php  
	class MyDB extends SQLite3
	{
    	function __construct()
    	{
      	  	$this->open('confusion.db');
    	}
	}
	$id=filter_input(INPUT_POST, "ID");
  	$count=filter_input(INPUT_POST,"COUNT");
	##########################################

	$db=new MyDB();
    $stmt = $db->prepare('SELECT Correct,Duration FROM usercode WHERE UserID=:id');
    $stmt->bindValue(':id', $id);
    $results = $stmt->execute();
    $total=0;
    $cc=0;//correct count
    while ($row = $results->fetchArray()) {
          $total=$total+$row['Duration'];
          if($row['Correct']=="T") $cc=$cc+1;
    }
    $score=($cc*100)/84;
    $score=intval($score);
    $stmt = $db->prepare('UPDATE user SET Score=:S, Duration=:D WHERE ID=:id');
    $stmt->bindValue(':S', $score);
    $stmt->bindValue(':D', $total);
    $stmt->bindValue(':id', $id);
    $results = $stmt->execute();
    $db->close();
?>
<script type="text/javascript">
        window.location="https://pennstate.qualtrics.com/SE/?SID=SV_5jrp4w2exvfVpD7";
</script>
</body>
</html>
