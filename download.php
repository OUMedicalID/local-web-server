<?php

require 'exportExcelLibrary.php';

//Change info to local web server when ready
$servername = "localhost";
$username = "tlliu";
$password = "TacoBell1";
$dbname = "medicalid";

//Website Method
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password); // Initialize the connection.
//$date = mysql_real_escape_string($date), prevents sql injection? Look at it later
//$startDate = '2011-06-01';
//$endDate = '2019-06-01';
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
//$sql    = "SELECT * FROM users where MID_Name like :likeSearch";
$sql = "SELECT * from users WHERE MID_AccessDate >= '" . $startDate . "'AND MID_AccessDate <= '" . $endDate . "' ORDER BY MID_AccessDate DESC";
//When new database is put in
//$sql    = "SELECT * FROM users where MID_Name like :likeSearch order by MID_WhateverAccessDate desc";
$stmt = $conn->prepare($sql);
$stmt->execute();

//echo $date;

//if ($stmt) {
if (isset($_POST['downloadSubmit'])) {

    $xlsx = new SimpleXLSXGen();
    $data = [
        ['email','MID_Name','MID_Birthday','MID_Gender','MID_Address1','MID_Address2','MID_City','MID_State'
        ,'MID_Zip','MID_HomePhone','MID_WorkPhone','MID_MaritalStatus','MID_Weight','MID_Height','MID_BloodType'
        ,'MID_Ethnicity','MID_PrimaryInsurance','MID_PrimaryInsuranceNumber','MID_PrimaryInsuranceGroupNumberOrMainPH'
        ,'MID_Conditions','MID_Injuries','MID_EContact1','MID_EContact2','MID_AccessDate']
    ];
    while($row = $stmt->fetch()){

        $sampleData = [$row["email"],$row["MID_Name"],$row["MID_Birthday"],$row["MID_Gender"],$row["MID_Address1"]
        ,$row["MID_Address2"],$row["MID_City"],$row["MID_State"],$row["MID_Zip"],$row["MID_HomePhone"]
        ,$row["MID_WorkPhone"],$row["MID_MaritalStatus"],$row["MID_Weight"],$row["MID_Height"],$row["MID_BloodType"]
        ,$row["MID_Ethnicity"],$row["MID_PrimaryInsurance"],$row["MID_PrimaryInsuranceNumber"],$row["MID_PrimaryInsuranceGroupNumberOrMainPH"]
        ,$row["MID_Conditions"],$row["MID_Injuries"],$row["MID_EContact1"],$row["MID_EContact2"],$row["MID_AccessDate"]];

        array_push($data,$sampleData);
    }
    foreach($data as $result) {
        echo $result[0], $result[1],'<br>';
    }
    SimpleXLSXGen::fromArray( $data )->downloadAs('MID_Test1.xlsx');
    /*
    //Working Example for adding to arrays in php
        $i = 1;
        $data = [];
        $sampleData = ['A'];
        while($i <= 10){
            array_push($data,$sampleData);
            $i = $i + 1;
        }
        foreach($data as $result) {
            echo $result[0], '<br>';
        }

    while($row = $stmt->fetch()){

    }
    //Library
        $xlsx = new SimpleXLSXGen();
        $data = [
            ['email','MID_Name','MID_Birthday','MID_Gender','MID_Address1','MID_Address2','MID_City','MID_State'
            ,'MID_Zip','MID_HomePhone','MID_WorkPhone','MID_MaritalStatus','MID_Weight','MID_Height','MID_BloodType'
            ,'MID_Ethnicity','MID_PrimaryInsurance','MID_PrimaryInsuranceNumber','MID_PrimaryInsuranceGroupNumberOrMainPH'
            ,'MID_Conditions','MID_Injuries','MID_EContact1','MID_EContact2','Date Last Active']

        ];
        SimpleXLSXGen::fromArray( $data )->downloadAs('MID_Test1.xlsx');

    */


    /*
    //Online Example
        $filename = "Export_excel_.xlsx";
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=".$filename."");
        $isPrintHeader = false;
        if(! empty($patientRow)){
            while ($row = $stmt->fetch()){
                if(!$isPrintHeader){
                    echo implode("\t", array_keys($row))."\n";
                    $isPrintHeader = true;
                }
                echo implode("\t", array_keys($row))."\n";
            }
        }
        exit();


    //HTML OUTPUT Example:
        $patientRecords .= "<tr id='tr-id-0' class='tr-class-0' data-title='bootstrap table' data-name='" . $row["MID_Name"] . "'>";
        $patientRecords .= "<td id='td-id-0' class='td-class-0' data-title='bootstrap table'>" . $row["MID_Name"] . "</td>";
        $patientRecords .= "<td data-value='100'>" . $row["MID_Gender"] . "</td>";
        $patientRecords .= "<td data-text='no'>" . $row["MID_Birthday"] . "</td>";
        $patientRecords .= "<td>" . $row["MID_City"] . "</td>";
        $patientRecords .= "<td data-text=''>" . $row["MID_HomePhone"] . "</td>";
        //Change conditions to check-in time when ready
        $patientRecords .= "<td data-text=''>" . $row["MID_Conditions"] . "</td>";
        $patientRecords .= "<td data-i18n='Actions'>";
        $patientRecords .= "<a class='like' href='#' title='Like'><i class='fas fa-search'></i></a>";
        $patientRecords .= "</td></tr>";
    */   
    
} else {
    echo "Not working either";
}
