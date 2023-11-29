<?php

global $conn;
$conn = new mysqli("localhost", "root", "xxxyyy", "leberkasrechner");

if ($conn->connect_error) {
    die("DB Connection Error");
} 