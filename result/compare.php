<!DOCTYPE html>
<html>
<head>
	<title>Compare</title>
</head>
<body>
<a href="questions.php">Questions -></a> <br />
<a href="admin.php">Results -></a><br />
<br /> 
<?php  
	class MyDB extends SQLite3
	{
    	function __construct()
    	{
      	  	$this->open('../confusion.db');
    	}
	}
  $db = new MyDB();
  $stmt=$db->prepare('SELECT ID,Pair FROM Code WHERE Type=:type ORDER BY ID ASC');
  $stmt->bindValue(':type',"Confusing");
  $results = $stmt->execute();  
  $output= <<<HERE
    <table style="width:70%">
        <tr>
          <td><b>Confusing Code ID</b></td> 
          <td><b>Non-confusing Code ID</b></td>
          <td><b>Confusing Correct Rate</b></td>
          <td><b>Non-confusing Correct Rate</b></td>
          <td><b>Confusing Average Duration</b></td>
          <td><b>Non-confusing Average Duration</b></td>
        </tr>
HERE;
  while($row=$results->fetchArray()){
	          $id = $row['ID'];
	          $pair = $row['Pair'];
	          $stmt2 = $db->prepare('SELECT Correct,Duration FROM usercode WHERE CodeID=:id');
	    	  $stmt2->bindValue(':id', $id);
	    	  $results2 = $stmt2->execute();
	    	  $correct = 0;
	    	  $totalTime = 0;
	    	  $count = 0;
	    	  while($row2=$results2->fetchArray()){
	    	  	$TF=$row2['Correct'];
	    	  	$totalTime=$totalTime+$row2['Duration'];
	    	  	$count++;
	    	  	if($TF=='T') $correct++;
	    	  }
	    	  if($count==0) {
	    	  	$rate="N/F";
	    	  	$time="N/F";
	    	  }
	    	  else {
	    	  	$rate=($correct*100/$count)."%";
	    	  	$time=$totalTime/$count;
	    	  }
	    	  if($pair == -1){
	    	  	$pairID="N/F";
	    	  	$pairRate="N/F";
	    	  	$pairTime="N/F";
	    	  }
	    	  else{
	    	  	  $pairID=$pair;
	    	  	  $stmt2 = $db->prepare('SELECT Correct,Duration FROM usercode WHERE CodeID=:id');
	    	  	  $stmt2->bindValue(':id', $pairID);
	    	  	  $results2 = $stmt2->execute();
	    	  	  $correct = 0;
		    	  $totalTime = 0;
		    	  $count = 0;
		    	  while($row2=$results2->fetchArray()){
		    	  	$TF=$row2['Correct'];
		    	  	$totalTime=$totalTime+$row2['Duration'];
		    	  	$count++;
		    	  	if($TF=='T') $correct++;
		    	  }
		    	  if($count==0) {
		    	  	$pairRate="N/F";
		    	  	$pairTime="N/F";
		    	  }
		    	  else {
		    	  	$pairRate=($correct*100/$count)."%";
		    	  	$pairTime=$totalTime/$count;
		    	  }
	    	  }
	   $output.=<<<HERE
          <tr>
            <td><a href="questions.php#$id">$id</a></td> 
HERE;
		if($pairID!="N/F"){
			$output.=<<<HERE
            <td><a href="questions.php#$pairID">$pairID</a></td>
HERE;
		}
		else{
			$output.=<<<HERE
            <td>$pairID</td>
HERE;
		}
      $output.=<<<HERE
            <td>$rate</td>
            <td>$pairRate</td>
            <td>$time</td>
            <td>$pairTime</td>
          </tr>
HERE;
  }				
  $output.= "</table><br>";
  print $output;
              $db->close();
?>
</body>
</html>
