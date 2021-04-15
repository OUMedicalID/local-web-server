<?php
include("AES.php");
include("mysqlCredentials.php"); 
session_start();
if(!isset($_SESSION["isLoggedIn"]))exit("Not logged in");
// We could also do a POST.
if(!isset($_GET["id"]))exit("Invalid ID");
$id = (int)$_GET["id"];


$servername = "localhost";
$username = "root";
$password = $MYSQL_Password;
$dbname = "localWebServer";

// Get the staffer's encrypted private key and decrypt it.

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password); // Initialize the connection.
$stmt = $conn->prepare("SELECT * FROM staff WHERE username=:username"); 
$stmt->execute(['username' => $_SESSION["username"]]); 
$row = $stmt->fetch(); 
$sodium = hex2bin(decrypt($_SESSION["shaPass"], $row["localKey"]));


// Grab the patient. record

$stmt = $conn->prepare("SELECT * FROM patients WHERE record_ID=:id"); 
$stmt->execute(['id' => $id]); 
$patientData = $stmt->fetch(); 

function decryptSodium($data, $sodium){
  $data = sodium_crypto_box_seal_open(hex2bin($data), $sodium);
  if($data == "")$data = "** Not Filled Out **";
  return $data;
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
    <style>
      .row{clear:both}

    .column{
    width: 50%;
    float: left;
    }
    </style>
    
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
            <h1 class="h2">Dashboard</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group mr-2">
                <button onclick="downloadPDF()" class="btn btn-sm btn-outline-secondary">Export</button>
              </div>

             <!-- <button class="btn btn-sm btn-outline-secondary dropdown-toggle">
                <span data-feather="calendar"></span>
                This week
              </button>-->
            </div>
          </div>

          <h5 style="text-align: center;" id="passInfo"></h5>

          <div class="row">
  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Personal Information</h5>     
          <form>
  <fieldset disabled>
    <div class="form-group">
      <label for="disabledTextInput">Name</label>
      <input type="text" id="disabledTextInput" class="form-control" value="<?php echo decryptSodium($patientData["MID_Name"], $sodium); ?>">
    </div>

    <div class="form-group">
      <label for="disabledTextInput">Birthdate</label>
      <input type="text" id="disabledTextInput" class="form-control" value="<?php echo decryptSodium($patientData["MID_Birthday"], $sodium); ?>">
    </div>

    <div class="form-group">
      <label for="disabledSelect">Gender</label>
      <input type="text" id="disabledTextInput" class="form-control" value="<?php echo decryptSodium($patientData["MID_Gender"], $sodium); ?>">
    </div>

    <div class="form-group">
      <label for="disabledTextInput">Address 1</label>
      <input type="text" id="disabledTextInput" class="form-control" value="<?php echo decryptSodium($patientData["MID_Address1"], $sodium); ?>">
    </div>

    <div class="form-group">
      <label for="disabledTextInput">Address 2</label>
      <input type="text" id="disabledTextInput" class="form-control" value="<?php echo decryptSodium($patientData["MID_Address2"], $sodium); ?>">
    </div>

    <div class="form-group">
      <label for="disabledTextInput">City</label>
      <input type="text" id="disabledTextInput" class="form-control" value="<?php echo decryptSodium($patientData["MID_City"], $sodium); ?>">
    </div>

    <div class="form-group">
      <label for="disabledTextInput">State</label>
      <input type="text" id="disabledTextInput" class="form-control" value="<?php echo decryptSodium($patientData["MID_State"], $sodium); ?>">
    </div>

    <div class="form-group">
      <label for="disabledTextInput">Zip Code</label>
      <input type="text" id="disabledTextInput" class="form-control" value="<?php echo decryptSodium($patientData["MID_Zip"], $sodium); ?>">
    </div>

    <div class="form-group">
      <label for="disabledTextInput">Home Phone</label>
      <input type="text" id="disabledTextInput" class="form-control" value="<?php echo decryptSodium($patientData["MID_HomePhone"], $sodium); ?>">
    </div>

    <div class="form-group">
      <label for="disabledTextInput">Work Phone</label>
      <input type="text" id="disabledTextInput" class="form-control" value="<?php echo decryptSodium($patientData["MID_WorkPhone"], $sodium); ?>">
    </div>
    
  </fieldset>
</form>
      </div>
    </div>
  </div>



  <div class="col-sm-6">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Other Personal Information</h5>
        <form>
          <fieldset disabled>
            <div class="form-group">
              <label for="disabledTextInput">Marital Status</label>
              <input type="text" id="disabledTextInput" class="form-control" value="<?php echo decryptSodium($patientData["MID_MaritalStatus"], $sodium); ?>">
            </div>
        
            <div class="form-group">
              <label for="disabledTextInput">Weight</label>
              <input type="text" id="disabledTextInput" class="form-control" value="<?php echo decryptSodium($patientData["MID_Weight"], $sodium); ?>">
            </div>
        
            <div class="form-group">
              <label for="disabledSelect">Height</label>
              <input type="text" id="disabledTextInput" class="form-control" value="<?php echo decryptSodium($patientData["MID_Height"], $sodium); ?>">
            </div>
        
            <div class="form-group">
              <label for="disabledTextInput">Blood Type</label>
              <input type="text" id="disabledTextInput" class="form-control" value="<?php echo decryptSodium($patientData["MID_BloodType"], $sodium); ?>">
            </div>
        
            <div class="form-group">
              <label for="disabledTextInput">Ethnicity</label>
              <input type="text" id="disabledTextInput" class="form-control" value="<?php echo decryptSodium($patientData["MID_Ethnicity"], $sodium); ?>">
            </div>
        
            <div class="form-group">
              <label for="disabledTextInput">Primary Insurance</label>
              <input type="text" id="disabledTextInput" class="form-control" value="<?php echo decryptSodium($patientData["MID_PrimaryInsurance"], $sodium); ?>">
            </div>
        
            <div class="form-group">
              <label for="disabledTextInput">Primary Insurance Number</label>
              <input type="text" id="disabledTextInput" class="form-control" value="<?php echo decryptSodium($patientData["MID_PrimaryInsuranceNumber"], $sodium); ?>">
            </div>
        
            <div class="form-group">
              <label for="disabledTextInput">Primary Insurance Group</label>
              <input type="text" id="disabledTextInput" class="form-control" value="<?php echo decryptSodium($patientData["MID_PrimaryInsuranceGroupNumberOrMainPH"], $sodium); ?>">
            </div>
          </fieldset>
        </form>
      </div>
    </div>
  </div>

  



</div>
<br>


<div class="col-sm-12">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Other Personal Information</h5>
        
        <div class="row">
          <div class="column">
              <h6>Medical Conditions</h6>
              <ul>
                  <?php
                  $array = json_decode(decryptSodium($patientData["MID_Conditions"],$sodium),true);
                  foreach($array as $a){
                    echo '<li>'.$a.'</li>';
                  }
                  ?>
              </ul>
          </div>
          <div class="column">
              <h6>Injuries</h6>
              <ul>
                   <?php
                  $array = json_decode(decryptSodium($patientData["MID_Injuries"],$sodium),true);
                  foreach($array as $a){
                    echo '<li>'.$a.'</li>';
                  }
                  ?>
              </ul>
          </div>
      </div>

      </div>
      </div>
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


<?php
$postData = "";
foreach($patientData as $key=>$value){
	if(is_numeric($key) || $key == "record_ID" || $key == "checkIn_Time")continue;
	$postData .= $key."=".decryptSodium($value, $sodium)."&";
}

$postData = rtrim($postData, '&');

?>


    <script>
	function downloadPDF() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      //document.getElementById("demo").innerHTML = this.responseText;
      
      console.log(this.responseText);
      var obj = JSON.parse(this.responseText);
      document.getElementById("passInfo").innerHTML = "Password to PDF file is: "+obj["password"];
      
      // Simulate click with download attribute to download the PDF instead of viewing in-browser.
      var a = document.createElement('a');
      a.href = obj["link"];
      a.download = 'patientRecord_enc_'+Math.floor(Math.random() * 100);
      a.click();
      
    }
  };

  

    xhttp.open("POST", "ajaxDownloadPDF.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('<?php echo $postData; ?>');
}



</script>

  
  </body>
</html>