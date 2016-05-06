<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Quiz</title>
<link rel = "stylesheet"
      type = "text/css"
      href = "home.css" /> 
<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
<script src="ifvisible.js"></script>

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

	$TABCOUNT = 21;
	$QUESTIONPERTAB = 4;
	$QUESTIONCOUNT = $TABCOUNT * $QUESTIONPERTAB;

	$id=filter_input(INPUT_POST, "ID");
	$count=filter_input(INPUT_POST,"COUNT");
	$count++;

	##########################################
	//Progress Bar
	$percentage=round(($count-1)/$QUESTIONCOUNT*100);
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

	print <<<HERE
	<div class="home">
	<fieldset>
	<div class="group">
	<p align="center"><b>Question $count</b></p>
	<div class="inline">
	<pre style="border:0px">
	<p class="quizBody">$quizBody</p>
	</pre> 
	</div> 
	<p align="center">
	<label>Answer</label>
	<input id = "answer" type = "text"
	       name = "ANS"  autofocus autocomplete="off" onkeyup="stoppedTyping()">
	       </p>
	<button type = "submit" id="next_button" disabled>
	  Next
	</button>
	</div>
	</fieldset>
	</div>
HERE;
	if ($count<$QUESTIONCOUNT){
		print <<<HERE
				<form action = "takeQuiz.php" method = "post" id = "myform">
HERE;
	}
	else{
		print <<<HERE
				<form action = "survey.php" method = "post" id = "myform">
HERE;
	}
	print<<<HERE
		<input type = "hidden" name = "ID" value=$id />
		<input type = "hidden" name = "COUNT" value=$count />
		</form>
		<form>
		<input type = "hidden" name = "idleTime" value=0 id = "idleTime"/>
		</form>
HERE;

  	$db->close();
 
?>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script language="javascript">
var startTime = new Date(); 
$(function() {
  $("#next_button").click(function() {
  	var endTime = new Date();        
  	var idleTime = $("#idleTime").val();
  	var timeTotal = (endTime - startTime);
    var timeSpent = (endTime - startTime - idleTime);    
    // retrieve form data
    var ans = $("#answer").val().toLowerCase();;
    var ID = "<?php echo $id; ?>";
    var COUNT = "<?php echo $count; ?>";
    // send form data to the server side php script.
    $.ajax({
        type: "POST",
        url: "time.php",
        data: { ans:ans, time:timeSpent, ttime:timeTotal, ID: ID, COUNT: COUNT }
    }).done(function(data) {
            $('#myform').submit();
    });
  });
});

$(document).unbind('keydown').bind('keydown', function (event) {
    var doPrevent = false;
    if (event.keyCode === 8) {
        var d = event.srcElement || event.target;
        if ((d.tagName.toUpperCase() === 'INPUT' && 
             (
                 d.type.toUpperCase() === 'TEXT' ||
                 d.type.toUpperCase() === 'PASSWORD' || 
                 d.type.toUpperCase() === 'FILE' || 
                 d.type.toUpperCase() === 'SEARCH' || 
                 d.type.toUpperCase() === 'EMAIL' || 
                 d.type.toUpperCase() === 'NUMBER' || 
                 d.type.toUpperCase() === 'DATE' )
             ) || 
             d.tagName.toUpperCase() === 'TEXTAREA') {
            doPrevent = d.readOnly || d.disabled;
        }
        else {
            doPrevent = true;
        }
    }

    if (doPrevent) {
        event.preventDefault();
    }
});
</script>
<script type="text/javascript">
    function stoppedTyping(){
        document.getElementById('next_button').disabled = false;
    }
</script>
<script type="text/javascript">
	var idleTime = 0;
	var idleStart = 0;
	var idleEnd = 0;
	var ID = "<?php echo $id; ?>";
    var COUNT = "<?php echo $count; ?>";
    ifvisible.setIdleDuration(20);

    ifvisible.blur(function(){
    	idleStart = new Date();
    	$.post('log.php',{ ID: ID, CodeID: COUNT, Event: "00" },
    	function(data){});
    });

    ifvisible.wakeup(function(){
        idleEnd = new Date();
       	idleTime = idleEnd - idleStart + idleTime;
       	document.getElementById('idleTime').value = idleTime;

       	$.post('log.php',{ ID: ID, CodeID: COUNT, Event: "01" },
    	function(data){});
    });

    ifvisible.idle(function(){
    	idleStart = new Date();
		if(!ifvisible.now('hidden')){
    		$.post('log.php',{ ID: ID, CodeID: COUNT, Event: "02" },
    		function(data){});
		} 
    });


</script>
</body>
</html>

