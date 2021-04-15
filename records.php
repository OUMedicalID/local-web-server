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



?><!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="../../../../favicon.ico">

  <title>Medical ID</title>

  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/records.css" rel="stylesheet">
  <link href="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-9ZfPnbegQSumzaE7mks2IYgHoayLtuto3AS6ieArECeaR8nCfliJVuLh/GaQ1gyM" crossorigin="anonymous">

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



      <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
          <h1 class="h2">Patient Records</h1>
          
        </div>




        <table id="table" data-locale="en-US" data-toggle="table" data-search="true" data-show-columns="true" data-show-fullscreen="true" data-show-columns="true" data-show-columns-toggle-all="true" data-click-to-select="true" data-detail-formatter="detailFormatter" data-minimum-count-columns="2" data-show-pagination-switch="true" data-pagination="true" data-page-list="[10, 25, 50, 100, all]" data-sortable="true">
          <thead>
            <tr class="tr-class-1">
              <th data-field="name" rowspan="2" data-valign="middle" data-sortable="true">Name</th>
              <th colspan="6">Details</th>
            </tr>
            <tr class="tr-class-2">
              <th data-field="quantity" data-sortable="true">Gender</th>
              <th data-field="rush" data-sortable="true">DOB</th>
              <th data-field="date" data-sortable="true">City</th>
              <th data-field="actions" data-sortable="true">Home Phone</th>
              <th data-field="eproof" data-sortable="true">Time Checked In</th>
              <th data-field="order" data-sortable="true">Actions</th>
            </tr>
          </thead>
          <tbody id="tbody">

            <?php

           
            //Website Method
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password); // Initialize the connection.
            $stmt = $conn->query("SELECT * FROM patients ORDER BY checkIn_Time DESC");
            $patientRecords = '';



            //if ($stmt) {
            while ($row = $stmt->fetch()) {
              $patientRecords .= "<tr id='tr-id-0' class='tr-class-0' data-title='bootstrap table' data-name='Ash Ketchum'>";
              $patientRecords .= "<td id='td-id-0' class='td-class-0' data-title='bootstrap table'>" . decryptSodium($row["MID_Name"],$sodium) . "</td>";
              $patientRecords .= "<td data-value='100'>" . decryptSodium($row["MID_Gender"],$sodium) . "</td>";
              $patientRecords .= "<td data-text='no'>" . decryptSodium($row["MID_Birthday"],$sodium) . "</td>";
              $patientRecords .= "<td>" . decryptSodium($row["MID_City"],$sodium) . "</td>";
              $patientRecords .= "<td data-text=''>" . decryptSodium($row["MID_HomePhone"],$sodium) . "</td>";
              //Change conditions to check-in time when ready
              $patientRecords .= "<td data-text=''>" . $row["checkIn_Time"]. "</td>";
              $patientRecords .= "<td data-i18n='Actions'>";
              $patientRecords .= "<a class='like' href='personalInfo.php?id=".$row["record_ID"]."' title='Like'><i class='fas fa-search'></i></a>";
              $patientRecords .= "</td></tr>";
            }
            //} else {
            //  echo 'Data Not found';
            //}

            // echo Everytjing

            echo $patientRecords;

            ?>

          </tbody>
        </table>
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

  <script src="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table.min.js"></script>
  <script src="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table-locale-all.min.js"></script>
  <script src="https://unpkg.com/bootstrap-table@1.18.1/dist/extensions/export/bootstrap-table-export.min.js"></script>


  <!-- Icons -->
  <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
  <script>
    feather.replace()
  </script>

  <!-- Graphs -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script>
    //Does stuff on page load I think
    $(document).ready(function() {

      //So the bar would work whether enter is pressed or the search icon is pressed
      $("#searchPatient").keypress(function(e) {
        if (e.which == 13) {
          $("#searchPatientButton").click();
        }
      });
      $("#searchPatientButton").click(function(e) {

        //Hide info used by Smart table so only patient names searched using the top bar are displayed
        $("#tbody").children().hide();

        //Show table rows based on the row class names that are like the search input
        var searchValue = $("#searchPatient").val();
        if (searchValue != '') {

          //To hide the pagination stuff since it conflicts with the search by name bar
          $(".fixed-table-pagination").hide();

          //For the search bar 
          $.ajax({
            url: "searchPageAjax.php",
            method: "post",
            data: {
              //search is what is sent to the PHP file, searchValue is the value from the search input box
              search: searchValue
            },
            dataType: "text",
            success: function(data) {
              $("#tbody").html(data);
            }
          })
        } else {
          //Appears when the data is empty
          $("#tbody").html('');

        }
      });
    });
  </script>
</body>

</html>