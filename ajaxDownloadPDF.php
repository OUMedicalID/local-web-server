<?php
include("AES.php");
include("mysqlCredentials.php"); 
session_start();
if(!isset($_SESSION["isLoggedIn"]))exit("Not logged in");


$template = file_get_contents("individualDataTemplate.html");
$template = str_replace("@_NAME_@", $_POST["MID_Name"], $template);
$template = str_replace("@_DOB_@", $_POST["MID_Birthday"], $template);
$template = str_replace("@_GENDER_@", $_POST["MID_Gender"], $template);
$template = str_replace("@_ADDRESS1_@", $_POST["MID_Address1"], $template);
$template = str_replace("@_ADDRESS2_@", $_POST["MID_Address2"], $template);
$template = str_replace("@_CITY_@", $_POST["MID_City"], $template);
$template = str_replace("@_STATE_@", $_POST["MID_State"], $template);
$template = str_replace("@_ZIP_@", $_POST["MID_Zip"], $template);
$template = str_replace("@_HOMEP_@", $_POST["MID_HomePhone"], $template);
$template = str_replace("@_WORKP_@", $_POST["MID_WorkPhone"], $template);


file_put_contents("doc_".rand(1,99999).".html",$template);
