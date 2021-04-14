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
$template = str_replace("@_MARITAL_@", $_POST["MID_MaritalStatus"], $template);
$template = str_replace("@_WEIGHT_@", $_POST["MID_Weight"], $template);
$template = str_replace("@_HEIGHT_@", $_POST["MID_Height"], $template);
$template = str_replace("@_BLOODTYPE_@", $_POST["MID_BloodType"], $template);
$template = str_replace("@_ETHNICITY_@", $_POST["MID_Ethnicity"], $template);
$template = str_replace("@_PI_@", $_POST["MID_PrimaryInsurance"], $template);
$template = str_replace("@_PIN_@", $_POST["MID_PrimaryInsuranceNumber"], $template);
$template = str_replace("@_PIGROUP_@", $_POST["MID_PrimaryInsuranceGroupNumberOrMainPH"], $template);

$random = random_int(1, 999999); // Generates secure random int vs using standard rand().
$random2 = random_int(1, 999999);
$randPass = random_str(12); 


$conditions = json_decode($_POST["MID_Conditions"],true);
$conds = "";
foreach($conditions as $cond){
	$conds .= "<li>".$cond."</li>";
}

$injuries = json_decode($_POST["MID_Injuries"],true);
$injuryInfo = "";
foreach($injuries as $injury){
	$injuryInfo .= "<li>".$injury."</li>";
}

$template = str_replace("@_CONDITIONS_@", $conds, $template);
$template = str_replace("@_INJURIES_@", $injuryInfo, $template);

// Create the html page.
file_put_contents("doc_".$random.".html",$template);

// Conver html to pdf.
shell_exec("wkhtmltopdf doc_".$random.".html patientRecord_".$random.".pdf");

//At this point we have the pdf. Delete the doc.
shell_exec('(sleep 15 && rm doc_'.$random.'.html) > /dev/null 2>/dev/null &');

// Generate the secure PDF. We use $random2 to prevent downloading patientRecord$random.pdf before it deletes.
shell_exec("pdftk patientRecord_".$random.".pdf output patientRecord_enc_".$random2.".pdf user_pw ".$randPass);


$firstURLPart = "https://".$_SERVER['HTTP_HOST'].str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(__DIR__))."/";

echo json_encode(array("success" => "true", "password" => $randPass, "link" => $firstURLPart."patientRecord_enc_".$random2.".pdf"));


// Delete the old PDF
shell_exec('(sleep 15 && rm patientRecord_'.$random.'.pdf) > /dev/null 2>/dev/null &');
shell_exec('(sleep 15 && rm patientRecord_enc_'.$random2.'.pdf) > /dev/null 2>/dev/null &');



// Cryptographically secure random str generator.
function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    if ($max < 1) {
        throw new Exception('$keyspace must be at least two characters long');
    }
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)]; //Utilze random_int() instead of rand().
    }
    return $str;
}