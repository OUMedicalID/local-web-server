<?php

//Change info to local web server when ready
$servername = "localhost";
$username = "tlliu";
$password = "xxxxxxx";
$dbname = "medicalid";

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
    $patientRecords .= "<tr id='tr-id-0' class='tr-class-0' data-title='bootstrap table' data-name='" . $row["MID_Name"] . "'>";
    $patientRecords .= "<td id='td-id-0' class='td-class-0' data-title='bootstrap table'>" . $row["MID_Name"] . "</td>";
    $patientRecords .= "<td data-value='100'>" . $row["MID_Gender"] . "</td>";
    $patientRecords .= "<td data-text='no'>" . $row["MID_Birthday"] . "</td>";
    $patientRecords .= "<td>" . $row["MID_City"] . "</td>";
    $patientRecords .= "<td data-text=''>" . $row["MID_HomePhone"] . "</td>";
    //Change conditions to check-in time when ready
    $patientRecords .= "<td data-text=''>" . $row["MID_AccessDate"] . "</td>";
    $patientRecords .= "<td data-i18n='Actions'>";
    $patientRecords .= "<a class='like' href='#' title='Like'><i class='fas fa-search'></i></a>";
    $patientRecords .= "</td></tr>";
}
//} else {
//  echo 'Data Not found';
//}

// echo Everytjing

echo $patientRecords;
