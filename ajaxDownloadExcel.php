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

?>



<?php

    

   
    if (isset($_POST['downloadSubmit'])) {

        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        //Notice: Trying to access array offset on value of type null in C:\xampp\htdocs\local-web-server1\download.php on line 177
        //Notice: Undefined variable: POST in C:\xampp\htdocs\local-web-server1\download.php on line 177
        //Fatal error: Uncaught Error: Call to a member function format() on bool in C:\xampp\htdocs\local-web-server1\download.php:205 Stack trace: #0 {main} thrown in C:\xampp\htdocs\local-web-server1\download.php on line 205
        $sql = "SELECT * from patients WHERE checkIn_Time >= '" . $startDate . "'AND checkIn_Time <= '" . $endDate . "' ORDER BY checkIn_Time DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        //Won't put in excel sheet if the MID_AccessDate is Null
        $data = [
            [
                'MID_Name', 'MID_Birthday', 'MID_Gender', 'MID_Address1', 'MID_Address2', 'MID_City', 'MID_State', 'MID_Zip', 'MID_HomePhone', 'MID_WorkPhone', 'MID_MaritalStatus', 'MID_Weight', 'MID_Height', 'MID_BloodType', 'MID_Ethnicity', 'MID_PrimaryInsurance', 'MID_PrimaryInsuranceNumber', 'MID_PrimaryInsuranceGroupNumberOrMainPH', 'MID_Conditions', 'MID_Injuries', 'MID_EContact1', 'MID_EContact2', 'checkIn_Time'
            ]
        ];
        while ($row = $stmt->fetch()) {

            $sampleData = [
                decryptSodium($row["MID_Name"],$sodium), decryptSodium($row["MID_Birthday"],$sodium), decryptSodium($row["MID_Gender"],$sodium), decryptSodium($row["MID_Address1"],$sodium), decryptSodium($row["MID_Address2"],$sodium), decryptSodium($row["MID_City"],$sodium), decryptSodium($row["MID_State"],$sodium), decryptSodium($row["MID_Zip"],$sodium), decryptSodium($row["MID_HomePhone"],$sodium), decryptSodium($row["MID_WorkPhone"],$sodium), decryptSodium($row["MID_MaritalStatus"],$sodium), decryptSodium($row["MID_Weight"],$sodium), decryptSodium($row["MID_Height"],$sodium), decryptSodium($row["MID_BloodType"],$sodium), decryptSodium($row["MID_Ethnicity"],$sodium), decryptSodium($row["MID_PrimaryInsurance"],$sodium), decryptSodium($row["MID_PrimaryInsuranceNumber"],$sodium), decryptSodium($row["MID_PrimaryInsuranceGroupNumberOrMainPH"],$sodium), decryptSodium($row["MID_Conditions"],$sodium), decryptSodium($row["MID_Injuries"],$sodium), decryptSodium($row["MID_EContact1"],$sodium), decryptSodium($row["MID_EContact2"],$sodium), $row["checkIn_Time"]
            ];

            array_push($data, $sampleData);
        }
        //Make date more user friendly
        if ($startDate) {
            $format = 'Y-m-d';
            $oldStartDate = DateTime::createFromFormat($format, $startDate);
            $oldEndDate = DateTime::createFromFormat($format, $endDate);
            $newStartDate = $oldStartDate->format('M-d-Y');
            $newEndDate = $oldEndDate->format('M-d-Y');
        } else {
            $newStartDate = date("M-d-Y");
            $newEndDate = date("M-d-Y");
        }

        $xlsx = SimpleXLSXGen::fromArray($data);
        $fileName = 'PatientFile_' . $newStartDate . '_to_' . $newEndDate . '.xlsx';
        $xlsx->saveAs($fileName);
/*
        $infilepath = '"C:\xampp\htdocs\local-web-server1\"' . $fileName . '';
        $outfilepath = '"C:\Users\tinma\Downloads\"' . $fileName . '';
        //$encryptionPassword = '~Random Password Function~';
        //$encryptCommand = 'type ' . $infilepath . ' | "C:\Users\tinma\AppData\Roaming\npm\secure-spreadsheet.cmd" --password $encryptPassword --input-format xlsx > ' . $outfilepath . '';
        $encryptCommand = 'type ' . $infilepath . ' | "C:\Users\tinma\AppData\Roaming\npm\secure-spreadsheet.cmd" --password secret --input-format xlsx > ' . $outfilepath . '';*/

        $random = rand(1,999999);
        $randPass = randomPassword();

        $encryptCommand = 'cat '.$fileName.' | secure-spreadsheet --password '.$randPass.' --input-format xlsx > output_'.$random.'.xlsx';

        exec($encryptCommand);

        //delete the old excel doc
        $deleteFileCommand = 'rm ' . $fileName . '';
        exec($deleteFileCommand);

        //Optional: shows user where the new password protected excel document by opening the downloads page
        //$openToNewFileCommand = 'start %windir%\explorer.exe "C:\Users\tinma\Downloads"';
        //exec($openToNewFileCommand);

        $firstURLPart = "https://".$_SERVER['HTTP_HOST'].str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(__DIR__))."/";

        echo json_encode(array("success" => "true", "password" => $randPass, "link" => $firstURLPart."output_".$random.".xlsx"));
        // Automatically delete file after 10 seconds of download.
        //exec('nohup (sleep 10 && rm output_'.$random.'.xlsx) > /dev/null 2>/dev/null &');
        shell_exec('(sleep 10 && rm output_'.$random.'.xlsx) > /dev/null 2>/dev/null &');
    } else {
        echo json_encode(array("error" => "Something went wrong."));
    }





    function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < 10; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
    ?>