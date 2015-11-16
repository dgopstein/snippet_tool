<!DOCTYPE html>
<html>
<head>
	<title></title>
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
      	  	$this->open('confustion.db');
    	}
	}
	$id=filter_input(INPUT_POST, "ID");
  	$count=filter_input(INPUT_POST,"COUNT");
	##########################################
	$ans=filter_input(INPUT_POST,"ANS");
    $xml = simplexml_load_file("User$id.xml");
    $QuesitonTag="q".$count;
    $codeID = $xml->User->$QuesitonTag;
    $timeXml = simplexml_load_file("tmp$id.xml");
    $t = "time";
    $time = $timeXml->Timer->$t;
    $db = new MyDB();
    $stmt = $db->prepare('SELECT Answer FROM Code WHERE ID=:id');
    $stmt->bindValue(':id', $codeID);
    $results = $stmt->execute();
    $row=$results->fetchArray();
    $stmt = $db->prepare('INSERT INTO usercode (UserID,CodeID,Duration,Correct,Answer) VALUES ( :id,:Cid,:diff,:tf,:ans)');
    $stmt->bindValue(':id', $id);
    $stmt->bindValue(':Cid', $codeID);
    $stmt->bindValue(':diff', $time);
    $stmt->bindValue(':ans', $ans);
    if($row['Answer']==$ans){
      $stmt->bindValue(':tf', "T");
    }
    else{
      $stmt->bindValue(':tf', "F");
    }
    $results = $stmt->execute();
    $db->close();
	##########################################
	// Proceed to Survey
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
    $score=($cc*100)/36;
    $score=intval($score);
    $stmt = $db->prepare('UPDATE user SET Score=:S, Duration=:D WHERE ID=:id');
    $stmt->bindValue(':S', $score);
    $stmt->bindValue(':D', $total);
    $stmt->bindValue(':id', $id);
    $results = $stmt->execute();
    $db->close();
    print <<<HERE
 	<div align="center">
 		<div align="left" id="progressBar" class="default"><div id="p100">100%</div></div> 
 	</div>
HERE;
    print <<<HERE
    <form action = "end.php"
      method = "post">
    <div class="home">
    <fieldset>
    <div class="group">
    <h4 align="center" style="color:red">You have finished all quizs!<br />Thank you! Here are some servey questions!</h4>
    <p align="left">1. What is your age (as of today)?</p>
    <input type = "text" name = "ANS1" autofocus autocomplete="off"/>
    <p align="left">2. What is your gender? </p>
   	<input type="radio" name="ANS2" value="male" checked>Male<br><input type="radio" name="ANS2" value="female">Female
    <p align="left">3. What is your highest education level? (If you are a student, report the degree you are pursuing.) </p>
    <select name="ANS3">
    <option value="Associate's Degree">Associate's Degree</option>
    <option value="Bachelor's Degree">Bachelor's Degree</option>
    <option value="Master's Degree">Master's Degree</option>
    <option value="Doctoral Degree">Doctoral Degree</option>
    <option value="Professional Degree">Professional Degree</option>
  	</select>
    <p align="left">4. When did you first learn C/C++? (Please report month and year such as 11/2008. If you do not member the month, then simply write the year.) </p>
    <input type = "text" name = "ANS4"  autocomplete="off"/>
    <p align="left">5. If you first learn C/C++ formally (at school or in a training), please tell us where. </p>
    <input type = "text" name = "ANS5"  autocomplete="off"/>
    <p align="left">6. If you first learn C/C++ informally (self-taught or using online tutorial), please tell us how. </p>
    <input type = "text" name = "ANS6"  autocomplete="off"/>
    <p align="left">7. When was the last time you use C/C++? (Please report month and year such as 8/2015. If you do not remember the month, then simply write the year.) </p>
    <input type = "text" name = "ANS7"  autocomplete="off"/>
    <p align="left">8. If C/C++ is not your dominate programming language, please tell us what is your dominate programming language, for example Java, PHP, Python, etc. </p>
    <input type = "text" name = "ANS8"  autocomplete="off"/>
    <button type = "submit">Next</button>
    <input type = "hidden" name = "ID" value=$id />
    </div>
    </fieldset>
    </div>
    </form>
HERE;
?>
</body>
</html>
