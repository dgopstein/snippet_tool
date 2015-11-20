<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Quiz</title>
<link rel = "stylesheet"
      type = "text/css"
      href = "home.css" /> 
<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>

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
$start=filter_input(INPUT_GET, "s");
$id=filter_input(INPUT_POST, "ID");
$count=filter_input(INPUT_POST,"COUNT");
if($start=="1"){
	$Username=filter_input(INPUT_POST, "ID");
	##########################################
	//Initial Question Sets
	$set=array();
  	$tags=array();
  	$N=4;
  	$tagsN=array();
  	$questions=array();
    $db = new MyDB();
    $results=$db->query('SELECT ID, Amount FROM Tag');
    while ($row = $results->fetchArray()) {
      array_push($tags, $row['ID']);
      if ($row['Amount'] >= 5) {
      	array_push($tagsN, 5);
      }
      else{
      	array_push($tagsN, $row['Amount']);
      }  
  	}
  	$size=$N*count($tags);
  	while(count($set) < $size){
  		for($i=0;$i<count($tagsN);$i++){
  			if($tagsN[$i]==0){
  				$tags[$i]='x';
  			}
  			elseif($tagsN[$i]==1){
  				$tags[$i]='y';
  			}
  			elseif ($tagsN[$i] <= -10) {
  				$tagsN[$i] = 0 - $tagsN[$i] / 10;
  				$tags[$i]=$i+1;
  			}
  			elseif ($tagsN[$i] < 0) {
  				$tagsN[$i] = $tagsN[$i] * 10;
 				$tags[$i]='x';
  			}
  		}
  		$pickTags = array();
  		for($i=0;$i<count($tags);$i++){
  			if($tags[$i] !='x'){
  				if ($tags[$i] != 'y') {
  					array_push($pickTags, $tags[$i]);
  				}
  				array_push($pickTags, $i+1);
  			}
  		}
	  	$tmpTag=$pickTags[rand(0,count($pickTags)-1)];
	  	$tagsN[$tmpTag-1] = 0 - ($tagsN[$tmpTag-1] - 1);
	  	$stmt = $db->prepare('SELECT CodeID FROM CodeTags WHERE TagID=:id');
	    $stmt->bindValue(':id', $tmpTag);
	    $results = $stmt->execute();
		while ($row = $results->fetchArray()) {
		    array_push($questions, $row['CodeID']);
		}
	   	shuffle($questions);
	   	for ($i=0;$i<count($questions);$i++){
	   		$found = 0;
	   		for($j=0;$j<count($set);$j++){
	   			if($set[$j]==$questions[$i]){
	   				$found=1;
	   				break;
	   			}
	   		}
	   		if ($found == 0){
	   			array_push($set, $questions[$i]);
	   			break;
	   		}
	   	}
	    $questions=array();
	}
	##########################################
	//Add user to db
  	$count=0;
  	$results=$db->query('SELECT ID FROM User');
  	$id=1;
  	while($row=$results->fetchArray()) $id++;
  	$stmt = $db->prepare('INSERT INTO User (ID) VALUES (:id)');
  	$stmt->bindValue(':id', $id);
  	$results = $stmt->execute();
  	$db->close();  
  	##########################################
  	//Ceate User Document
  	//Hard Coding Amount of Questions --- 36
	$xml = fopen("User$id.xml", "w");
	$txt="<?xml version='1.0' standalone='yes'?>\n<Users>\n<User>\n<ID>$Username</ID>\n";
	for( $i=1; $i<=count($set);$i++){
		$QuestionNumber=$set[$i-1];
		$txt .= "<q$i>$QuestionNumber</q$i>\n";
	}
	$txt .= "</User>\n</Users>";
	fwrite($xml, $txt);
	fclose($xml);
	##########################################
	$count++;
}
else{ //grade last question
	$ans=filter_input(INPUT_POST,"ANS");
	$ans = trim(preg_replace('!\s+!', ' ', $ans));
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
		$count++;
}

					##########################################
					//Progress Bar
					$percentage=round(($count-1)/40*100);
					print <<<HERE
					 <div align="center">
					 	<div align="left" id="progressBar" class="default"><div id="p$percentage">$percentage%</div></div> 
					 </div>
HERE;
					##########################################
					// Print Question
					 	$xml = simplexml_load_file("User$id.xml");
					 	$QuesitonTag="q".$count;
						$QuestionNumber = $xml->User->$QuesitonTag;
						$db = new MyDB();
						$stmt=$db->prepare('SELECT Code FROM Code WHERE ID=:id');
						$stmt->bindValue(':id', $QuestionNumber);
						$results = $stmt->execute();  
						$row=$results->fetchArray();
						$quizBody=$row['Code'];
						if ($count<40){
							print <<<HERE
									<form action = "takeQuiz.php"
								 	method = "post">
HERE;
						}
						else{
							print <<<HERE
									<form action = "survey.php"
				  					method = "post">
HERE;
						}
						print <<<HERE
						<div class="home">
						<fieldset>
						<div class="group">
						<p align="center"><b>Question $count</b></p>
						<div class="inline">
						<pre class="prettyprint" style="border:0px">
						<p class="quizBody">$quizBody</p>
						</pre> 
						</div> 
						<p align="center">
						<label>Answer</label>
						<input type = "text"
						       name = "ANS"  autofocus autocomplete="off"/>
						       </p>
						<button type = "submit" onclick="buttonClick()">
						  Next
						</button>
						<input type = "hidden"
						       name = "ID" value=$id />
						<input type = "hidden"
						       name = "COUNT" value=$count />
						</div>
						</fieldset>
						</div>
						</form>
HERE;
					  	$db->close();
 
?>
<script type="text/javascript">
    var startTime = new Date();        
    function buttonClick()      
    {
        var endTime = new Date();        
        var timeSpent = (endTime - startTime);        
        var xmlhttp;        
        var id = "<?php Print($id); ?>";
        xmlhttp = new XMLHttpRequest();       
        var url = "time.php?time="+timeSpent+"&user="+id;        
        xmlhttp.open("GET",url,false);        
        xmlhttp.send(null);        
    }
</script>
</body>
</html>

