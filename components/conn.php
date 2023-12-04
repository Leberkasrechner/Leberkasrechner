<?php

$env = parse_ini_file(__DIR__ . '/../.env');
global $conn;
$conn = new mysqli($env["DBSERVER"], $env["DBUSER"], $env["DBPASSWORD"], $env["DBNAME"], intval($env["DBPORT"]));

if ($conn->connect_error) {
    die("DB Connection Error");
}



function getValue($table, $column_given, $value_given, $column_searched, $exit=false) {
        
        if(!(isset($table) && isset($column_given) && isset($value_given) && isset($column_searched))) {
            return " ";
        }
        global $conn;
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT $column_searched FROM $table WHERE $column_given = $value_given";
        $res = $conn->query($sql);
        if($res && $res->num_rows>0) {
            $ret = mysqli_fetch_array($res)[0];
            return $ret;
        } else {
            if($exit) {exit();}
            return false;
        }
    }

    function getEntity($table, $column_given, $value_given, $fetch=true) {

        global $conn;
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM $table WHERE $column_given = '$value_given'";
        $res = $conn->query($sql);
        if($res && $res->num_rows>0) {
            if($fetch) { $res = mysqli_fetch_array($res); }
            return $res;
        } else {
            return false;
        }
    }


    function getTable($table) {
        global $conn;
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }


        $sql = "SELECT * FROM $table";
        $res = $conn->query($sql);
        if($res && $res->num_rows>0) {
            //$ret = mysqli_fetch_array($res);
            $ret = mysqli_fetch_all($res);
            return $ret;
        } else {
            return false;
        }
    }