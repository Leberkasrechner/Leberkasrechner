<?php

$env = parse_ini_file('.env');
global $conn;
$conn = new mysqli($env["DBSERVER"], $env["DBUSER"], $env["DBPASSWORD"], $env["DBNAME"], intval($env["DBPORT"]));

if ($conn->connect_error) {
    die("DB Connection Error");
}
?>
