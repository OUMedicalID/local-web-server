<?php
include("mysqlCredentials.php");

$servername = "localhost";
$username = "root";
$password = $MYSQL_Password;
$dbname = "localWebServer";
date_default_timezone_set('US/Eastern');
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password); // Initialize

// Obtain the public key.
$stmt = $conn->prepare("SELECT * FROM settings");
$stmt->execute(); 
$row = $stmt->fetch();
$sodiumKey = hex2bin($row["publicKey"]);



$_POST["checkIn_Time"] = date('Y-m-d');
$stmt = $conn->prepare("INSERT INTO patients ".createKeyList()." VALUES ".createKeyPreparedList());
$info = array();

foreach($_POST as $key=>$value){
	if($key == "checkIn_Time")
		$val = $value;
	else
		$value = bin2hex(sodium_crypto_box_seal($value, $sodiumKey));
	$info[$key] = $value;
}

$stmt->execute($info); 




function createKeyList(){
	$str = "(";
	$keys = array_keys($_POST);
	foreach($keys as $key) $str .= $key.", ";
	return rtrim($str, ", ").")";

}
function createKeyPreparedList(){
	$str = "(";
	$keys = array_keys($_POST);
	foreach($keys as $key) $str .= ":".$key.", ";
	return rtrim($str, ", ").")";

}
function createValueList(){
	$str = "(";
	$values = array_values($_POST);
	foreach($values as $val) $str .= $val.", ";
	return rtrim($str, ", ").")";

}