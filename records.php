<!doctype html>
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
    <form action="searchPatient.php"></form>
    <input class="form-control form-control-dark w-100" type="text" placeholder="Search Patient By Name" aria-label="Search Patient" name="searchPatient" id="searchPatient">
    <button type="submit" class="searchSubmit" id="searchPatientButton"><i class="fa fa-search"></i></button>
    </form>
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
              <a class="nav-link active" href="records.php">
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
              <a class="nav-link" href="download.php">
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
          <h1 class="h2">Patient Records</h1>
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

            //Change info to local web server when ready
            $servername = "localhost";
            $username = "********";
            $password = "********";
            $dbname = "********";

            //Website Method
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password); // Initialize the connection.
            $stmt = $conn->query("SELECT * FROM users ORDER BY MID_AccessDate DESC");
            $patientRecords = '';

            while ($row = $stmt->fetch()) {

              //To make the date easier to read
              if (!$row["MID_AccessDate"]) {
                $formattedDate = '';
              } else {
                $format = 'Y-m-d H:i:s';
                $date = DateTime::createFromFormat($format, $row["MID_AccessDate"]);
                $formattedDate = $date->format('M-d-Y H:i:s');
              }

              $patientRecords .= "<tr id='tr-id-0' class='tr-class-0' data-title='bootstrap table'>";
              $patientRecords .= "<td id='td-id-0' class='td-class-0' data-title='bootstrap table'>" . $row["MID_Name"] . "</td>";
              $patientRecords .= "<td data-value='100'>" . $row["MID_Gender"] . "</td>";
              $patientRecords .= "<td data-text='no'>" . $row["MID_Birthday"] . "</td>";
              $patientRecords .= "<td>" . $row["MID_State"] . "</td>";
              $patientRecords .= "<td data-text=''>" . $row["MID_HomePhone"] . "</td>";
              $patientRecords .= "<td data-text=''>" . $formattedDate . "</td>";
              $patientRecords .= "<td data-i18n='Actions'>";
              //<button type="submit" class="searchSubmit" id="searchPatientButton"><i class="fa fa-search"></i></button>
              $patientRecords .= "<form action='template.php' name = 'searchIndividualForm' method='post'>";
              $patientRecords .= "<input name = 'searchIndividual' type='hidden' value='" . $row["email"] . "'/>";
              $patientRecords .= "<button type = 'submit' class='like searchSubmit' style = 'color:blue' title='Like'><i class='fas fa-search'></i></button>";
              //$patientRecords .= "<a class='like individualSearch' style = 'color:blue' title='Like' data-name='" . $row["email"] . "'><i class='fas fa-search'></i></a>";
              $patientRecords .= "</form></td></tr>";
            }

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
        if (searchValue) {

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
      //This code sends the email when the search icon is clicked on the row
      //The "on" is used instead of "onClick" because the search icon won't work after page is already loaded

      //Might not be needed if there is a submit for in the html already
      $(document).on('click', '.individualSearch', function() {
        $individualEmail = $(this).attr("data-name");
        //alert($individualEmail);
        console.log($individualEmail);
        //$(this).searchIndividualForm.submit();
        document.searchIndividualForm.submit();
      });
    });
  </script>
</body>

</html>