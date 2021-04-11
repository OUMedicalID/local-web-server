<?php

//Change info to local web server when ready
$servername = "localhost";
$username = "*******";
$password = "********";
$dbname = "******";

//Website Method
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password); // Initialize the connection.
$search = $_POST["search"];
$sql    = "SELECT * FROM users WHERE MID_Name LIKE :likeSearch ORDER BY MID_AccessDate DESC";
//When new database is put in
//$sql    = "SELECT * FROM users where MID_Name like :likeSearch order by MID_WhateverAccessDate desc";
$likeSearch = $search . "%";
$stmt = $conn->prepare($sql);
$stmt->execute(['likeSearch' => $likeSearch]);
$patientRecords = '';

//if ($stmt) {
while ($row = $stmt->fetch()) {

    if (!$row["MID_AccessDate"]) {
        $formattedDate = '';
    } else {
        $format = 'Y-m-d H:i:s';
        $date = DateTime::createFromFormat($format, $row["MID_AccessDate"]);
        $formattedDate = $date->format('M-d-Y H:i:s');
    }

    $patientRecords .= "<tr id='tr-id-0' class='tr-class-0' data-title='bootstrap table' data-name='" . $row["MID_Name"] . "'>";
    $patientRecords .= "<td id='td-id-0' class='td-class-0' data-title='bootstrap table'>" . $row["MID_Name"] . "</td>";
    $patientRecords .= "<td data-value='100'>" . $row["MID_Gender"] . "</td>";
    $patientRecords .= "<td data-text='no'>" . $row["MID_Birthday"] . "</td>";
    $patientRecords .= "<td>" . $row["MID_State"] . "</td>";
    $patientRecords .= "<td data-text=''>" . $row["MID_HomePhone"] . "</td>";
    //Change conditions to check-in time when ready
    $patientRecords .= "<td data-text=''>" . $formattedDate . "</td>";
    $patientRecords .= "<td data-i18n='Actions'>";
    //<button type="submit" class="searchSubmit" id="searchPatientButton"><i class="fa fa-search"></i></button>
    $patientRecords .= "<form action='template.php' name = 'searchIndividualForm' method='post'>";
    $patientRecords .= "<input name = 'searchIndividual' type='hidden' value='" . $row["email"] . "'/>";
    $patientRecords .= "<button type = 'submit' class='like searchSubmit' style = 'color:blue' title='Like'><i class='fas fa-search'></i></button>";
    //$patientRecords .= "<a class='like individualSearch' style = 'color:blue' title='Like' data-name='" . $row["email"] . "'><i class='fas fa-search'></i></a>";
    $patientRecords .= "</form></td></tr>";
}
//} else {
//  echo 'Data Not found';
//}

// echo Everytjing

echo $patientRecords;
