<?php
include("AES.php");
include('exportExcelLibrary.php');
include("mysqlCredentials.php");
session_start();
if(!isset($_SESSION["isLoggedIn"]))exit("Not logged in");

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
    <link href="css/download.css" rel="stylesheet">

    <style>
        .select,
        #locale {
            width: 100%;
        }

        .like {
            margin-right: 10px;
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


            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4 downloadCenter">
                <div class="border-bottom pageTitle">
                    <h1 class="h2">Simulate Patient Data Entry</h1>
                </div>
                <div class="downloadCenter">
                    

                        <textarea cols="5" rows="10" class="form-control" name="json" id="json">{
   "MID_Name":"Matt",
   "MID_Birthday":"03/16/2021",
   "MID_Gender":"Male",
   "MID_Address1":"123 random st",
   "MID_Address2":"",
   "MID_City":"auburn",
   "MID_State":"Hawaii",
   "MID_Zip":"12345",
   "MID_HomePhone":"72276272727",
   "MID_WorkPhone":"2828483838393",
   "MID_MaritalStatus":"Married",
   "MID_Weight":"125",
   "MID_Height":"283",
   "MID_BloodType":"B+",
   "MID_Ethnicity":"White",
   "MID_PrimaryInsurance":"Geico",
   "MID_PrimaryInsuranceNumber":"3737373773",
   "MID_PrimaryInsuranceGroupNumberOrMainPH":"282882828",
   "MID_Conditions":"[\"back pain\"]",
   "MID_Injuries":"[\"bit by alligator\"]"
}</textarea>             <br><br>

                        <div id="msg"></div>
                        <p style="text-align: center;">Note: Do not add new attributes to the JSON, otherwise this will not work. Simply just edit the values.</p> 
                        <br>
                        <button onclick="simulate()" type="button" class="btn btn-primary">Submit Simulation</button>

                </div>
            </main>
        </div>
    </div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script>
        window.jQuery || document.write('<script src="js/vendor/jquery-slim.min.js"><\/script>')
    </script>
    <script src="js/vendor/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

  
    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
        feather.replace()
    </script>

  

    <script>

function simulate() {

  var jsonData = JSON.parse(document.getElementById("json").value);

  var buildPost = "";

  for (var key in jsonData) {
    // check if the property/key is defined in the object itself, not in parent
    if (jsonData.hasOwnProperty(key)) {           
        console.log(key, jsonData[key]);
        buildPost += key+"="+jsonData[key]+"&";
    }
}


buildPost = buildPost.substring(0, buildPost.length - 1);





  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      
      //document.getElementById("demo").innerHTML = this.responseText;
      //console.log(this.responseText);
      //var obj = JSON.parse(this.responseText);
      //document.getElementById("passInfo").innerHTML = "Password to excel file is: "+obj["password"];
      
      document.getElementById("msg").innerHTML = '<div class="alert alert-success" role="alert">Simulation Done. Patient is in the database.</div>';
      
    }
  };


    xhttp.open("POST", "receiveData.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(buildPost);

    
}
</script>
 
</body>

</html>