<!DOCTYPE html>
<html>
<head>
	<title>Questions</title>
</head>
<body>
<a href="compare.php">Confusing and Non-confusing Comparison -></a> <br />
<a href="admin.php">Results -></a> 
<?php  
	class MyDB extends SQLite3
	{
    	function __construct()
    	{
      	  	$this->open('../confusion.db');
    	}
	}
  $db = new MyDB();
  $stmt=$db->prepare('SELECT ID,Code,Answer,Type FROM Code');
  $results = $stmt->execute();  
  while($row=$results->fetchArray()){
	          $id = $row['ID'];
	          $quizBody=$row['Code'];
	          $Ans=$row['Answer'];
	          $Type=$row['Type'];
	          $stmt2 = $db->prepare('SELECT Correct FROM usercode WHERE CodeID=:id');
	    	  $stmt2->bindValue(':id', $id);
	    	  $results2 = $stmt2->execute();
	    	  $correct = 0;
	    	  $count = 0;
	    	  while($row2=$results2->fetchArray()){
	    	  	$TF=$row2['Correct'];
	    	  	$count++;
	    	  	if($TF=='T') $correct++;
	    	  }
	    	  if($count==0) $rate="N/F";
	    	  else $rate=($correct*100/$count)."%";
	    	  $stmt3 = $db->prepare('SELECT TagID FROM codetags WHERE CodeID=:id');
	    	  $stmt3->bindValue(':id', $id);
	    	  $results3 = $stmt3->execute();
	    	  $row3=$results3->fetchArray();
	    	  $stmt4 = $db->prepare('SELECT Tag FROM tag WHERE ID=:id');
	    	  $stmt4->bindValue(':id', $row3['TagID']);
	    	  $results4 = $stmt4->execute();
	    	  $row4=$results4->fetchArray();
	    	  $atom=$row4['Tag'];
              print <<<HERE
              <p id="$id"><b>Question $id</b></p>
              <div>
              <pre style="border:0px">
              <p>$quizBody<br/><br/>Correct Answer: $Ans<br />Question Type: $Type<br />Correct Rate: $rate<br />Atom: $atom</p>
              </pre> 
              </div>              
HERE;
  }
              $db->close();
?>
</body>
</html>
