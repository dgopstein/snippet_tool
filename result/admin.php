<!DOCTYPE html>

<html>

<head>

	<title>Results</title>

</head>

<body>
<a href="compare.php">Confusing and Non-confusing Comparison -></a> <br />
<a href="questions.php">Questions -></a><br />
<a href="backup.php">Backup Database -></a><br />
<a href="reset.php">Reset Database -></a> 
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

      $lastLogin=$row['LastLogin'];

      $score=$row['Score'];

      $duration=$row['Duration'];

      $output= <<<HERE

      <table style="width:45%">

        <tr>

          <td><h3>User ID: $ID</h3></td>

          <td><h3>Total Score: $score</h3></td> 

          <td><h3>Total Time Spent: $duration</h3></td>

          <td><h3>Finished At $lastLogin</h3></td>

        </tr>

      </table> 

      <table style="width:50%">

        <tr>

          <td><b>Code ID</b></td> 

          <td><b>Time Spent</b></td>

          <td><b>User Answer</b></td>

          <td><b>T/F</b></td>

          <td><b>Letters</b></td>

          <td><b>User Letters</b></td>

        </tr>

HERE;



      $stmt = $db->prepare('SELECT CodeID,Duration,Correct,Answer,Letters,LetterAns FROM usercode WHERE UserID=:id');

      $stmt->bindValue(':id', $id);

      $results = $stmt->execute();

      while($row=$results->fetchArray()){

          $codeID=$row['CodeID'];

          $duration=$row['Duration'];

          $correct=$row['Correct'];

          $answer=$row['Answer'];

          $letters=$row['Letters'];

          $letterAns=$row['LetterAns'];

          $output.=<<<HERE

          <tr>

            <td>$codeID</td> 

            <td>$duration</td>

            <td>$answer</td>

            <td>$correct</td>

            <td>$letters</td>

            <td>$letterAns</td>

          </tr>

HERE;

      }

      $output.= "</table><br>";

      $db->close();

      return $output;

  }



  $db = new MyDB();

  //Get User List

  $stmt = $db->prepare('SELECT ID,LastLogin,Score,Duration FROM User');

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

