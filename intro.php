<!DOCTYPE html>
<html>
<head>
	<title></title>
<link rel = "stylesheet"
  type = "text/css"
  href = "home.css" /> 
  <script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
</head>
<body>
<?php 
$id=filter_input(INPUT_POST, "ID"); 
$example=file_get_contents("example.txt");
  print <<<HERE
<form action = "takeQuiz.php?s=1" method = "post">
<div class="home">
  <fieldset>
  <div class="group">
  <h2 align="left">Introduction</h2>
  <p>Thank you for volunteering for this research project. The purpose of this study is to understand how well programmers understand programming features.</p>
  <p>You must have at least 3 months of experience with C/C++. If you don’t, please let the experimenter know right now.</p>
  <p>You will be asked to answer the outcome of several short C programs. You will not see whether your answers are correct or not. Try your best and complete each question as soon as you can.</p>
  <h2 align="left">Examples</h2>
  <p>Here is an example that is similar to the questions you will see.<p>
  <pre class="prettyprint" style="border:0px">$example</pre>
  <label>Answer</label><input type = "text"name = "ANS" autocomplete="off"/>
  <p>You should enter “1 2” (one space two) in the textbox provided. Please note that:</p>
  <ul>
    <li>Enter decimal number only when necessary. That is enter 3 instead of 3.00 and 3.15 instead of 3.1500</li>
    <li>Please enter answers in the right order. In the previous example, 1 2 is correct but 2 1 is not correct.</li>
    <li>There is no syntax error in these questions. If you think there is a syntax problem, use your best estimation.</li>
  </ul>
  <input type = "hidden" name = "ID" value=$id />
  <button type = "submit">Next</button>
  </div>
  </fieldset>
</div>
</form>

HERE;
?>
</body>
</html>
