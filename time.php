<?php 
	class MyDB extends SQLite3
	{
	    function __construct()
	    {
	        $this->open('confusion.db');
	    }
	}
	$time = filter_input(INPUT_POST, "time");
	$timeTotal = filter_input(INPUT_POST, "ttime");
	$ans = filter_input(INPUT_POST, "ans");
	$id = filter_input(INPUT_POST, "ID");
	$count = filter_input(INPUT_POST, "COUNT");

	$ans = trim(preg_replace('!\s+!', ' ', $ans));
	$xml = simplexml_load_file("User$id.xml");
	$QuesitonTag="q".$count;
	$codeID = $xml->User->$QuesitonTag;

	$db = new MyDB();
	$stmt = $db->prepare('SELECT Answer FROM Code WHERE ID=:id');
	$stmt->bindValue(':id', $codeID);
	$results = $stmt->execute();

	$row=$results->fetchArray();
	$stmt = $db->prepare('INSERT INTO usercode (UserID,CodeID,Duration,TimeTotal,Correct,Answer) VALUES ( :id,:Cid,:diff,:tt,:tf,:ans)');
	$stmt->bindValue(':id', $id);
	$stmt->bindValue(':Cid', $codeID);
	$stmt->bindValue(':diff', $time);
	$stmt->bindValue(':tt', $timeTotal);
	$stmt->bindValue(':ans', $ans); 
	if($row['Answer']==$ans){
	  $stmt->bindValue(':tf', "T");
	}
	else{
	  $stmt->bindValue(':tf', "F");
	}
	$results = $stmt->execute();
	$db->close();
?> 