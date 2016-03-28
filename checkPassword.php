<?php
	class MyDB extends SQLite3
	{
	    function __construct()
	    {
	        $this->open('confusion.db');
	    }
	}
	$db = new MyDB();
	$uname=filter_input(INPUT_POST, "uname");
	$pwd=filter_input(INPUT_POST, "pwd");

    $db = new MyDB();
	$stmt = $db->prepare('SELECT * FROM usrpwd WHERE Username=:uname');
	$stmt->bindValue(':uname', $uname);
	$results = $stmt->execute();
	$permission = 0;
	while($row = $results->fetchArray()){
		if ($row['Password']==$pwd && $row['Permission'] > 0){
			$permission=1;
			$count = $row['Permission'] - 1;
			$stmt = $db->prepare('UPDATE usrpwd SET Permission=:P WHERE Username=:uname');
		    $stmt->bindValue(':P', $count);
			$stmt->bindValue(':uname', $uname);
		    $results = $stmt->execute();
		    break;
		}
	}	
	$db -> close();
    if( $permission == 1 ){
        echo "OK";
    }else{
        echo "NG";
    }
?>