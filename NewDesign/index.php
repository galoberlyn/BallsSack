<?php
include('login.php');

if(isset($_SESSION['username'])) {
	header("Location: dashboard/dashboard.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Log In</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Coming soon, Bootstrap, Bootstrap 3.0, Free Coming Soon, free coming soon, free template, coming soon template, Html template, html template, html5, Code lab, codelab, codelab coming soon template, bootstrap coming soon template">
    <link href='http://fonts.googleapis.com/css?family=EB+Garamond' rel='stylesheet'
        type='text/css' />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,300,800'
        rel='stylesheet' type='text/css' />
    <!-- ============ Add custom CSS here ============ -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
      <link href="css/style.css" rel="stylesheet" type="text/css" />    
   
    <link href="css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="css/placing.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="placing">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <div class="registrationform">
            <form method="POST" class="form-horizontal">
                <fieldset>
                    <legend ><p id="login-center">Log-in</p> </legend>
                    <div class="login-input">
                    <div class="form-group">
                        <label for="myusername" class="col-lg-2 control-label">
                            Username</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" name="myusername" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="mypassword" class="col-lg-2 control-label">
                            Password</label>
                        <div class="col-lg-10">
                            <input type="password" class="form-control" name="mypassword" required>
                                                 
                        </div>
                    </div>
                    </div>
                    <div class="button-vertical">
                    <div class="form-group">
                        <div class="col-lg-10 col-lg-offset-2"><div class="clearbtn">
                            <button type="reset" class="btn btn-warning">
                                Clear</button></div>
                            <div class="submitbtn">
                            <button type="submit" name="submit" class="btn btn-primary">
                                Log-in</button>
                            </div><br>
             
                        </div>
                    </div>
                    </div>
                </fieldset>
            </form>
         </div>


            </div>
         </div>


       
        <script src="js/jquery.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/jquery.backstretch.js" type="text/javascript"></script>
</body>
</html>