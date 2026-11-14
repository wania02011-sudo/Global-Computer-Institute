
<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "GlobalComputerInstitute";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// show errors for dev(minor error bhi bta dega )
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

?>