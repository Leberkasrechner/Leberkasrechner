<?php

$env = parse_ini_file(__DIR__ . '/../.env');
global $conn;
$conn = new mysqli($env["DBSERVER"], $env["DBUSER"], $env["DBPASSWORD"], $env["DBNAME"], intval($env["DBPORT"]));
if ($conn->connect_error) {
    die("DB Connection Error");
}



function getValue($table, $column_given, $value_given, $column_searched, $exit=false, $dbconnection = null) {
        global $conn;
        $dbconn = $conn;
        if(!empty($dbconnection)) {$dbconn = $dbconnection;} # Wenn übergeben, nutze die angegebene Conncetion


        if(!(isset($table) && isset($column_given) && isset($value_given) && isset($column_searched))) {
            return " ";
        }

        $sql = "SELECT $column_searched FROM $table WHERE $column_given = '$value_given'";
        $res = $dbconn->query($sql);
        if($res && $res->num_rows>0) {
            $ret = $res->fetch_assoc()[$column_searched];
            return $ret;
        } else {
            if($exit) {exit();}
            return false;
        }
    }

    function getEntity($table, $column_given, $value_given, $fetch=true, $dbconnection = null) {

        global $conn;
        $dbconn = $conn;
        if(!empty($dbconnection)) {$dbconn = $dbconnection;} # Wenn übergeben, nutze die angegebene Conncetion
        // Check connection
        if ($dbconn->connect_error) {
            die("Connection failed: " . $dbconn->connect_error);
        }

        $sql = "SELECT * FROM $table WHERE $column_given = '$value_given'";
        $res = $dbconn->query($sql);
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