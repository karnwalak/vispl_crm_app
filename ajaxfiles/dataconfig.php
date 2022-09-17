<?php
session_start();
$servername = "localhost";
$username = "vispl_dbusr";
$password = "JhnBnhy6TrfT!!+";
$dbname = "mereid21_leadcrm";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Setup site url
if ($_SERVER['HTTP_HOST'] == 'localhost') {
    define('SITEURL','http://localhost/leadcrm/');
}
else if ($_SERVER['HTTP_HOST'] == 'crm.uat.smartping.in') {
    define('SITEURL','http://crm.uat.smartping.in/');
}
else {
    define('SITEURL','http://crm.smartping.in/');
}
?>
