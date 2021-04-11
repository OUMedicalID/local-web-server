<?php

include("AES.php");
include("mysqlCredentials.php"); 
session_start();
if(!isset($_SESSION["isLoggedIn"]))exit("Not logged in");
// We could also do a POST.


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


//Website Method
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password); // Initialize the connection.
$search = $_POST["search"];
$sql    = "SELECT * FROM patients WHERE MID_Name LIKE :likeSearch ORDER BY checkIn_Time DESC";
//When new database is put in
//$sql    = "SELECT * FROM users where MID_Name like :likeSearch order by MID_WhateverAccessDate desc";
$likeSearch = $search . "%";
$stmt = $conn->prepare($sql);
$stmt->execute(['likeSearch' => $likeSearch]);
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
   $patientRecords .= "<td data-text=''>" . decryptSodium($row["checkIn_Time"],$sodium) . "</td>";
   $patientRecords .= "<td data-i18n='Actions'>";
   $patientRecords .= "<a class='like' href='personalInfo.php?id=".$row["record_ID"]."' title='Like'><i class='fas fa-search'></i></a>";
   $patientRecords .= "</td></tr>";
}
//} else {
//  echo 'Data Not found';
//}

// echo Everytjing

echo $patientRecords;
