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
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script language="javascript">

$(function() {
// set a event handler to the button
  $("#login").click(function() {
    // retrieve form data
    var uname = $("#uname").val().toLowerCase();;
    var pwd = $("#pwd").val();
    // send form data to the server side php script.
    $.ajax({
        type: "POST",
        url: "checkPassword.php",
        data: { uname:uname, pwd:pwd }
    }).done(function( data ) {
        if (data.indexOf("OK") >= 0){
            $('#id').attr('value', uname);
            $('#myform').submit();
        }else if(data.indexOf("NG") >= 0){
            alert("Login Failed!");
        }
    });
  });
});
</script>

<div class="home">
  <fieldset>
  <div class="group">
  <h2>Please enter the username and password you are assigned</h2>
  <p align="center" class="UrN">
     <label>Username</label>
    <input type = "text"
           name = "uname" autofocus autocomplete="off" id = "uname"/>
           </p>
  <p align="center" class="PwD">
   <label>Password</label>
  <input type = "password"
         name = "pwd" autocomplete="off" id = "pwd"/>
         </p>
    <button id = "login" type = "submit">
      Next
    </button>
    </div>
  </fieldset>
  </div>
  <form method="POST" action="consent.php" id="myform">
      <input type = "hidden" name = "ID" value = "" id = "id"/>
  </form>

</body>
</html>
