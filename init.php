<?php 
	class MyDB extends SQLite3
	{
	    function __construct()
	    {
	        $this->open('confusion.db');
	    }
	}
	$TABCOUNT = 21;
	$QUESTIONPERTAB = 4;
	$QUESTIONCOUNT = $TABCOUNT * $QUESTIONPERTAB;
	$DISTANCE = 12;

	function foo($TABCOUNT, $QUESTIONPERTAB, $QUESTIONCOUNT, $DISTANCE){

		$set = array();
		$count = 0;
		$tabCount = 21;
		$tabN = array_fill(0, $TABCOUNT, $QUESTIONPERTAB);
		$tab = array_fill(0, $TABCOUNT, 0);
		while (count($set)<$QUESTIONCOUNT){
			$tab_flag = 0;
			$failure = 0;
			$count = 0;
			$select = rand(0,$tabCount-1);
	//		echo "{select1: ".$select."}";
			while ($tab[$select] != 0 || $tabN[$select] == 0){
				$tabN_flag = 0;
				$tab_flag = 0;
				if ($count > 100){
					$failure == 1;
					break;
				}
				for ($i = 0; $i < count($tab); $i++) {
					if($tab[$i]==0 && $tabN[$i]>0) $tab_flag = 1;
				}
				if ($tab_flag == 0){
					$failure = 1;
					break;
				}
				$select = rand(0,$tabCount-1);
	//			echo "{select2: ".$select."}";
				$count++;
			}
			if ($failure == 1) break;
			array_push($set, $select+1);
			for ($i=0; $i < $tabCount; $i++) { 
				if($tab[$i] != 0) $tab[$i] --;
			}
			$tab[$select] = $DISTANCE;
			$tabN[$select]--;
		}
		return $set;
	}
	function test($set, $TABCOUNT, $DISTANCE){
		$flag = array_fill(0, $TABCOUNT, 0);
		foreach ($set as $num) {
			if ($flag[$num-1] != 0) return "FALSE";
			else $flag[$num-1] = $DISTANCE + 1;
			for ($i=0; $i < count($flag); $i++) { 
				if($flag[$i] != 0) $flag[$i] --;
			}
		}
		return "TRUE";
	}

	$Username=filter_input(INPUT_POST, "id");
	
	##########################################
	//Initial Question Sets
    $db = new MyDB();
	$questions = foo($TABCOUNT, $QUESTIONPERTAB, $QUESTIONCOUNT, $DISTANCE);
	while(count($questions)< $QUESTIONCOUNT || test($questions, $TABCOUNT, $DISTANCE)=="FALSE") $questions = foo($TABCOUNT, $QUESTIONPERTAB, $QUESTIONCOUNT, $DISTANCE);

	$set = array_fill(0, count($questions), 0);
	for ($i = 1; $i <= $TABCOUNT; $i++) {
		$update = 2;
		$stmt = $db->prepare('SELECT Round FROM tag WHERE ID=:id');
		$stmt->bindValue(':id', $i);
		$results = $stmt->execute();
		$row = $results->fetchArray();
		$round = $row['Round'];
		if($round == 0){
			$stmt = $db->prepare('SELECT CodeID FROM CodeTags WHERE TagID=:id AND RoundID>:lrid AND RoundID<:urid');
			$lb = 0;
			$up = 5;
			$stmt->bindValue(':lrid', $lb);
			$stmt->bindValue(':urid', $up);
		} elseif ($round == 2){
			$stmt = $db->prepare('SELECT CodeID FROM CodeTags WHERE TagID=:id AND RoundID>:lrid AND RoundID<:urid');
			$stmt->bindValue(':id', $i);
			$stmt->bindValue(':lrid', 2);
			$stmt->bindValue(':urid', 7);
			$update = 4;
		} elseif ($round == 4) {
			$stmt = $db->prepare('SELECT CodeID FROM CodeTags WHERE TagID=:id AND (RoundID>:lrid OR RoundID<:urid)');
			$stmt->bindValue(':id', $i);
			$stmt->bindValue(':lrid', 4);
			$stmt->bindValue(':urid', 3);
			$update = 0;
		}

		$stmt->bindValue(':id', $i);
		$results = $stmt->execute();
		$code = array();
		while($row = $results->fetchArray()){
			array_push($code, $row['CodeID']);
		}
		shuffle($code);
		$tmp_count = 0;
		for ($j = 0; $j < count($questions); $j++) {
			if($questions[$j]==$i){
				$set[$j]=$code[$tmp_count];
				$tmp_count++;
			}
		}
		$stmt = $db->prepare('UPDATE tag SET Round=:R WHERE ID=:id');
		$stmt->bindValue(':R', $update);
		$stmt->bindValue(':id', $i);
		$results = $stmt->execute();
	}

	##########################################
	//Add user to db
	$Browser=filter_input(INPUT_POST, "browser");
  	$results=$db->query('SELECT ID FROM User');
  	$id=1;
  	while($row=$results->fetchArray()) $id++;
  	$stmt = $db->prepare('INSERT INTO User (ID, Name, Browser) VALUES (:id,:n,:b)');
  	$stmt->bindValue(':id', $id);
  	$stmt->bindValue(':n', $Username);
  	$stmt->bindValue(':b',$Browser);
  	$results = $stmt->execute();
  	$db->close();

  	##########################################
  	//Ceate User Document
  	//Hard Coding Amount of Questions --- $QUESTIONCOUNT
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
	echo $id;
?>