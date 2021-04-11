<?php
include("AES.php");
include("mysqlCredentials.php"); 

$servername = "localhost";
$username = "root";
$password = $MYSQL_Password;
$dbname = "localWebServer";
date_default_timezone_set('US/Eastern');


$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
//$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$sql = "SELECT count(*) FROM `staff`"; 
$result = $conn->prepare($sql); 
$result->execute(); 
$number_of_rows = $result->fetchColumn(); 
if((int)$number_of_rows !== 0)exit();


if($_POST["signup"] == "true"){

	$continue = true;

	if($_POST["password"] !== $_POST["confirmpass"]){
		$passwordMismatch = true;
		$continue = false;
	}

	$pattern = '/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,}$/';
	if(!preg_match($pattern, $_POST["password"])){
		$strengthFailed = true;
		$continue = false;
	}

	if(!ctype_alnum($_POST["username"])){
		$invalidUsername = true;
		$continue = false;
	}

	$password = password_hash($_POST["password"], PASSWORD_DEFAULT);

	if($continue){
		
      $salt = sodiumGenerate();
      $public_key = bin2hex($salt["public_key"]);
      $private = bin2hex($salt["keypair"]);


      $stmt = $conn->prepare("INSERT INTO staff (username,password,isAdmin,localKey,firstName, lastName) VALUES (?,?,?,?,?,?)");
      $stmt->execute([strtolower($_POST["username"]), $password, true, encrypt($_POST["password"], $private) ,"", ""]);


      $stmt = $conn->prepare("INSERT INTO settings (auto_logout_min, password_length, publicKey) VALUES (1,8,?)");
      $stmt->execute([$public_key]);



  		
	}




}






?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Medical ID Register</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
  </head>

  <body class="text-center">

    <form class="form-signin" action="register.php" method="POST">
      <img class="mb-4" src="assets/logo.jpg" alt="" width="72" height="72" style="border-radius: 12px;">
      <?php if($passwordMismatch) echo '<div class="alert alert-danger" role="alert"> Passwords do not match </div>'; ?>
      <?php if($strengthFailed) echo '<div class="alert alert-danger" role="alert"> Password must be at least 8 characters, have a number, and a special character. </div>'; ?>
      <?php if($invalidUsername) echo '<div class="alert alert-danger" role="alert"> Username is invalid. </div>'; ?>
      <h1 class="h3 mb-3 font-weight-normal">Register</h1>
      <label for="inputEmail" class="sr-only">Username</label>
      <input type="text" id="username" name="username" class="form-control" placeholder="Username" autofocus required>
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
      <label for="inputConfirmPassword" class="sr-only">Confirm Password</label>
      <input type="password" id="confirmpass" name="confirmpass" class="form-control" placeholder="Confirm Password" required>
      <input type="hidden" name="signup" value="true">
      <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
      <p class="mt-5 mb-3 text-muted">&copy; 2021-2022</p>
    </form>
  </body>
</html>