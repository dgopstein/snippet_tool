<!DOCTYPE html>
<html>
<head>
	<title>Consent Form</title>
	<style>
	.pdf{
		position: relative;
		left:15%;
		margin-top: 5%;
	}
	.pdf_content{
		width: 70%;
		height: 70vh;
		border-style: outset;
		border-width: medium;
	}

	button {
	  font-family: 'Open Sans', sans-serif;
	  display: block;
	  font-weight: bold;
	  margin: 50px auto 0px auto;
	}

}
	</style>
</head>
<body>
	<div class="pdf">
  	<object type="application/pdf" data="consent.pdf" class="pdf_content">
    	<p>ERROR</p>
  	</object>

	</div>
	<?php
	$id=filter_input(INPUT_POST, "ID"); 
	print <<<HERE
	<form class = "button" action = "intro.php" method = "post">
		<input type = "hidden" name = "ID" value=$id />
		<button  type = "submit">I agree.</button>
	</form>
HERE;
	?>
</body>
</html>