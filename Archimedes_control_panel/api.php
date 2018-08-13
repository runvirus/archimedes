<?php
require_once "functions.php";
require_once 'DBconnect.php';

if(isset($_GET["hwid"]) && !empty($_GET["hwid"])){
  $hwid = $_GET["hwid"];

  if(!validateHwid($hwid, $mysqli)){
    die("Invalid HWID");
  }

  if(isset($_GET["host"]) && !empty($_GET["host"])){
    $host = $_GET["host"];

    if(!validateHost($host, $mysqli)){
      die("Invalid Host");
    }

    if(isset($_GET["action"]) && !empty($_GET["action"])){
      $action = $_GET["action"];

      if($action == "fetchpath"){
        echo fetchPath($host, $mysqli);
      }

      if($action == "fileentry"){
        if(!empty($_POST["json"])){
          $json = $_POST["json"];

          if(fileEntry($json, $mysqli, $host)){
            echo "1";
          } else {
            die("0");
          }
        } else {
          // Empty json argument
        }
      }
      if($action == "difffileentry"){
        if(!empty($_POST["json"])){
          $json = $_POST["json"];

          if(diffFileEntry($json, $mysqli, $host)){
            echo "1";
          } else {
            die("0");
          }
        } else {
          // Empty json argument
        }
      }
      if($action == "gettime"){
        echo getTime($host, $mysqli);
      }
      if($action == "gethashes"){
        getHashes($host, $mysqli);
      }
    }
  } else {
    die("Host missing.");
  }
} else {
  die("HWID missing.");
}
