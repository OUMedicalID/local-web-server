<?php
  include("AES.php");
  include("mysqlCredentials.php"); 

  $servername = "localhost";
  $username = "root";
  $password = $MYSQL_Password;
  $dbname = "localWebServer";
  date_default_timezone_set('US/Eastern');

  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

  $sql = "SELECT count(*) FROM `staff`"; 
  $result = $conn->prepare($sql); 
  $result->execute(); 
  $number_of_rows = $result->fetchColumn(); 
  if((int)$number_of_rows !== 0)exit();


  if($_POST["create"] == "true"){

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

      if($_POST["fname"] == $_POST[" "]){
         $continue = false;
      }

      if($_POST["lname"] == $_POST[" "]){
         $continue = false;
      }

      if($continue){
        $salt = sodiumGenerate();
        $public_key = bin2hex($salt["public_key"]);
        $private = bin2hex($salt["keypair"]);



        $stmt = $conn->prepare("INSERT INTO staff (username,password,isAdmin,localKey,firstName, lastName) VALUES (?,?,?,?,?,?)");
        $stmt->execute([strtolower($_POST["username"]), $password, false, encrypt($_POST["password"], $private) ,$_POST["fname"], $_POST["lname"]]);
      }



  }

?>


<!doctype html>
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
                <a class="nav-link" href="userSettings.html">
                  <span data-feather="tool"></span>
                  Account Settings
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" href="adminSettings.html">
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
            <h1 class="h2">Dashboard</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group mr-2">
                <button class="btn btn-sm btn-outline-secondary">Share</button>
                <button class="btn btn-sm btn-outline-secondary">Export</button>
              </div>

             <!-- <button class="btn btn-sm btn-outline-secondary dropdown-toggle">
                <span data-feather="calendar"></span>
                This week
              </button>-->
            </div>
          </div>

          <form action="adminSettings.php" method="POST">
            <div class="form-group">
              <label for="logoutTime">Auto Logout Time (In Minutes)</label>
              <input type="number" min="1" class="form-control" id="logoutTime" name="logoutTime" placeholder="Number (in Minutes)">
            </div>
            

            <button type="submit" class="btn btn-primary">Save Settings</button>
          </form>


          <br>


          <div class="card">
  <h5 class="card-header">Create New User</h5>
  <div class="card-body">
   <form>
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
      <input type="hidden" name="create" value="true">
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
    <script>
      var ctx = document.getElementById("myChart");
      var myChart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
          datasets: [{
            data: [15339, 21345, 18483, 24003, 23489, 24092, 12034],
            lineTension: 0,
            backgroundColor: 'transparent',
            borderColor: '#007bff',
            borderWidth: 4,
            pointBackgroundColor: '#007bff'
          }]
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: false
              }
            }]
          },
          legend: {
            display: false,
          }
        }
      });
    </script>
  </body>
</html>