<?php
include("AES.php");
include('exportExcelLibrary.php');
include("mysqlCredentials.php");
session_start();
if(!isset($_SESSION["isLoggedIn"]))exit("Not logged in");

$servername = "localhost";
$username = "root";
$password = $MYSQL_Password;
$dbname = "localWebServer";


$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password); // Initialize the connection.
$stmt = $conn->prepare("SELECT * FROM staff WHERE username=:username"); 
$stmt->execute(['username' => $_SESSION["username"]]); 
$row = $stmt->fetch(); 
$sodium = hex2bin(decrypt($_SESSION["shaPass"], $row["localKey"]));

function decryptSodium($data, $sodium){
  $data = sodium_crypto_box_seal_open(hex2bin($data), $sodium);
  if($data == "")$data = "** Not Filled Out **";
  return $data;
}

if(isset($_POST["formSubmitted"])){
   $continue = true;
   $firstName = $_POST['FirstName'];
   $lastName = $_POST['LastName'];

   if(!ctype_alpha($firstName) || !ctype_alpha($lastName))$continue = false;
   if(strlen($firstName) > 120 || strlen($lastName) > 120)$continue = false;

   if($continue){

     $stmt = $conn->prepare("UPDATE staff SET firstName=:firstName, lastName=:lastName WHERE username=:username");
     $data = [
        'firstName' => $firstName,
        'lastName' => $lastName,
        'username' => $_SESSION["username"]
      ];
      $stmt->execute($data);
  }
}


if(isset($_POST["currentPassword"])){

  if (password_verify($_POST["currentPassword"], $row["password"])) {
      $pattern = '/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,}$/';
      if(preg_match($pattern, $_POST["inputNewPassword"])){
          if($_POST["inputNewPassword"] == $_POST["confirmNewPassword"]){

            $newUnhashedPassword = $_POST["inputNewPassword"];
            $_SESSION["shaPass"] = hash("sha512", $newUnhashedPassword);
            $newLocalKey = encrypt($newUnhashedPassword, bin2hex($sodium));

            $stmt = $conn->prepare("UPDATE staff SET password=:password, localKey=:localKey WHERE username=:username");
            $data = [
                'password' => password_hash($newUnhashedPassword, PASSWORD_DEFAULT),
                'localKey' => $newLocalKey,
                'username' => $_SESSION["username"]
              ];
            $stmt->execute($data);

            echo "Password is changed.";

          }
      }
  }

}




?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>Medical ID</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
  </head>

  <body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Medical ID</a>
      <input class="form-control form-control-dark w-100" type="text" placeholder="Search Patient" aria-label="Search Patient">
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="signin.html">Sign out</a>
        </li>
      </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link" href="template.html">
                  <span data-feather="home"></span>
                  Dashboard <span class="sr-only">(current)</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="records.php">
                  <span data-feather="search"></span>
                  Search Records
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="userSettings.html">
                  <span data-feather="tool"></span>
                  Account Settings
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="adminSettings.html">
                  <span data-feather="settings"></span>
                  Admin Settings
                </a>
              </li>

              <!--<li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="bar-chart-2"></span>
                  Reports
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="layers"></span>
                  Integrations
                </a>
              </li>-->

            </ul>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>All Data</span>
              <!--<a class="d-flex align-items-center text-muted" href="#">
                <span data-feather="plus-circle"></span>
              </a>-->
            </h6>

            <ul class="nav flex-column mb-2">
              <li class="nav-item">
                <a class="nav-link" href="download.html">
                  <span data-feather="file-text"></span>
                  Download All Data
                </a>
              </li>
<!--
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="file-text"></span>
                  Last quarter
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="file-text"></span>
                  Social engagement
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="file-text"></span>
                  Year-end sale
                </a>
              </li>
            -->


            </ul>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">User Settings</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group mr-2">
              </div>

             <!-- <button class="btn btn-sm btn-outline-secondary dropdown-toggle">
                <span data-feather="calendar"></span>
                This week
              </button>-->
            </div>
          </div>
  
            
   <h4 class="h4">User Info</h4>  


  <form action="userSettings.php" method="POST">
    <div class="form-row">
      <div class="col">
        <input type="text" class="form-control" id="FirstName" name="FirstName" placeholder="First name" value="<?php echo $row["firstName"]; ?>">
      </div>
      <div class="col">
        <input type="text" class="form-control" id="LastName" name="LastName" placeholder="Last name" value="<?php echo $row["lastName"]; ?>">
      </div>
    </div>
           
   
   <br>
   
  <h4 class="h4">Update Password</h4>
          <div class="card">
  <div class="card-body">
      <h5 class="h5">Current Password</h5>
       <label for="currentPassword" class="sr-only">Password</label>
      <input type="password" id="currentPassword" class="form-control" name="currentPassword" placeholder="Current Password">
      <div class="checkbox mb-3"></div>
      <h5 class="h5">New Password</h5>
      <label for="inputNewPassword" class="sr-only">Password</label>
      <input type="password" id="inputNewPassword" class="form-control" name="inputNewPassword" placeholder="New Password">
      <div class="checkbox mb-3"></div>
      <h5 class="h5">Confirm New Password</h5>
      <label for="confirmNewPassword" class="sr-only">Password</label>
      <input type="password" id="confirmNewPassword" class="form-control" name="confirmNewPassword" placeholder="Confirm New Password">
      <div class="checkbox mb-3"></div>
    </div>

</div>
<br>
<input type="hidden" name="formSubmitted" value="true" />
<input type="submit" class="btn btn-primary" value="Submit"></button>
</form>
</main>
              
              
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="js/vendor/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>

    
  </body>
</html>