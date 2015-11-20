<!DOCTYPE html>
<html>
<head>
	<title>Intro</title>
<link rel = "stylesheet"
  type = "text/css"
  href = "home.css" /> 
  <script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>
</head>
<body>
<?php 
$page=filter_input(INPUT_GET, "page");
$id=filter_input(INPUT_POST, "ID"); 
if ($page==1){
  $example=file_get_contents("example.txt");
  print <<<HERE
<form action = "takeQuiz.php?s=1" method = "post">
<div class="home">
  <fieldset>
  <div class="group">
  <h2 align="left">Examples</h2>
  <p>Here is an example that is similar to the questions you will see.<p>
  <pre class="prettyprint" style="border:0px">$example</pre>
  <label>Answer</label><input type = "text"name = "ANS" autocomplete="off"/>
  <p>You should enter "1 2" (one space two) in the textbox provided. Please note that:</p>
  <ul>
    <li>Enter a decimal number only when necessary. Enter 3 instead of 3.00 and 3.15 instead of 3.1500.</li>
    <li>Please enter answers in the right order. In the previous example, 1 2 is correct but 2 1 is not correct.</li>
    <li>There is no syntax error in these questions. If you think there is a syntax problem, use your best estimation.</li>
    <li>Please answer each question as quickly as possible, but take sufficient time to make sure the answer is correct.</li>
  </ul>
  <p>You will see only one question at a time. You cannot go back to a previous question to change your answer. When you are ready, click "Start the Experiment" to start.</p>
  <input type = "hidden" name = "ID" value=$id />
  <button type = "submit">Start the Experiment</button>
  </div>
  </fieldset>
</div>
</form>

HERE;
}
else{
  print <<<HERE
<form action = "intro.php?page=1" method = "post">
<div class="home">
  <fieldset>
  <div class="group">
  <h2 align="left">Instructions</h2>
  <p>Thank you for volunteering for this research project. The purpose of this study is to understand how well programmers understand programming features.</p>
  <p>To participate, you must have at least 3 months of experience with C/C++. If you do not have this experience, please let the experimenter know right now.</p>
  <p>You will be asked to provide the outcome of several short C programs. You will not see whether your answers are correct or not. Try your best and complete each question as soon as you can.</p>
  <p>You will see an example of a study question on the next screen prior to beginning the experiment.</p>
  <input type = "hidden" name = "ID" value=$id />
  <button type = "submit">Next</button>
  </div>
  </fieldset>
</div>
</form>

HERE;
}

?>
</body>
</html>
