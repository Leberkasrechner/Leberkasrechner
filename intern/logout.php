<?php
  session_name("leberkasrechner_sessid");
  session_start();
  $_SESSION = array();
  session_destroy();
  header("Location: login.php?logout=1");
  exit;
?>