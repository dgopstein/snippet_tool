<!DOCTYPE html>
<html>
<head>
	<title>End</title>
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
	$ans1=filter_input(INPUT_POST, "ANS1");
  $ans2=filter_input(INPUT_POST, "ANS2");
  $ans3=filter_input(INPUT_POST, "ANS3");
  $ans4=filter_input(INPUT_POST, "ANS4");
  $ans5=filter_input(INPUT_POST, "ANS5");
  $ans6=filter_input(INPUT_POST, "ANS6");
  $ans7=filter_input(INPUT_POST, "ANS7");
  $ans8=filter_input(INPUT_POST, "ANS8");
  $db = new MyDB();
  $stmt = $db->prepare('INSERT INTO questionnaire (UserID,Q1,Q2,Q3,Q4,Q5,Q6,Q7,Q8) VALUES ( :id,:Ans1,:Ans2,:Ans3,:Ans4,:Ans5,:Ans6,:Ans7,:Ans8)');
  $stmt->bindValue(':id', $id);
  $stmt->bindValue(':Ans1', $ans1);
  $stmt->bindValue(':Ans2', $ans2);
  $stmt->bindValue(':Ans3', $ans3);
  $stmt->bindValue(':Ans4', $ans4);
  $stmt->bindValue(':Ans5', $ans5);
  $stmt->bindValue(':Ans6', $ans6);
  $stmt->bindValue(':Ans7', $ans7);
  $stmt->bindValue(':Ans8', $ans8);
  $results = $stmt->execute();
  $db->close();
  print <<<HERE
  <div class="home">
  <fieldset>
    <h4 align="center" style="color:red">Thank you again for taking the time to participate in this research study.</h4>
  </fieldset>
  </div>
HERE;
?>
</body>
</html>
