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
$username=filter_input(INPUT_POST, "ID"); 
if ($page==1){
  $example=file_get_contents("example.txt");
  print <<<HERE
<form action = "intro.php?page=2" method = "post">
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
    <li>Please enter answers in the right order. In the example above, 1 2 is correct but 2 1 is not correct.</li>
  </ul>
  <input type = "hidden" name = "ID" value=$username />
  <button type = "submit">Okay</button>
  </div>
  </fieldset>
</div>
</form>

HERE;
}
else if ($page ==2){
    print <<<HERE
<form action = "intro.php?page=3" method = "post">
<div class="home">
  <fieldset>
  <div class="group">
  <p>There is no syntax error in these questions. If you think there is a syntax problem, use your best estimation.</p>
  <input type = "hidden" name = "ID" value=$username />
  <button type = "submit">Okay</button>
  </div>
  </fieldset>
</div>
</form>

HERE;
}
else if ($page == 3){
print <<<HERE
<form action = "intro.php?page=4" method = "post">
<div class="home">
  <fieldset>
  <div class="group">
  <p>Please answer each question as quickly as possible, but take sufficient time to make sure the answer is correct.</p>
  <input type = "hidden" name = "ID" value=$username />
  <button type = "submit">Okay</button>
  </div>
  </fieldset>
</div>
</form>

HERE;
}
else if ($page == 4){
  print <<<HERE
<form action = "intro.php?page=5" method = "post">
<div class="home">
  <fieldset>
  <div class="group">
  <p>Please do not use paper and pencil during the experiment.</p>
  <input type = "hidden" name = "ID" value=$username />
  <button type = "submit">I agree</button>
  </div>
  </fieldset>
</div>
</form>

HERE;
}
else if ($page == 5){
  print <<<HERE
<form action = "intro.php?page=6" method = "post">
<div class="home">
  <fieldset>
  <div class="group">
  <p>Please do not use calculator of any form.</p>
  <input type = "hidden" name = "ID" value=$username />
  <button type = "submit">I agree</button>
  </div>
  </fieldset>
</div>
</form>

HERE;
}
else if ($page == 6){
  print <<<HERE
<form action = "intro.php?page=7" method = "post">
<div class="home">
  <fieldset>
  <div class="group">
  <p>Please do not use Google to search explanations during the experiment.</p>
  <input type = "hidden" name = "ID" value=$username />
  <button type = "submit">I agree</button>
  </div>
  </fieldset>
</div>
</form>

HERE;
}
else if ($page == 7){
  $count = 0;
  $id = 1;
  print <<<HERE
<div class="home">
  <fieldset>
  <div class="group">
  <p>You will see only one question at a time. You cannot go back to a previous question to change your answer. Once the experiment starts you should not take any break until the experiment is finished.</p><p>When you are ready, click "Start the Experiment" to start.</p>
  <button type = "submit" id = "start">Start the Experiment</button>
  </div>
  </fieldset>
</div>
<form action = "takeQuiz.php" method = "post" id = "myform">
  <input type = "hidden" name = "ID" value= $id id = "id"/>
  <input type = "hidden" name = "COUNT" value = $count />
</form>
<form>
  <input type = "hidden" name = "BROWSER" value = "chrome" id = "browser"/>
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
  <p>If you don't have at least 3 months of experience with C/C++, please stop and do not continue. Contact the experimenter right now.</p>
  <p>You will be asked to provide the outcome of many short C programs. You will not see whether your answers are correct or not. Try your best and complete each question as soon as you can.</p>
  <p>You will see an example of a study question on the next screen prior to beginning the experiment.</p>
  <input type = "hidden" name = "ID" value=$username />
  <button type = "submit">Next</button>
  </div>
  </fieldset>
</div>
</form>

HERE;
}

?>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script language="javascript">

$(function() {
// set a event handler to the button
  $("#start").click(function() {
    // retrieve form data
    var user = "<?php echo $username; ?>";
    var browser = $("#browser").val();
    // send form data to the server side php script.
    $.ajax({
        type: "POST",
        url: "init.php",
        data: {id:user, browser:browser}
    }).done(function( data ) {
        $('#id').attr('value', data);
        $('#myform').submit();
    });
  });
});
</script>
<script type="text/javascript">
    var page = "<?php echo $page; ?>";
    if (page == 7){
      var isOpera = (!!window.opr && !!opr.addons) || !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
      var isFirefox = typeof InstallTrigger !== 'undefined';
      var isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
      var isIE = /*@cc_on!@*/false || !!document.documentMode;
      var isEdge = !isIE && !!window.StyleMedia;
      var isChrome = !!window.chrome && !!window.chrome.webstore;
      var isBlink = (isChrome || isOpera) && !!window.CSS;
      var browser = "null";
      if (isOpera) browser = "Opera";
      else if (isFirefox) browser = "Firefox";
      else if (isSafari) browser = "Safari";
      else if (isIE) browser = "IE";
      else if (isEdge) browser = "Edge";
      else if (isChrome) browser = "Chrome";
      else if (isBlink) browser = "Blink";
      document.getElementById('browser').value = browser;
    }
</script>
</body>
</html>
