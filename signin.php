<?php
include("mysqlCredentials.php");
date_default_timezone_set('US/Eastern');
$servername = "localhost";
$username = "root";
$password = $MYSQL_Password;
$dbname = "localWebServer";

if(isset($_POST["signin"])){

  if($_POST["username"] == null || empty($_POST["username"]))exit();
  if($_POST["password"] == null || empty($_POST["password"]))exit();

  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password); // Initialize the connection.
  $stmt = $conn->prepare("SELECT * FROM staff WHERE username=:username"); 
  $stmt->execute(['username' => $_POST["username"]]); 
  $row = $stmt->fetch();


  if (password_verify($_POST["password"], $row["password"])) {
    // User authenticated.

    session_start();
    $_SESSION["isLoggedIn"] = true;
    $_SESSION["username"] = $row["username"];
    $_SESSION["shaPass"] = hash("sha512", $_POST["password"]);

    header("Location: records.php");

  }


}


?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Medical ID Sign in</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/signin.css" rel="stylesheet">
  </head>

  <body class="text-center">
    <form class="form-signin" action="signin.php" method="POST">
      <img class="mb-4" src="assets/logo.jpg" alt="" width="72" height="72" style="border-radius: 12px;">
      <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
      <label for="email" class="sr-only">Email address</label>
      <input type="text" id="username" name="username" class="form-control" placeholder="Email address" autofocus>
      <label for="password" class="sr-only">Password</label>
      <input type="password" id="password" name="password" class="form-control" placeholder="Password" />
      <input type="hidden" name="signin" value="true" />
      <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      <a class="registerBtn" href="register.html"><label class="registerBtn" type="submit">Don't have an account? Sign up here!</label> </a>
      <p class="mt-5 mb-3 text-muted">&copy; 2021-2022</p>
    </form>
  </body>
</html>