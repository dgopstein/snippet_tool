<!DOCTYPE html>
<html>
<head>
	<title>Survey Results</title>
</head>
<body>
<a href="compare.php">Confusing and Non-confusing Comparison -></a> <br />
<a href="questions.php">Questions -></a><br />
<a href="admin.php">Results -></a><br />
<?php  
	class MyDB extends SQLite3
	{
    	function __construct()
    	{
      	  	$this->open('../confusion.db');
    	}
	}
  function printUser($row){
      $db = new MyDB();
      $id=$row['ID'];
      $xml = simplexml_load_file("../User$id.xml");
      $ID = $xml->User->ID;
      $output= <<<HERE
      <h3>User ID: $ID</h3>
HERE;
      $stmt = $db->prepare('SELECT Q1,Q2,Q3,Q4,Q5,Q6,Q7,Q8 FROM questionnaire WHERE UserID=:id');
      $stmt->bindValue(':id', $id);
      $results = $stmt->execute();
      while($row=$results->fetchArray()){
          $q1=$row['Q1'];
          $q2=$row['Q2'];
          $q3=$row['Q3'];
          $q4=$row['Q4'];
          $q5=$row['Q5'];
          $q6=$row['Q6'];
          $q7=$row['Q7'];
          $q8=$row['Q8'];
          $output.=<<<HERE
          <p><b>Q1</b>          $q1</p>
          <p><b>Q2</b>          $q2</p>
          <p><b>Q3</b>          $q3</p>
          <p><b>Q4</b>          $q4</p>
          <p><b>Q5</b>          $q5</p>
          <p><b>Q6</b>          $q6</p>
          <p><b>Q7</b>          $q7</p>
          <p><b>Q8</b>          $q8</p>
HERE;
      }
      $output.= "<br>";
      $db->close();
      return $output;
  }
  $db = new MyDB();
  print<<<HERE
    <h3>Survey Questions</h3>
    <p align="left">1. What is your age (as of today)?</p>
    <p align="left">2. What is your gender? </p>
    <p align="left">3. What is your highest education level? (If you are a student, report the degree you are pursuing.) </p>
    <p align="left">4. When did you first learn C/C++? (Please report month and year, such as 11/2008. If you do not member the month, then simply write the year.) </p>
    <p align="left">5. If you first learn C/C++ formally (at school or in a training), please let us know where. </p>
    <p align="left">6. If you first learn C/C++ informally (self-taught or using online tutorial), please tell us what method you used. </p>
    <p align="left">7. When was the last time you used C/C++? (Please report month and year, such as 8/2015. If you do not remember the month, then simply write the year.) </p>
    <p align="left">8. If C/C++ is not your dominate programming language, please tell us what is your dominate programming language, for example Java, PHP, Python, etc. </p>
    <br />
HERE;
  //Get User List
  $stmt = $db->prepare('SELECT ID FROM User');
  $results = $stmt->execute();
  if ($row=$results->fetchArray()){
      print printUser($row);
      while($row=$results->fetchArray()){
          print printUser($row);
      }
  }
  else {
    echo "<h1>No entries at this point.</h1>";
  }
  $db->close();
?>
</body>
</html>



