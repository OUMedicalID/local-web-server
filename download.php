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
                                Dashboard
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
                            <a class="nav-link" href="adminSettings.html">
                                <span data-feather="settings"></span>
                                Admin Settings
                            </a>
                        </li>

                    </ul>

                    <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                        <span>All Data</span>
                    </h6>

                    <ul class="nav flex-column mb-2">
                        <li class="nav-item">
                            <a class="nav-link active" href="download.php">
                                <span data-feather="file-text"></span>
                                Download All Data
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4 downloadCenter">
                <div class="border-bottom pageTitle">
                    <h1 class="h2">Download Patient Records</h1>
                </div>
                <div class="downloadCenter">
                    
                        <label for="datepicker">
                            <h3>Select Timeframe:</h3>
                        </label>
                        
                            <input style="margin-left: auto; margin-right: auto;" class="form-control" id="datepicker" type="text" name="datepicker" value="01/01/2018 - 01/15/2018" />
                       
                        <input id="startDate" name="startDate" type="hidden" value="" placeholder="startDate" />
                        <input id="endDate" name="endDate" type="hidden" value="" placeholder="endDate" />
                        <div>
                            <button onclick="requestDownload()" type="submit" id="downloadSubmit" name="downloadSubmit" class="btn btn-primary btn-lg">Download
                                Records</button>
                        </div>


                        <br><br><h3 id="passInfo" style="text-align: center;"></h3>
                   
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

    <script src="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.18.1/dist/bootstrap-table-locale-all.min.js"></script>
    <script src="https://unpkg.com/bootstrap-table@1.18.1/dist/extensions/export/bootstrap-table-export.min.js">
    </script>

    <!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
        feather.replace()
    </script>

    <!-- Graphs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>

    <script>
        $(document).ready(function() {

            $("#datepicker").attr("value", "");
            $(function() {
                $('input[name="datepicker"]').daterangepicker({
                    opens: 'left'
                }, function(start, end, label) {
                    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' +
                        end.format('YYYY-MM-DD'));
                    $("#startDate").attr("value", start.format('YYYY-MM-DD'));
                    $("#endDate").attr("value", end.format('YYYY-MM-DD'));
                    var startVal = $("#startDate").val();
                    console.log(startVal);
                });
            });
        });
    </script>


    <script>
function requestDownload() {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      //document.getElementById("demo").innerHTML = this.responseText;
      console.log(this.responseText);
      var obj = JSON.parse(this.responseText);
      document.getElementById("passInfo").innerHTML = "Password to excel file is: "+obj["password"];
      window.open(obj["link"], "_blank");
      
    }
  };

    var todaysDate = new Date().toISOString().slice(0, 10);
    var startDate = document.getElementById("startDate").value;
    var endDate = document.getElementById("endDate").value;

    if(startDate == "" || startDate == null){
        startDate = todaysDate;
    }

    if(endDate == "" || endDate == null){
        endDate = todaysDate;
    }

    xhttp.open("POST", "ajaxDownloadExcel.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("downloadSubmit=1&startDate="+document.getElementById("startDate").value+"&endDate="+document.getElementById("endDate").value);
}
</script>
 
</body>

</html>