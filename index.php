<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="EN" dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Quiz</title>
<link rel = "stylesheet"
      type = "text/css"
      href = "style.css" /> 
</head>
<body>
    
<?php
  print <<<HERE
<form action = "intro.php"
      method = "post">
<div class="home">
  <fieldset>
  <div class="group">
  <h2>Sign UP to Take a quiz!</h2>
  <p align="center" class="UrN">
     <label>User ID</label>
    <input type = "text"
           name = "ID" autofocus autocomplete="off"/>
           </p>
    <button type = "submit">
      Take quiz
    </button>
    </div>
  </fieldset>
  </div>
</form>

HERE;

?>

</body>
</html>
