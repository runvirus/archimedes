<?php
  $DBhost = "localhost";
  $DBuser = "root";
  $DBpass = "";
  $DBname = "wazf";

  $mysqli = new MySQLi($DBhost,$DBuser,$DBpass,$DBname);

  if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
  }
