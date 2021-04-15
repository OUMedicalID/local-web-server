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

if($row["isAdmin"] == 0){
  // Not an admin.
  exit();
}


if(isset($_POST["editLogoutTime"])){
  $newTime = (int)$_POST["logoutTime"];
  $stmt = $conn->prepare("UPDATE settings SET auto_logout_min=:auto_logout_min LIMIT 1");
  $data = ['auto_logout_min' => $newTime];
  $stmt->execute($data);
}

if(isset($_POST["newUser"])){
   $zero = 0;
   $continue = true;
   $firstName = $_POST['fname'];
   $lastName = $_POST['lname'];

   if(!ctype_alpha($firstName) || !ctype_alpha($lastName))$continue = false;
   if(strlen($firstName) > 120 || strlen($lastName) > 120)$continue = false;

   // Check that username doesn't already exist

   $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password); // Initialize the connection.
   $stmt = $conn->prepare("SELECT * FROM staff WHERE username=:username"); 
   $stmt->execute(['username' => $_POST["username"]]); 
   $row = $stmt->fetch(); 
   if(count($row) > 1)$continue = false;


   if($continue){
      $pattern = '/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,}$/';
      if(preg_match($pattern, $_POST["password"])){
          if($_POST["password"] == $_POST["confirmpass"]){

            $newUnhashedPassword = $_POST["password"];
            $newLocalKey = encrypt($newUnhashedPassword, bin2hex($sodium));
            $stmt = $conn->prepare("INSERT INTO staff (username, password, firstName, lastName, isAdmin, localKey) VALUES (:username, :password, :firstName, :lastName, :isAdmin, :localKey)");
            $data = [
                'username' => $_POST["username"],
                'password' => password_hash($newUnhashedPassword, PASSWORD_DEFAULT),
                'firstName' => $firstName,
                'lastName' => $lastName,
                'isAdmin' => $zero,
                'localKey' => $newLocalKey
              ];
            $stmt->execute($data);

            //echo "Password is changed.";

          }
      }
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

    <title>Medical ID</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
  </head>

  <body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Medical ID</a>
      
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="logout.php">Sign out</a>
        </li>
      </ul>
    </nav>

<?php include("menu.php"); ?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Admin Settings</h1>
          </div>

          <form action="adminSettings.php" method="POST">
            <div class="form-group">
              <label for="logoutTime">Auto Logout Time (In Minutes)</label>
              <input type="number" min="1" class="form-control" id="logoutTime" name="logoutTime" placeholder="Number (in Minutes)">
            </div>
            
            <input type="hidden" name="editLogoutTime" value="true" />
            <button type="submit" class="btn btn-primary">Save Settings</button>
          </form>


          <br>


          <div class="card">
  <h5 class="card-header">Create New User</h5>
  <div class="card-body">
   <form action="adminSettings.php" method="POST">
  <div class="form-group row">
    <label for="inputEmail3" class="col-sm-2 col-form-label">username</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="username" name="username" placeholder="Username">
    </div>
  </div>
  <div class="form-group row">
    <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="password" name="password" placeholder="Password">
    </div>
  </div>
  <div class="form-group row">
    <label for="inputPassword3" class="col-sm-2 col-form-label">Confirm Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="confirmpass" name="confirmpass" placeholder="Confirm Password">
    </div>
  </div>
  <div class="form-group row">
    <label for="inputPassword3" class="col-sm-2 col-form-label">First Name</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name">
    </div>
  </div>

  <div class="form-group row">
    <label for="inputPassword3" class="col-sm-2 col-form-label">Last Name</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name">
    </div>
  </div>

  <div class="form-group row">
    <div class="col-sm-10">
      <input type="hidden" name="newUser" value="true" />
      <button type="submit" class="btn btn-primary">Create</button>
    </div>
  </div>
</form>
  </div>
</div>


 
        </main>






      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="js/vendor/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>

    <!-- Graphs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
  </body>
</html>